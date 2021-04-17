<?php

namespace App\Jobs;

use App\Model\Transferwd;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Exception\TronException;
use App\Http\Controllers\Controller;
use App\User;
use App\Notifications\eIDRNotification;
use App\Jobs\eIDRrebalanceJob;
use App\KbbBonus;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class KonversieIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id; //id of conversion request

    public function __construct($id)
    {

        $this->id = $id;
    }

    public function middleware()
    {
        return [(new WithoutOverlapping($this->id))->dontRelease()];
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
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey($fuse);

        //get WD data
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDKonversiWDeIDR($this->id);
        if ($getData == null) {
            $this->delete();
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
            die($e->getMessage());
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'KonversieIDRJob Fail, UserID: ' . $user->id . ' reward_id: ' . $this->id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }

        //log to app history
        if ($response['result'] == true) {
            $txHash = $response['txid'];

            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'submit_by' => 1,
                'reason' => $txHash,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelWD->getUpdateWD('id', $this->id, $dataUpdate);

            //fail check
            sleep(5);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'KonversieIDRJob Fail, UserID: ' . $user->id . ' reward_id: ' . $this->id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            // record to eIDR log
            DB::table('eidr_logs')->insert([
                'amount' => $getData->wd_total,
                'from' => $from,
                'to' => $to,
                'hash' => $txHash,
                'type' => 1,
                'detail' => 'Konversi Bonus ke eIDR by: ' . $user->user_code,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Send Telegram Notification to user
            $notification = [
                'amount' => $getData->wd_total,
                'type' => 'Konversi Saldo Bonus ke eIDR',
                'hash' => $txHash
            ];

            if ($user->chat_id != null) {
                $user->notify(new eIDRNotification($notification));
            }

            $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

            if ($eIDRbalance < 1500000) {
                eIDRrebalanceJob::dispatch()->onQueue('tron');
            }

            if ($user->affiliate >= 1 && $user->affiliate < 4) {
                KbbBonus::create([
                    'user_id' => $user->id,
                    'affiliate' => $user->affiliate,
                    'type' => 5,
                    'amount' => $getData->wd_total,
                    'hash' => $txHash
                ]);
            }

            return;
        }
    }
}
