<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Bonus;

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
        $modelBonus = new Bonus;

        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $todaysDividendPool = 2 / 100 * $LMBDividendPool;
        $dividendPerLMB = $todaysDividendPool / $totalStakedLMB;

        $stakers = $modelBonus->getAllStakers();

        foreach ($stakers as $staker) {
            $netStakedLMB = $staker->staked - $staker->unstaked;

            if ($netStakedLMB > 0) {
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