<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\Model\Sales;
use App\Model\Member\MasterSales;
use App\User;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Exception\TronException;
use App\Model\Bonus;
use App\Http\Controllers\Controller;
use App\Jobs\eIDRrebalanceJob;
use Telegram\Bot\Laravel\Facades\Telegram;

class ForwardShoppingPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $masterSalesID;
    public function __construct($masterSalesID)
    {
        $this->masterSalesID = $masterSalesID;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->masterSalesID))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //prepare Tron
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.eidr_hot'));

        //get Shopping Data
        $shoppingData = MasterSales::find($this->masterSalesID);

        // check for duplicate trial
        if ($shoppingData->tron != null) {
            $this->delete();
            return;
        }

        // check tx status
        if ($shoppingData->status != 2) {
            $this->delete();
            return;
        } elseif ($shoppingData->status == 2) {
            $totalPrice = $shoppingData->total_price;
            $royalti = $totalPrice * 2 / 100;
            $seller = User::find($shoppingData->stockist_id);
            $sellerTron = $seller->tron;
            $lmbDiv = $royalti / 2; // 1% from sales

            if ($sellerTron == null) {
                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ForwardShoppingPaymentJob failed because the seller address is null' . chr(10) . 'Seller: ' . $seller->username,
                    'parse_mode' => 'markdown'
                ]);
                $this->delete();
                return;
            }

            $netPayment = $totalPrice - $royalti;

            $to = $sellerTron;
            $amount = $netPayment * 100;

            $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
            $tokenID = '1002652';

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            if (!isset($response['result'])) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            //cleanup
            if ($response['result'] == true) {

                // record forward transfer to MasterSales
                $shoppingData->tron = $response['txid'];
                $shoppingData->save();

                //fail check
                sleep(10);
                try {
                    $tron->getTransaction($response['txid']);
                } catch (TronException $e) {
                    $response = Telegram::sendMessage([
                        'chat_id' => Config::get('services.telegram.overlord'),
                        'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID,
                        'parse_mode' => 'markdown'
                    ]);
                    return;
                }

                $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

                if ($eIDRbalance < 2500000) {
                    eIDRrebalanceJob::dispatch()->onQueue('tron');
                }

                // Add dividend to Dividend Pool
                $modelBonus = new Bonus;
                $modelBonus->insertLMBDividend([
                    'amount' => $lmbDiv,
                    'type' => 1,
                    'status' => 1,
                    'source_id' => $this->masterSalesID,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                return;
            } else {
                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ForwardShoppingPaymentJob failed because anomaly' . chr(10) . 'MasterSalesID: ' . $this->masterSalesID,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }
        }
    }
}
