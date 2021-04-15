<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Finance\Contract;

class EndMatureContractJob implements ShouldQueue
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
        $releaseDate = date('Y-m-d 00:00:00', strtotime('+1 days'));
        $capital = $contract->principal + $contract->compounded;

        try {
            $contract->status = 2;
            $contract->principal = $capital;
            $contract->compounded = 0;
            $contract->next_yield_at = $releaseDate;
            $contract->save();
        } catch (\Throwable $th) {
            $this->fail();
        }

        return;
    }
}
