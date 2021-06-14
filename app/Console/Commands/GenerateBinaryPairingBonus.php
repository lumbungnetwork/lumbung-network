<?php

namespace App\Console\Commands;

use App\Model\Member\BonusBinary;
use App\Model\Member\EidrBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class GenerateBinaryPairingBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonus:binary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and Generate Binary Pairing bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Count yesterday's revenue from membership using placement_at to define time constrain
        $revenue = User::where('placement_at', '>=', date('Y-m-d 00:00:01', strtotime('yesterday')))
            ->where('placement_at', '<', date('Y-m-d 00:00:00', strtotime('today')))
            ->count();
        // End this command when there's no revenue
        if ($revenue == 0) {
            return;
        }
        // Check if Binary Index History record already exist
        $check = DB::table('binary_index_history')->where('date', date('Y-m-d', strtotime('yesterday')))->exists();
        if ($check) {
            return;
        }
        // Get All Users premiumed and placed (defined by placement_at) at least yesterday
        $users = User::where('placement_at', '<', date('Y-m-d 00:00:00'))
            ->where('user_type', 10)
            ->get();

        $totalPairs = 0;
        $matchArr = [];
        // Iterate on Users collection to count binary matching pairs
        foreach ($users as $user) {
            $left = 0;
            if ($user->left_id) {
                // Upline details is an upward user_id chain following upline_id sequence
                $uplines = $user->upline_detail . ',[' . $user->id . '],[' . $user->left_id . ']';
                $count = User::where('upline_detail', 'LIKE', $uplines . '%')
                    ->where('member_type', '>', 0)
                    ->count();
                // We add +1 to count lowest node (because upline_detail only get as far as the upline)
                $left = $count + 1;
            }
            $right = 0;
            if ($user->right_id) {
                // Upline details is an upward user_id chain following upline_id sequence
                $uplines = $user->upline_detail . ',[' . $user->id . '],[' . $user->right_id . ']';
                $count = User::where('upline_detail', 'LIKE', $uplines . '%')
                    ->where('member_type', '>', 0)
                    ->count();
                // We add +1 to count lowest node (because upline_detail only get as far as the upline)
                $right = $count + 1;
            }
            // Get Binary History to check the paid/waiting balance
            $binaryHistory = DB::table('binary_history')
                ->selectRaw(' sum(total_left) as sum_left,
                            sum(total_right) as sum_right')
                ->where('user_id', $user->id)
                ->first();
            // Deduct from binaryHistory to check remaining binary balance
            $checkLeft = $left - $binaryHistory->sum_left;
            $checkRight = $right - $binaryHistory->sum_right;
            if ($checkLeft > 0 && $checkRight > 0) {
                // Take the lesser value as qualified pairs
                $pairs = $checkLeft;
                if ($checkLeft > $checkRight) {
                    $pairs = $checkRight;
                }
                // Accumulate data
                $totalPairs += $pairs;
                $matchArr[] = (object) [
                    'user_id' => $user->id,
                    'pairs' => $pairs
                ];
            }
        }

        // Calculate Bonus index based on pairs count against revenue
        // index = total yesterday's revenue divided by total (yesterday) matching pairs multiplied by base index
        // base index = 20000 (20% of membership fee)
        $index = round($revenue / $totalPairs * 20000, 2);
        if ($index > 20000) {
            $index = 20000;
        }

        if ($totalPairs) {
            foreach ($matchArr as $bonus) {
                try {
                    // Create BonusBinary Model Record
                    // user_id and index_date are unique, in case of duplicate entry will move to catch block
                    $binary = new BonusBinary;
                    $binary->user_id = $bonus->user_id;
                    $binary->amount = round($bonus->pairs * $index, 2);
                    $binary->pairs = $bonus->pairs;
                    $binary->index = $index;
                    $binary->index_date = date('Y-m-d', strtotime('yesterday'));
                    $binary->total_premiums = $revenue;
                    $binary->total_pairs = $totalPairs;
                    $binary->save();

                    // Create internal eIDR credit record
                    $balance = new EidrBalance;
                    $balance->user_id = $binary->user_id;
                    $balance->amount = $binary->amount;
                    $balance->type = 1;
                    $balance->source = 3;
                    $balance->tx_id = $binary->id;
                    $balance->note = 'Bonus Pairing ' . $binary->index_date;
                    $balance->save();

                    // Create Binary history record to update binary remaining balance
                    DB::table('binary_history')->insert([
                        'user_id' => $binary->user_id,
                        'total_left' => $binary->pairs,
                        'total_right' => $binary->pairs,
                        'binary_date' => $binary->index_date
                    ]);
                } catch (\Throwable $th) {
                    // skip this iteration
                    continue;
                }
            }

            // Create Binary Index History record
            DB::table('binary_index_history')->insertOrIgnore([
                'date' => date('Y-m-d', strtotime('yesterday')),
                'index' => $index,
                'total_premiums' => $revenue,
                'total_pairs' => $totalPairs
            ]);
        }
        // Done.
        return;
    }
}
