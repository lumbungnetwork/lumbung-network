<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Transferwd;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Exception\TronException;
use App\Http\Controllers\Controller;
use App\User;
use App\Notifications\eIDRNotification;
use App\Jobs\eIDRrebalanceJob;

class WDRoyaltiByeIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id; //id of WD request

    public function __construct($id)
    {

        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //prepare Tron
        $fuse = Config::get('services.telegram.test');

        //prepare Tron
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey($fuse);

        //get WD data
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDWDRoyaltiByeIDR($this->id);
        if ($getData == null) {
            dd('WDRoyaltiByeIDRjob stopped, no data');
        }

        $user = User::find($getData->user_id);

        $to = $getData->tron;
        $amount = $getData->wd_total * 100;

        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            $this->fail();
        }

        if (!isset($response['result'])) {
            $this->fail();
        }

        //log to app history
        if ($response['result'] == true) {
            $txHash = $response['txid'];
            //fail check
            sleep(6);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $this->fail();
            }

            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => 1,
                'reason' => $txHash,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelWD->getUpdateWD('id', $this->id, $dataUpdate);

            $notification = [
                'amount' => $getData->wd_total,
                'type' => 'Konversi Bonus Royalti ke eIDR',
                'hash' => $txHash
            ];

            if ($user->chat_id != null) {
                $user->notify(new eIDRNotification($notification));
            }

            $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

            if ($eIDRbalance < 1500000) {
                eIDRrebalanceJob::dispatch()->onQueue('tron');
            }

            return;
        }
    }
}
