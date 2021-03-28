<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateContractYieldJob;
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
        GenerateContractYieldJob::dispatch(1)->onQueue('mail');
        GenerateContractYieldJob::dispatch(2)->onQueue('mail');
        GenerateContractYieldJob::dispatch(3)->onQueue('mail');
        return 'done';
    }
}
