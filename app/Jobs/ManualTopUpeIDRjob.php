<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Model\Bonus;
use App\User;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\Jobs\eIDRrebalanceJob;
use App\Notifications\eIDRNotification;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Model\Member\EidrBalance;
use App\Model\Member\EidrBalanceTransaction;

class ManualTopUpeIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $topup_id;
    private $approval;

    public function __construct($approval, $topup_id)
    {
        $this->topup_id = $topup_id;
        $this->approval = $approval;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->topup_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //prepare data and check
        $data = EidrBalanceTransaction::find($this->topup_id);
        if ($data == null || $data->status != 1) {
            $this->delete();
            return;
        }

        if ($this->approval == 0) {
            $data->status = 3;
            $data->save();
            return;
        }

        $method = 'Bank ' . $data->tx_id;
        if ($data->method == 2) {
            $method = 'TRON';
        }

        // Create Internal eIDR balance
        $balance = new EidrBalance;
        $balance->user_id = $data->user_id;
        $balance->amount = $data->amount;
        $balance->type = 1;
        $balance->source = 5;
        $balance->tx_id = $data->id;
        $balance->note = 'Deposit via ' . $method;
        $balance->save();

        $data->status = 2;
        $data->save();


        return;
    }
}
