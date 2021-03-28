<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Finance\Contract;

class ActivateContractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $contract_id;

    public function __construct($contract_id)
    {
        $this->contract_id = $contract_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contract = Contract::find($this->contract_id);
        $contract->status = 1;
        $contract->grade = 1;
        $contract->save();
    }
}
