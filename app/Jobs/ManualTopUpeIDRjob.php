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
use App\Jobs\eIDRrebalanceJob;
use App\Notifications\eIDRNotification;
use Telegram\Bot\Laravel\Facades\Telegram;

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
            $this->delete();
        }

        $user = User::find($dataUser->user_id);

        if ($this->approval == 0) {
            $dataUpdate = array(
                'status' => 3,
                'reason' => 'Rejected (TG)',
                'deleted_at' => date('Y-m-d H:i:s'),
                'submit_by' => $user->id,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateTopUp('id', $this->topup_id, $dataUpdate);
            return;
        } elseif ($this->approval == 1) {
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

            if (!isset($response['result'])) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ManualTopUpeIDR Fail, UserID: ' . $user->id . ' topup_id: ' . $this->topup_id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            if ($response['result'] == true) {
                $txHash = $response['txid'];
                //fail check
                sleep(10);
                try {
                    $tron->getTransaction($txHash);
                } catch (TronException $e) {
                    $response = Telegram::sendMessage([
                        'chat_id' => Config::get('services.telegram.overlord'),
                        'text' => 'ManualTopUpeIDR Fail, UserID: ' . $user->id . ' topup_id: ' . $this->topup_id,
                        'parse_mode' => 'markdown'
                    ]);
                    return;
                }

                $dataUpdate = array(
                    'status' => 2,
                    'reason' => $txHash,
                    'tuntas_at' => date('Y-m-d H:i:s'),
                    'submit_by' => $getData->user_id,
                    'submit_at' => date('Y-m-d H:i:s'),
                );
                $modelBonus->getUpdateTopUp('id', $getData->id, $dataUpdate);

                $notification = [
                    'amount' => $getData->nominal,
                    'type' => 'Top-up eIDR',
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
}
