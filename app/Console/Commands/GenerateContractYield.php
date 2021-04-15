<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateContractYieldJob;
use App\Jobs\EndMatureContractJob;
use App\Model\Finance\Contract;

class GenerateContractYield extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finance:generate_yield';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run daily checking to distribute Yield according to contract terms';

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
        $today = date('Y-m-d 00:00:00', strtotime('today'));

        $eligibleContracts = Contract::where('next_yield_at', '<=', $today)->where('status', '<=', 2)->select('id')->get();
        if (count($eligibleContracts) > 0) {
            foreach ($eligibleContracts as $contract) {
                GenerateContractYieldJob::dispatch($contract->id)->onQueue('mail');
            }
        }

        // Checking for near due contract (Strategy 2)
        $maturedContracts = Contract::where('expired_at', '<=', $today)
            ->where('status', 1)
            ->where('strategy', 2)
            ->select('id')
            ->get();
        if (count($maturedContracts) > 0) {
            foreach ($maturedContracts as $contract) {
                EndMatureContractJob::dispatch($contract->id)->onQueue('mail');
            }
        }

        return;
    }
}
