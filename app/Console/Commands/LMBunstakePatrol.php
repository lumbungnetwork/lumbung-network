<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendUnstakedLMBJob;

class LMBunstakePatrol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmb:unstakepatrol';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily patrol to check mature LMB Unstake';

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
        $dataCheck = DB::table('unstake')
            ->select('amount', 'user_id', 'staking_id', 'id')
            ->whereDate('due_date', '<=', date('Y-m-d H:i:s', strtotime('Today +5 minutes')))
            ->where('status', 0)
            ->get();

        if (count($dataCheck) > 0) {
            foreach ($dataCheck as $data) {
                SendUnstakedLMBJob::dispatch($data->user_id, $data->staking_id, $data->amount)->onQueue('tron');
            }
        }
    }
}
