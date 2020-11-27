<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Bonus;
use GuzzleHttp\Client;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TopUpeIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $topup_id;
    protected $user_id;

    public function __construct($topup_id, $user_id)
    {
        $this->topup_id = $topup_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $modelBonus = new Bonus;
        $tgAk = Config::get('services.telegram.eidr');

        $getData = $modelBonus->getJobTopUpSaldoIDUserId($this->topup_id, $this->user_id);
        if ($getData == null) {
            Log::info('CheckTopUpeIDR stopped: no data');
            dd('stopped, no data');
        }
        //prepare TRON
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        $fuse = Config::get('services.telegram.test');

        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer, $signServer = null, $explorer = null, $fuse);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        //prepare Client
        $client = new Client;

        //prepare moota
        $mootaToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJucWllNHN3OGxsdyIsImp0aSI6ImY0NGE5YjZmNjI1NTllYTVlNjYzODcxMTA0OWM2M2YwYTcxYTU0ZTQ5YTA0YjU1ODQ2NTcxZDc0ZTAyZmM5MTc4ZTZlZDQ3YTQ5NGZkMDY1IiwiaWF0IjoxNjA2MTU1Mjk4LCJuYmYiOjE2MDYxNTUyOTgsImV4cCI6MTYzNzY5MTI5OCwic3ViIjoiMTU2NTIiLCJzY29wZXMiOlsiYXBpIl19.DHQ77NIKrStWYPue21LfAlhWzJUjCHtmHhyKa-yqBPGpgtcywywxcQBqk4odBDXHhPkkj1sE0h_nRxoeRY0cg692XuDBrE5Z0mupTaM8-tlgr8lzBhFrjrleCTwqPxPwkQhoGmM3iNAs8JbwpGZ5LgCJNtfDFW_vHYBA9r-2M9Dug30Ckz1TJgyGxNKrEFVV9ZOzuAie6FjHxp_LSV0bCQvacocNEoYWSqMCxomjvtkGZ9iIPC93WZVsLu7v4Up1Xdz5ZIYk7ZNltN_NCBwgUB6KstTRqcloWBk-ISJW-favXIrlKa-aDiZrngzRgKsCv69bf7PhxmaEMKm1edova5tey1qyIQ9mpYP1TIOU3AeSJj6wPFH20rF6KIBpx-vQg3GnnRj0vmY17bnpzv4bIKImyAUg5S94nuNVqx664mfcggEa1oVwdW9kjhHsp2tAET5g2sASrHbx2yASuPJYYEbTSL1OnyhT0IAIIE1o8jIDsvH69jN7GuDppKwbY4iTQBE4Ctm-y0ds3FGdRevv1yXoRUdJayj2mhrWTb--H01qvDyN542rO1Gk6LA-vTro2PKQvJ3zU2_H_Dc_Rle_01cr8FaS76mz_BmwyQm7-WC9eRnYJKTLRXJ1u2QYUSlRk_zQS-VqYif8j6mhD2fyVjWZo65tBYh8AvRfu_ktD5E';

        $headers = [
            'Authorization' => 'Bearer ' . $mootaToken,
            'Accept'        => 'application/json',
        ];

        //which bank?
        $bankID = 'dE6jRawozNQ'; //Bank Mandiri 1060013300309
        if ($getData->bank_perusahaan_id == 26) {
            $bankID = 'E32zpnxxWA1'; //BRI 033601001791568
        }
        //disable Mandiri for a while
        if ($getData->bank_perusahaan_id == 1) {
            $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                'query' => [
                    'chat_id' => '365874331',
                    'text' => 'Top-up eIDR need Manual Action ' . $getData->user_code . ' nominal:' . number_format($getData->nominal + $getData->unique_digit),
                    'parse_mode' => 'markdown'
                ]
            ]);
            return;
        }

        //call bank refresh mutation

        $client->request('POST', 'https://app.moota.co/api/v2/bank/' . $bankID . '/refresh', [
            'headers' => $headers
        ]);

        sleep(45); //let the lazy robot work

        //find matching unique digits
        $date = date('Y-m-d');

        $expectedTransfer = $getData->nominal + $getData->unique_digit;

        $mutationCheck = $client->request('GET', 'https://app.moota.co/api/v2/mutation', [
            'headers' => $headers,
            'query' => [
                'type' => 'CR',
                'bank' => $bankID,
                'amount' => $expectedTransfer,
                'date' => $date,
            ]
        ]);
        $mutationCheckArray = json_decode($mutationCheck->getBody()->getContents(), true);
        if ($mutationCheckArray['total'] == 0) {
            dd('TopUpeIDRjob stopped: mutation not found! topup_id:' . $this->topup_id . ' user_id: ' . $this->user_id);
        }
        foreach ($mutationCheckArray as $mutationData) {
            $mutationCreatedAt = $mutationData['created_at'];
            $mutationNote = $mutationData['note'];
            $mutationID = $mutationData['mutation_id'];
            $mutationAmount = $mutationData['amount'];

            //start checking
            if (strtotime($mutationCreatedAt) > strtotime($getData->created_at)) {
                if ($mutationNote != 'PAID') {
                    if ($mutationAmount == $expectedTransfer) {

                        $to = $getData->tron;
                        $amount = $getData->nominal * 100;
                        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
                        $tokenID = '1002652';
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
                                'reason' => $response['txid'],
                                'tuntas_at' => date('Y-m-d H:i:s'),
                                'submit_by' => $getData->user_id,
                                'submit_at' => date('Y-m-d H:i:s'),
                            );
                            $modelBonus->getUpdateTopUp('id', $getData->id, $dataUpdate);

                            $paidNote = ['note' => 'PAID'];
                            $client->request('POST', 'https://app.moota.co/api/v2/mutation/' . $mutationID . '/note', [
                                'headers' => $headers,
                                'json' => $paidNote
                            ]);
                            $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;
                            $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                                'query' => [
                                    'chat_id' => '365874331',
                                    'text' => 'Top-up eIDR Berhasil, username ' . $getData->user_code . ' nominal:' . number_format($mutationAmount) . '. Sisa saldo eIDRhot: ' . number_format($eIDRbalance),
                                    'parse_mode' => 'markdown'
                                ]
                            ]);
                            Log::info('CheckTopUpeIDR stopped: eIDR Top-up Success');
                            return;
                        } else {
                            $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                                'query' => [
                                    'chat_id' => '365874331',
                                    'text' => 'Transfer eIDR untuk Top-up eIDR GAGAL, username ' . $getData->user_code . ' nominal:' . $mutationAmount,
                                    'parse_mode' => 'markdown'
                                ]
                            ]);
                            Log::info('CheckTopUpeIDR stopped: eIDR Transfer Failed');
                        }
                    } else {
                        Log::info('Top-up eIDR from user ' . $getData->user_code . ' ' . $expectedTransfer . ' FAILED. Error: mutation amount does not match!');
                    }
                } else {
                    Log::info('Top-up eIDR from user ' . $getData->user_code . ' ' . $expectedTransfer . ' FAILED. Error: mutation noted as PAID!');
                }
            } else {
                Log::info('Top-up eIDR from user ' . $getData->user_code . ' ' . $expectedTransfer . ' FAILED. Error: mutation date expired!');
            }
        }
    }
}
