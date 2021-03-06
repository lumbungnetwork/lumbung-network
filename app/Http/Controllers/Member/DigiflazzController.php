<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Member\DigitalSale;
use App\Model\Member\EidrBalance;
use GuzzleHttp\Client;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Model\Member\LMBdividend;

class DigiflazzController extends Controller
{
    public function handleRequest(Request $request)
    {
        $secret = config('services.digiflazz.webhook_secret');
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        if ($request->header('X-Hub-Signature') == 'sha1=' . $signature) {
            $payload = json_decode($request->getContent(), true);

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
                    $divProportion = config('services.lmb_div.proportion'); // proportion from Profit Sharing Pool
                    if ($salesData->type <= 2) {
                        $lmbDiv = (config('services.lmb_div.cellular') / 100) * $salesData->ppob_price; //Cellular
                    } elseif ($salesData->type = 3) {
                        $lmbDiv = $divProportion * config('services.lmb_div.pln_prepaid'); //PLN Prepaid
                    } elseif ($salesData->type = 4) {
                        $lmbDiv = $divProportion * config('services.lmb_div.telkom'); //Telkom
                    } elseif ($salesData->type = 5) {
                        $lmbDiv = $divProportion * config('services.lmb_div.pln_postpaid'); //PLN Postpaid
                    } elseif ($salesData->type = 6) {
                        $lmbDiv = $divProportion * config('services.lmb_div.hp_postpaid'); //HP Postpaid
                    } elseif ($salesData->type = 7) {
                        $lmbDiv = $divProportion * config('services.lmb_div.bpjs'); //BPJS
                    } elseif ($salesData->type = 8) {
                        $lmbDiv = $divProportion * config('services.lmb_div.pdam'); //PDAM
                    } elseif ($salesData->type = 9) {
                        $lmbDiv = $divProportion * config('services.lmb_div.pgn'); //PGN
                    } elseif ($salesData->type = 10) {
                        $lmbDiv = $divProportion * config('services.lmb_div.multifinance'); //Multifinance
                    } elseif ($salesData->type >= 21) {
                        $lmbDiv = $divProportion * config('services.lmb_div.emoney'); //e-Money
                    }

                    // Create LMBdividend
                    $dividend = new LMBdividend;
                    $dividend->amount = $lmbDiv;
                    $dividend->type = 3;
                    $dividend->status = 1;
                    $dividend->source_id = $salesData->id;
                    $dividend->save();
                }

                //low balance notif
                if ($payload['data']['buyer_last_saldo'] < 1500000) {
                    Telegram::sendMessage([
                        'chat_id' => config('services.telegram.supervisor'),
                        'text' => 'Digiflazz balance left: Rp' . number_format($payload['data']['buyer_last_saldo'], 0)
                    ]);
                }
            }
        }

        return;
    }


    public function postpaidInquiry($buyer_sku_code, $customer_no, $type)
    {
        // Digiflazz Credentials
        $username = config('services.digiflazz.user');
        $apiKey = config('services.digiflazz.key');

        // payload
        $DigitalSale = new DigitalSale;
        $ref_id = $DigitalSale->getCodeRef($type);
        $sign = md5($username . $apiKey . $ref_id);
        $json = json_encode([
            'commands' => 'inq-pasca',
            'username' => $username,
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $customer_no,
            'ref_id' => $ref_id,
            'sign' => $sign,
        ]);
        // endpoint
        $url = 'https://api.digiflazz.com/v1/transaction';

        // use Guzzle Client
        $client = new Client;
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => $json
        ]);

        return json_decode($response->getBody(), true);
    }

    public function prepaidPLNInquiry(Request $request)
    {

        // payload
        $json = json_encode([
            'commands' => 'pln-subscribe',
            'customer_no' => $request->customer_no
        ]);
        // endpoint
        $url = 'https://api.digiflazz.com/v1/transaction';

        // use Guzzle Client
        $client = new Client;
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => $json
        ]);

        $res = json_decode($response->getBody(), true);
        return response()->json([
            'success' => true,
            'customer_no' => $res['data']['customer_no'],
            'name' => $res['data']['name'],
            'segment_power' => $res['data']['segment_power']
        ]);
    }
}
