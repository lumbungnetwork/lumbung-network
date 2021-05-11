<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Finance;
use App\Model\Finance\Credit;
use App\Http\Controllers\Controller;

class GenerateActiveCreditsYield implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->user_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = Finance::find($this->user_id);

        // Check credit current balance, if less than 100 turn to inactive
        $Credit = new Credit;
        $creditBalance = $Credit->getUserNetCreditBalance($user->id);
        if ($creditBalance < 100) {
            $user->active_credits = 0;
            $user->save();
            return;
        }

        // Check to prevent double credit in same day
        $check = Credit::where('user_id', $user->id)
            ->where('source', 5)
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime('Today +1 minute')))
            ->exists();
        if ($check) {
            $this->delete();
            return;
        }

        // 12% APY (11.3% APR, 0.03% daily interest)
        $interest = 0.0003;
        $yield = round($creditBalance * $interest, 2);

        $controller = new Controller;

        // Generate new positive credit
        $credit = new Credit;
        $credit->user_id = $user->id;
        $credit->amount = $yield;
        $credit->type = 1;
        $credit->source = 5;
        $credit->tx_id = $controller->createCreditTxId($yield, 1, 4, 0);
        $credit->save();

        return;
    }
}
