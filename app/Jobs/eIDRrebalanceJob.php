<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
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
        $tokenID = '1002652';
        $to = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $depositAddress = 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge';
        $royaltiAddress = 'TZHYx9bVa4vQz8VpVvZtjwMb4AHqkUChiQ';
        $depositBalance = $tron->getTokenBalance($tokenID, $depositAddress, $fromTron = false) / 100;
        $royaltiBalance = $tron->getTokenBalance($tokenID, $royaltiAddress, $fromTron = false) / 100;

        //From Deposit Vendor
        if ($depositBalance > 10) {
            $amount = ($depositBalance - 10) * 100;
            $tron->setPrivateKey(Config::get('services.eidr.deposit'));

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $depositAddress);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if (!isset($response['result'])) {
                $this->fail();
            }

            $txHash = $response['txid'];
            //fail check
            sleep(5);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $this->fail();
            }
        }

        //From Royalti
        if ($royaltiBalance > 10) {
            $amount = ($royaltiBalance - 10) * 100;
            $tron->setPrivateKey(Config::get('services.eidr.royalti'));

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $royaltiAddress);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if (!isset($response['result'])) {
                $this->fail();
            }

            $txHash = $response['txid'];
            //fail check
            sleep(5);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $this->fail();
            }
        }

        $message_text = 'eIDR Rebalancing just executed' . chr(10);
        $message_text .= 'From Deposit: Rp' . number_format($depositBalance) . chr(10);
        $message_text .= 'From Royalti: Rp' . number_format($royaltiBalance) . chr(10);

        Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.overlord'),
            'text' => $message_text,
            'parse_mode' => 'markdown'
        ]);

        return;
    }
}
