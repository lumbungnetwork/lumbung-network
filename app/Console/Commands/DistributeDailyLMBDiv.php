<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Bonus;
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

        $modelBonus = new Bonus;

        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $todaysDividendPool = 2 / 100 * $LMBDividendPool;
        $dividendPerLMB = $todaysDividendPool / $totalStakedLMB;

        $stakers = $modelBonus->getAllStakers();

        foreach ($stakers as $staker) {
            $netStakedLMB = $staker->net;
            $premiumMembership = User::where('id', $staker->user_id)->where('user_type', 10)->exists();

            if ($netStakedLMB > 0 && $premiumMembership) {
                $dividend = round($netStakedLMB * $dividendPerLMB, 2, PHP_ROUND_HALF_DOWN);
                $modelBonus->insertUserDividend([
                    'user_id' => $staker->user_id,
                    'type' => 1,
                    'amount' => $dividend,
                    'date' => date('Y-m-d')
                ]);
            }
        }

        $modelBonus->insertLMBDividend([
            'amount' => round($todaysDividendPool, 2),
            'type' => 0,
            'status' => 0,
            'source_id' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
