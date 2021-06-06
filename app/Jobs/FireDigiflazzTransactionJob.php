<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member\DigitalSale;
use App\Model\Member\EidrBalance;
use App\Model\Bonus;
use Telegram\Bot\Laravel\Facades\Telegram;
use GuzzleHttp\Client;

class FireDigiflazzTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $salesID;
    public function __construct($salesID)
    {
        $this->salesID = $salesID;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->salesID))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get Data
        $data = DigitalSale::findOrFail($this->salesID);
        $ref_id = $data->ppob_code;

        // Prepare Digiflazz Credentials
        $username = config('services.digiflazz.user');
        $apiKey = config('services.digiflazz.key');
        $sign = md5($username . $apiKey . $ref_id);

        // Prepare Payload according to tx type (Prepaid or Postpaid)

        if ($data->type >= 4 && $data->type < 11) {
            $json = json_encode([
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $data->buyer_code,
                'customer_no' => $data->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            ]);
        } else {
            $json = json_encode([
                'username' => $username,
                'buyer_sku_code' => $data->buyer_code,
                'customer_no' => $data->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            ]);
        }

        // endpoint
        $url = 'https://api.digiflazz.com/v1/transaction';

        // use Guzzle Client to send the POST request
        $client = new Client;
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => $json
        ]);

        $payload = json_decode($response->getBody(), true);

        //deliver the bad news first
        if ($payload['data']['status'] == 'Gagal') {
            // Update Sale data
            $salesData = DigitalSale::where('ppob_code', $payload['data']['ref_id'])->first();
            if ($salesData != null) {
                $salesData->status = 3;
                $salesData->reason = $payload['data']['message'];
                $salesData->save();

                // Refund by deleting EidrBalance payment record
                $balance = EidrBalance::find($salesData->tx_id);
                if ($balance != null) {
                    $balance->delete();
                }
            }
        }

        //so the good news taste sweeter
        if ($payload['data']['status'] == 'Sukses') {
            // Update Sale data
            $salesData = DigitalSale::where('ppob_code', $payload['data']['ref_id'])->first();
            if ($salesData != null && $salesData->status == 5) {
                $salesData->status = 2;
                $salesData->return_buy = json_encode($payload);
                $salesData->save();

                // Seller's commission
                if ($salesData->buy_metode == 2) {
                    $commission = $salesData->ppob_price - $salesData->harga_modal;

                    $balance = new EidrBalance;
                    $balance->user_id = $salesData->seller->id;
                    $balance->amount = $commission;
                    $balance->type = 1;
                    $balance->source = 6;
                    $balance->tx_id = $salesData->id;
                    $balance->note = 'Komisi Penjualan Produk Digital';
                    $balance->save();
                }

                //fill dividend pool
                $lmbDiv = 0;
                $divProportion = 0.7; // 70% from Profit Sharing Pool
                if ($salesData->type <= 2) {
                    $lmbDiv = (1.1 / 100) * $salesData->ppob_price;
                } elseif ($salesData->type = 3) {
                    $lmbDiv = $divProportion * 755; //PLN Prepaid
                } elseif ($salesData->type = 4) {
                    $lmbDiv = $divProportion * 600; //Telkom
                } elseif ($salesData->type = 5) {
                    $lmbDiv = $divProportion * 800; //PLN Postpaid
                } elseif ($salesData->type = 6) {
                    $lmbDiv = $divProportion * 600; //HP Postpaid
                } elseif ($salesData->type = 7) {
                    $lmbDiv = $divProportion * 300; //BPJS
                } elseif ($salesData->type = 8) {
                    $lmbDiv = $divProportion * 300; //PDAM
                } elseif ($salesData->type = 9) {
                    $lmbDiv = $divProportion * 400; //PGN
                } elseif ($salesData->type = 10) {
                    $lmbDiv = $divProportion * 1000; //Multifinance
                } elseif ($salesData->type >= 21) {
                    $lmbDiv = $divProportion * 200; //e-Money
                }
                $modelBonus = new Bonus;
                $modelBonus->insertLMBDividend([
                    'amount' => $lmbDiv,
                    'type' => 3,
                    'status' => 1,
                    'source_id' => $salesData->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            //low balance notif
            if ($payload['data']['buyer_last_saldo'] < 1500000) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.overlord'),
                    'text' => 'Digiflazz balance left: Rp' . number_format($payload['data']['buyer_last_saldo'], 0)
                ]);
            }
        }

        return;
    }
}
