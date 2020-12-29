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
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

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
        $tgAk = Config::get('services.telegram.eidr');
        $client = new Client;

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
            $sellerTron = '';
            if ($this->sellerType == 1) {
                $royalti = $totalPrice * 4 / 100;
                $seller = User::find($shoppingData->stockist_id);
                $sellerTron = $seller->tron;
            } elseif ($this->sellerType == 2) {
                $royalti = $totalPrice * 2 / 100;
                $seller = User::find($shoppingData->vendor_id);
                $sellerTron = $seller->tron;
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

            //cleanup
            if ($response['result'] == true) {

                $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

                if ($eIDRbalance < 1500000) {
                    $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                        'query' => [
                            'chat_id' => '365874331',
                            'text' => 'EIDR balance left: ' . $eIDRbalance,
                            'parse_mode' => 'markdown'
                        ]
                    ]);
                }

                return;
            } else {
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => 'Anomaly on ForwardShoppingPayment! MasterSalesID: ' . $this->masterSalesID,
                        'parse_mode' => 'markdown'
                    ]
                ]);
                return;
            }
        }
    }
}
