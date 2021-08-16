<?php

namespace App\Jobs;

use App\Model\Member\EidrBalanceTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\Controller;
use IEXBase\TronAPI\Exception\TronException;
use App\Jobs\eIDRrebalanceJob;
use App\User;

class WithdrawInternalEidrViaTronJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $transaction_id;
    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->transaction_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get data and check
        $data = EidrBalanceTransaction::find($this->transaction_id);
        if (!$data) {
            $this->delete();
            return;
        }

        if ($data->status != 1) {
            $this->delete();
            return;
        }

        $user = User::where('id', $data->user_id)->select('id', 'username', 'tron')->first();

        // prepare Tron
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.eidr_hot'));

        $to = $user->tron;
        $amount = $data->amount * 100;

        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            Telegram::sendMessage([
                'chat_id' => config('services.telegram.supervisor'),
                'text' => 'WithdrawEidrByTron Fail, transaction_id: ' . $this->transaction_id,
            ]);
            return;
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => config('services.telegram.supervisor'),
                'text' => 'WithdrawEidrByTron Fail, transaction_id: ' . $this->transaction_id,
            ]);
            return;
        }

        //cleanup
        if ($response['result'] == true) {

            // record to EidrBalanceTransaction
            $data->status = 2;
            $data->tx_id = $response['txid'];
            $data->save();

            //fail check
            sleep(10);
            try {
                $tron->getTransaction($response['txid']);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'WithdrawEidrByTron Fail, transaction_id: ' . $this->transaction_id,
                ]);
                return;
            }

            $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

            if ($eIDRbalance < 2500000) {
                eIDRrebalanceJob::dispatch()->onQueue('tron');
            }
        }
    }
}
