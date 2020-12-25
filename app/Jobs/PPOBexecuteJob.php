<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Model\Member;
use App\Model\Pin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PPOBexecuteJob implements ShouldQueue
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelMember = new Member;
        $modelPin = new Pin;
        $fuse = Config::get('services.telegram.test');
        $tgAk = Config::get('services.telegram.eidr');
        $client = new Client;
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey($fuse);

        $getDataMaster = $modelPin->getJobExecutePPOB($this->masterSalesID);
        $getDataAPI = $modelMember->getDataAPIMobilePulsa();
        $username   = $getDataAPI->username;
        $apiKey   = $getDataAPI->api_key;
        $ref_id = $getDataMaster->ppob_code;
        $sign = md5($username . $apiKey . $ref_id);

        $amount = $getDataMaster->ppob_price * 100;
        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        //pulsa, data, pln prepaid and emoneys
        if ($getDataMaster->type >= 1 && $getDataMaster->type < 4 || $getDataMaster->type >= 21 && $getDataMaster->type < 30) {
            $array = array(
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }
        //pasca
        if ($getDataMaster->type >= 4 && $getDataMaster->type < 11) {
            $array = array(
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $getDataMaster->buyer_code,
                'customer_no' => $getDataMaster->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            );
        }

        $url = $getDataAPI->master_url . '/v1/transaction';
        $json = json_encode($array);
        $cek = $modelMember->getAPIurlCheck($url, $json);
        $arrayData = json_decode($cek, true);

        if ($arrayData == null) {
            Log::debug('stopped because Data from DF is null');
            die();
        }

        if ($arrayData['data']['status'] == 'Pending') {
            if ($getDataMaster->type >= 4 && $getDataMaster->type < 11) {
                $array = array(
                    'commands' => 'status-pasca',
                    'username' => $username,
                    'buyer_sku_code' => $getDataMaster->buyer_code,
                    'customer_no' => $getDataMaster->product_name,
                    'ref_id' => $ref_id,
                    'sign' => $sign,
                );
            } else {
                $array = array(
                    'username' => $username,
                    'buyer_sku_code' => $getDataMaster->buyer_code,
                    'customer_no' => $getDataMaster->product_name,
                    'ref_id' => $ref_id,
                    'sign' => $sign,
                );
            }

            $url = $getDataAPI->master_url . '/v1/transaction';
            $json = json_encode($array);

            do {
                $i = 0;
                if ($i > 26) goto end;
                sleep(3); //give it a break, brotha ;D
                $cek = $modelMember->getAPIurlCheck($url, $json);
                $arrayData = json_decode($cek, true);
                $i++;

                if ($arrayData == null) {
                    dd('stopped because Data from DF is null (do while)');
                }
            } while ($arrayData['data']['status'] == 'Pending');
        }

        //deliver the bad news first
        if ($arrayData['data']['status'] == 'Gagal') {

            //refund eIDR to buyer
            $to = $modelMember->getUserTronAddress($getDataMaster->user_id);

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                $message = null;
                if ($arrayData['data']['message'] != null) {
                    $message = $arrayData['data']['message'];
                }
                $dataUpdate = array(
                    'status' => 3,
                    'reason' => $message,
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'return_buy' => $cek,
                    'vendor_approve' => 3,
                    'vendor_cek' => $response['txid']
                );
                $modelPin->getUpdatePPOB('id', $this->masterSalesID, $dataUpdate);

                return;
            } else {
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => 'eIDR Refund FAILED on PPOB Transaction:   user_id= ' . $getDataMaster->user_id . '  MasterSalesID= ' . $getDataMaster->id,
                        'parse_mode' => 'markdown'
                    ]
                ]);

                return;
            }
        }

        //so the good news taste sweeter
        if ($arrayData['data']['status'] == 'Sukses') {

            //deduct vendor's deposit balance
            $cekDuaKali = $modelPin->getJagaGaBolehDuaKali($getDataMaster->buyer_code . '-' . $ref_id);
            if ($cekDuaKali == null) {
                $memberDeposit = array(
                    'user_id' => $getDataMaster->vendor_id,
                    'total_deposito' => $getDataMaster->harga_modal,
                    'transaction_code' => $getDataMaster->buyer_code . '-' . $ref_id,
                    'deposito_status' => 1
                );
                $modelPin->getInsertMemberDeposit($memberDeposit);
            }

            if ($arrayData['data']['buyer_last_saldo'] < 1000000) {
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => 'Saldo Digiflazz tinggal ' . $arrayData['data']['buyer_last_saldo'],
                        'parse_mode' => 'markdown'
                    ]
                ]);
            }

            //forward eIDR to seller
            $to = $modelMember->getUserTronAddress($getDataMaster->vendor_id);

            //send eIDR
            try {
                $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                $signedTransaction = $tron->signTransaction($transaction);
                $response = $tron->sendRawTransaction($signedTransaction);
            } catch (TronException $e) {
                die($e->getMessage());
            }

            if ($response['result'] == true) {
                $dataUpdate = array(
                    'status' => 2,
                    'tuntas_at' => date('Y-m-d H:i:s'),
                    'return_buy' => $cek,
                    'vendor_approve' => 2,
                    'vendor_cek' => $response['txid']
                );
                $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate);
                return;
            } else {
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => 'eIDR Forwarding FAILED on PPOB Transaction:   vendor_id= ' . $getDataMaster->vendor_id . '  MasterSalesID= ' . $getDataMaster->id,
                        'parse_mode' => 'markdown'
                    ]
                ]);

                return;
            }
        }

        //get over it
        end:
        if ($arrayData['data']['status'] == 'Pending') {
            $dataUpdate = array(
                'vendor_cek' => $cek,
                'vendor_approve' => 1
            );
            $modelPin->getUpdatePPOB('id', $getDataMaster->id, $dataUpdate);
            Log::debug('stopped because status Pending');
            die();
        }
    }
}
