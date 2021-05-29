<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Member\DigitalSale;
use App\Model\Member\EidrBalance;
use App\Model\Bonus;

class DigiflazzController extends Controller
{
    public function handleRequest(Request $request)
    {
        $secret = config('services.digiflazz.webhook_secret');

        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);
        \Log::info($signature);

        if ($request->header('X-Hub-Signature') == 'sha1=' . $signature && $request->header('X-Digiflazz-Event') == 'update') {
            \Log::info(json_decode($request->getContent(), true));
            $payload = json_decode($request->getContent(), true);

            if ($payload['data']['status'] == 'Gagal') {
                // Update Sale data
                $salesData = DigitalSale::where('ppob_code', $payload['data']['ref_id'])->first();
                $salesData->status = 3;
                $salesData->reason = $payload['data']['message'];
                $salesData->save();

                // Refund by deleting EidrBalance payment record
                $balance = EidrBalance::findOrFail($salesData->tx_id);
                $balance->delete();
            }

            if ($payload['data']['status'] == 'Sukses') {
                // Update Sale data
                $salesData = DigitalSale::where('ppob_code', $payload['data']['ref_id'])->first();
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
        }

        return;
    }
}
