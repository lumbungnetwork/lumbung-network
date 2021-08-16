<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\Model\Member\LMBdividend;
use App\Model\Member\MasterSales;
use App\User;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Exception\TronException;
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
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'ForwardShoppingPaymentJob failed because the seller address is null' . chr(10) . 'Seller: ' . $seller->usernamed
                ]);
                $this->delete();
                return;
            }

            $netPayment = $totalPrice - $royalti;

            $to = $sellerTron;
            $amount = $netPayment * 100;

            $from = config('services.tron.address.eidr_hot');
            $tokenID = config('services.tron.token_id.eidr');

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID
                ]);
                return;
            }

            if (!isset($response['result'])) {
                $response = Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID
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
                    Telegram::sendMessage([
                        'chat_id' => config('services.telegram.supervisor'),
                        'text' => 'ForwardShoppingPayment Fail, UserID: ' . $seller->id . ' sales_id: ' . $this->masterSalesID
                    ]);
                    $this->fail();
                }

                $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

                if ($eIDRbalance < 2500000) {
                    eIDRrebalanceJob::dispatch()->onQueue('tron');
                }

                // Create LMBdividend (1% from sales)
                $dividend = new LMBdividend;
                $dividend->amount = $lmbDiv;
                $dividend->type = 1;
                $dividend->status = 1;
                $dividend->source_id = $this->masterSalesID;
                $dividend->save();

                return;
            } else {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'ForwardShoppingPaymentJob failed cause: response result != true' . chr(10) . 'MasterSalesID: ' . $this->masterSalesID
                ]);
                $this->fail();
            }
        }
    }
}
