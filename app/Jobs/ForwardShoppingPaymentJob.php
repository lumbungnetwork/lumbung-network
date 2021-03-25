<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Sales;
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
    protected $sellerType;
    public function __construct($masterSalesID, $sellerType)
    {
        $this->masterSalesID = $masterSalesID;
        $this->sellerType = $sellerType;
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

        //get Shopping Data
        $modelSales = new Sales;
        $shoppingData = $modelSales->getMasterSalesDataByType($this->masterSalesID, $this->sellerType);
        if ($shoppingData->status != 2) {
            return;
        } elseif ($shoppingData->status == 2) {
            $totalPrice = $shoppingData->total_price;
            $royalti = 0;
            $sellerTron = null;
            if ($this->sellerType == 1) {
                $royalti = $totalPrice * 2 / 100; //reduction from proposal #04
                $seller = User::find($shoppingData->stockist_id);
                $sellerTron = $seller->tron;
                $lmbDiv = $royalti / 2; // 1% from sales
            } elseif ($this->sellerType == 2) {
                $royalti = $totalPrice * 2 / 100;
                $seller = User::find($shoppingData->vendor_id);
                $sellerTron = $seller->tron;
                $lmbDiv = $royalti; // 2% of sales
            }
            if ($sellerTron == null) {
                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'ForwardShoppingPaymentJob failed because the seller address is null' . chr(10) . 'Seller: ' . $seller->user_code,
                    'parse_mode' => 'markdown'
                ]);
                $this->delete();
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
                die($e->getMessage());
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

                //fail check
                sleep(5);
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

                if ($eIDRbalance < 1500000) {
                    eIDRrebalanceJob::dispatch()->onQueue('tron');
                }

                $modelBonus = new Bonus;
                $modelBonus->insertLMBDividend([
                    'amount' => $lmbDiv,
                    'type' => $this->sellerType,
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
