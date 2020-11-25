<?php

namespace App\Jobs;

use App\Model\Transferwd;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;

class KonversieIDRjob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id; //id of conversion request

    public function __construct($id)
    {

        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //prepare Tron
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        $fuse = Config::get('services.telegram.test');


        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer, $signServer = null, $explorer = null, $fuse);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        //get WD data
        $modelWD = new Transferwd;
        $getData = $modelWD->getIDKonversiWDeIDR($this->id);
        if ($getData == null) {
            dd('KonversieIDRjob stopped, no data');
        }

        $status = $getData->status;
        $is_tron = $getData->is_tron;
        $reason = $getData->reason;
        $to = $getData->tron;
        $amount = $getData->wd_total * 100;

        $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
        $tokenID = '1002652';

        //start checking
        if ($is_tron == 1) {
            if ($status == 0) {
                if ($reason == null) {
                    //send eIDR
                    try {
                        $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
                        $signedTransaction = $tron->signTransaction($transaction);
                        $response = $tron->sendRawTransaction($signedTransaction);
                    } catch (TronException $e) {
                        die($e->getMessage());
                    }

                    //log to app history
                    if ($response['result'] == true) {
                        $dataUpdate = array(
                            'status' => 1,
                            'transfer_at' => date('Y-m-d H:i:s'),
                            'submit_by' => 1,
                            'reason' => $response['txid'],
                            'submit_at' => date('Y-m-d H:i:s'),
                        );
                        $modelWD->getUpdateWD('id', $this->id, $dataUpdate);
                    }
                }
            }
        }
    }
}
