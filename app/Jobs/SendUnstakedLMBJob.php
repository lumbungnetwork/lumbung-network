<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use DB;
use IEXBase\TronAPI\Exception\TronException;
use GuzzleHttp\Client;

class SendUnstakedLMBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    protected $staking_id;
    protected $amount;
    public function __construct($user_id, $staking_id, $amount)
    {
        $this->user_id = $user_id;
        $this->staking_id = $staking_id;
        $this->amount = $amount;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->staking_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get minimum timestamp for failcheck
        $minTimestamp = time() * 1000;

        // Double Check
        $unstakeData = DB::table('unstake')->where('staking_id', $this->staking_id)->first();
        if ($unstakeData->status) {
            $this->delete();
        }

        $getUserTronAddress = User::where('id', $this->user_id)->select('tron')->first();
        $sendAmount = $this->amount * 1000000;

        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.tron.lmb_staking'));

        $to = $getUserTronAddress->tron;
        $from = config('services.tron.address.lmb_staking');
        $tokenID = config('services.tron.token_id.lmb');

        //send LMB
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $sendAmount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            goto Cleanup;
        }

        if (!isset($response['result'])) {
            goto Cleanup;
        }


        if ($response['result'] == true) {
            $txHash = $response['txid'];
            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                Cleanup:
                sleep(10);
                // Trongrid API to check recent transactions
                $url = 'https://api.trongrid.io/v1/accounts/' . $from . '/transactions?only_confirmed=true&only_from=true&limit=5&min_timestamp=' . $minTimestamp;

                // use Guzzle Client
                $client = new Client;
                $res = $client->get($url, [
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json']
                ]);

                $response = json_decode($res->getBody(), true);
                $done = false;
                foreach ($response['data'] as $data) {
                    if ($data['raw_data']['contract'][0]['parameter']['value']['to_address'] == $tron->toHex($to) && $data['raw_data']['contract'][0]['parameter']['value']['amount'] == $sendAmount) {
                        $done = true;
                    }
                }
                // If transaction not found, this job fails
                if (!$done) {
                    Telegram::sendMessage([
                        'chat_id' => config('services.telegram.supervisor'),
                        'text' => 'SendUnstakedLMB Fail on FAILCHECK, UserID: ' . $this->user_id . ',tron addr: ' . $to . ', staking id: ' . $this->staking_id
                    ]);
                    $this->fail();
                }

                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => 'SendUnstakedLMB failcheck jump anomaly, UserID: ' . $this->user_id . ',tron addr: ' . $to . ', staking id: ' . $this->staking_id
                ]);
            }

            //log to app history
            DB::table('unstake')->where('id', $unstakeData->id)->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            DB::table('staking')->where('id', $this->staking_id)->update([
                'hash' => $txHash,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return;
        }
    }
}
