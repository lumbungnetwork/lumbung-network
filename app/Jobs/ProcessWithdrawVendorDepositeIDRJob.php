<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Laravel\Facades\Telegram;
use IEXBase\TronAPI\Exception\TronException;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProcessWithdrawVendorDepositeIDRJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $txID;
    public function __construct($txID)
    {
        $this->txID = $txID;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->txID))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get transaction data
        $tx = DB::table('deposit_transaction')
            ->select('tron_transfer', 'price', 'user_id')
            ->where('id', $this->txID)
            ->first();

        // Get Username
        $user = DB::table('users')
            ->select('user_code')
            ->where('id', $tx->user_id)
            ->first();

        // Get TRON
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.eidr_hot'));

        $amount = $tx->price * 100;
        $to = $tx->tron_transfer;
        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            $text = 'WD Vendor Deposit Failed' . chr(10);
            $text .= 'txID: ' . $this->txID;
            $text .= 'error: ' . $e->getMessage();
            Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => $text
            ]);

            return;
        }

        $txHash = $response['txid'];

        // update transaction record
        DB::table('deposit_transaction')
            ->where('id', $this->txID)
            ->update([
                'tron_transfer' => $txHash
            ]);

        //fail check
        sleep(10);
        try {
            $response = $tron->getTransaction($txHash);
        } catch (TronException $e) {
            $text = 'WD Vendor Deposit Failed' . chr(10);
            $text .= 'txID: ' . $this->txID;
            $text .= 'error: ' . $e->getMessage();
            Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => $text
            ]);

            return;
        }

        // record to eIDR log
        DB::table('eidr_logs')->insert([
            'amount' => $tx->price,
            'from' => $from,
            'to' => $to,
            'hash' => $txHash,
            'type' => 4,
            'detail' => 'Withdraw Vendor Deposit by: ' . $user->user_code,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return;
    }
}
