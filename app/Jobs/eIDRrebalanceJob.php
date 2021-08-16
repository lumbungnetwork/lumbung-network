<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;


class eIDRrebalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $controller = new Controller;
        $tron = $controller->getTron();
        $tokenID = config('services.tron.token_id.eidr');
        $to = config('services.tron.address.eidr_hot');
        $depositAddress = config('services.tron.address.eidr_deposit');
        $depositBalance = $tron->getTokenBalance($tokenID, $depositAddress, $fromTron = false) / 100;

        //From Deposit Vendor
        if ($depositBalance > 10) {
            $amount = ($depositBalance - 10) * 100;
            $tron->setPrivateKey(config('services.eidr.deposit'));

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $depositAddress);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'eIDR Rebalancing Failed'
                ]);
                $this->fail();
            }

            if (!isset($response['result'])) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'eIDR Rebalancing Failed'
                ]);
                $this->fail();
            }

            $txHash = $response['txid'];
            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'eIDR Rebalancing Failed on FAIL CHECK'
                ]);
                $this->fail();
            }
        }

        $message_text = 'eIDR Rebalancing just executed' . chr(10);
        $message_text .= 'From Deposit: Rp' . number_format($depositBalance) . chr(10);

        Telegram::sendMessage([
            'chat_id' => config('services.telegram.supervisor'),
            'text' => $message_text,
            'parse_mode' => 'markdown'
        ]);

        return;
    }
}
