<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Bonus;
use App\User;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;

use App\Notifications\eIDRNotification;

class ManualTopUpeIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $topup_id;

    public function __construct($topup_id)
    {
        $this->topup_id = $topup_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //prepare data
        $modelBonus = new Bonus;
        $dataUser = $modelBonus->getUserIdfromTopUpId($this->topup_id);
        $getData = $modelBonus->getJobTopUpSaldoIDUserId($this->topup_id, $dataUser->user_id);
        if ($getData == null) {
            dd('ManualTopUpeIDRjob stopped, no data');
        }

        $user = User::find($dataUser->user_id);

        //prepare TRON
        $fuse = Config::get('services.telegram.test');

        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey($fuse);

        $to = $getData->tron;
        $amount = $getData->nominal * 100;
        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            die($e->getMessage());
        }

        if ($response['result'] == true) {
            $dataUpdate = array(
                'status' => 2,
                'reason' => $response['txid'],
                'tuntas_at' => date('Y-m-d H:i:s'),
                'submit_by' => $getData->user_id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateTopUp('id', $getData->id, $dataUpdate);

            $notification = [
                'amount' => $getData->nominal,
                'type' => 'Top-up eIDR',
                'hash' => $response['txid']
            ];

            if ($user->chat_id != null) {
                $user->notify(new eIDRNotification($notification));
            }
            return;
        }
    }
}
