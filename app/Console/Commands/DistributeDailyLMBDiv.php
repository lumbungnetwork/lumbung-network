<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Member\LMBdividend;
use App\Model\Member\Staking;
use App\Model\Member\UsersDividend;
use App\User;
use Illuminate\Support\Facades\DB;

class DistributeDailyLMBDiv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmb:dailydiv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute Daily LMB dividend';

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
        $check = DB::table('lmb_dividend')->latest()->first();
        if ($check->created_at >= date('Y-m-d H:i:s', strtotime('Today +2 minutes'))) {
            return;
        }

        $LMBDividend = new LMBdividend;
        $Staking = new Staking;

        $LMBDividendPool = $LMBDividend->getLMBDividendPool();
        $totalStakedLMB = $Staking->getStakedLMB();
        $todaysDividendPool = 2 / 100 * $LMBDividendPool;
        $dividendPerLMB = $todaysDividendPool / $totalStakedLMB;

        $stakers = $Staking->getAllStakers();

        foreach ($stakers as $staker) {
            $netStakedLMB = $staker->net;
            $premiumMembership = User::where('id', $staker->user_id)->where('user_type', 10)->exists();

            // Double check staked amount and premium membership
            if ($netStakedLMB > 0 && $premiumMembership) {
                // Calculate amount based on net staked LMB times today's dividend per LMB
                $amount = round($netStakedLMB * $dividendPerLMB, 2, PHP_ROUND_HALF_DOWN);
                // Create dividend for each user
                $dividend = new UsersDividend;
                $dividend->user_id = $staker->user_id;
                $dividend->type = 1;
                $dividend->amount = $amount;
                $dividend->date = date('Y-m-d');
                $dividend->save();
            }
        }

        // Deduct distributed amount from Dividend pool
        $distributed = new LMBdividend;
        $distributed->amount = round($todaysDividendPool, 2);
        $distributed->type = 0;
        $distributed->status = 0;
        $distributed->source_id = 0;
        $distributed->save();

        return;
    }
}
