<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Model\Bonus;

class SendUnstakedLMBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    protected $staking_id;
    protected $amount;
    public function __construct($user_id, $staking_id, $amount)
    {
        $this->user_id = $user_id;
        $this->staking_id = $staking_id;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $getUserTronAddress = User::where('id', $this->user_id)->select('tron')->first();
        $sendAmount = $this->amount * 1000000;

        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.tron.lmb_staking'));

        $to = $getUserTronAddress->tron;
        $from = 'TY8JfoCbsJ4qTh1r9HBtmZ88xQLsb6MKuZ';
        $tokenID = '1002640';

        //send LMB
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $sendAmount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            die($e->getMessage());
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'SendUnstakedLMB Fail, UserID: ' . $this->user_id . ' staking_id: ' . $this->staking_id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }


        if ($response['result'] == true) {
            $txHash = $response['txid'];
            //fail check
            sleep(15);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'SendUnstakedLMB Fail, UserID: ' . $this->user_id . ' staking_id: ' . $this->staking_id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            //log to app history
            $modelBonus = new Bonus;
            $modelBonus->updateUserStake('id', $this->staking_id, ['hash' => $txHash, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
}
