<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Finance\Contract;
use App\Model\Finance\_Yield;
use App\Model\Finance\PerformanceIndex;
use BotMan\BotMan\Messages\Attachments\Contact;

class GenerateContractYieldJob implements ShouldQueue
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
        // Get the contract
        $contract = Contract::find($this->contract_id);

        // Get contract payout cycle 
        $cycle = date('Y-m-d 00:00:00', strtotime('28 days ago')); // securing 1 month (Monthly payout)

        if ($contract->strategy == 2) {
            $cycle = date('Y-m-d 00:00:00', strtotime('13 days ago'));; // securing biweekly (14 days payout)
        }

        // Check to prevent duplicate
        $check = _Yield::where('created_at', '>', $cycle)->where('contract_id', $contract->id)->exists();
        if ($check) {
            $this->delete();
        }

        // Get latest performance index
        $pi = PerformanceIndex::where('strategy', $contract->strategy)->latest()->first();
        $index = $pi->index / 100;

        //Generate Yield based on contract capital (principal + compounded) * MPR (monthly percentage) * index * performance fee
        $mpr = 2 / 100;
        $performanceFee = 1; // Free for Stable coin Lending Strategy

        if ($contract->strategy == 2) {
            $performanceFee = 95 / 100; // 5%, Discounted from 10% standard
            switch ($contract->grade) {
                case 1:
                    $mpr = 3 / 200; // Divided by 200 because generated twice per month
                    break;
                case 2:
                    $mpr = 5 / 200;
                    break;
                case 3:
                    $mpr = 7 / 200;
                    break;
                case 4:
                    $mpr = 9 / 200;
                    break;
            }
        }

        $capital = $contract->principal + $contract->compounded;
        $yieldAmount = ($capital * $mpr) * $index;
        $amount = $yieldAmount * $performanceFee;

        try {
            $yield = new _Yield;
            $yield->contract_id = $contract->id;
            $yield->amount = $amount;
            $yield->type = 1;
            $yield->save();
        } catch (\Throwable $th) {
            $this->fail();
        }

        // Update next_yield_at
        $next_yield_at = '+30 days';
        if ($contract->strategy == 2) {
            $next_yield_at = '+14 days';
        }
        $contract->next_yield_at = date('Y-m-d 00:00:00', strtotime($next_yield_at));
        $contract->save();

        return;
    }
}
