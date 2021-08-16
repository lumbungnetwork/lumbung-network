<?php

namespace App\Jobs;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IEXBase\TronAPI\Exception\TronException;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Notifications\LMBNotification;
use GuzzleHttp\Client;

class ClaimNetworkRewardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $claimRewardID;
    public function __construct($claimRewardID)
    {
        $this->claimRewardID = $claimRewardID;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->claimRewardID))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get claim_reward data and double check
        $data = DB::table('claim_reward')->where('id', $this->claimRewardID)->first();
        if ($data->status) {
            $this->delete();
        }

        // Get minimum timestamp for failcheck
        $minTimestamp = time() * 1000;

        // Get reward model
        $reward = DB::table('bonus_reward2')->where('id', $data->reward_id)->first();

        // Get User
        $user = User::find($data->user_id);

        // Prepare TRON
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.lmb_distributor'));

        $to = $user->tron;
        $amount = $reward->reward_detail * 1000000;

        $from = config('services.tron.address.lmb_distributor');
        $tokenID = config('services.tron.token_id.lmb');

        //send LMB
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
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

            // Update Hash on claim_reward record
            DB::table('claim_reward')->where('id', $this->claimRewardID)->update([
                'status' => 1,
                'reason' => $txHash,
                'transfer_at' => date('Y-m-d H:i:s')
            ]);

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
                    if ($data['raw_data']['contract'][0]['parameter']['value']['to_address'] == $tron->toHex($to) && $data['raw_data']['contract'][0]['parameter']['value']['amount'] == $amount) {
                        $done = true;
                    }
                }
                // If transaction not found, this job fails
                if (!$done) {
                    Telegram::sendMessage([
                        'chat_id' => config('services.telegram.supervisor'),
                        'text' => 'ClaimNetworkRewardJob Fail on FAILCHECK, UserID: ' . $user->id . ',tron addr: ' . $to . ', reward id: ' . $this->claimRewardID
                    ]);
                    $this->fail();
                }
            }

            // Send Notification to User's Telegram and Telegram Channel
            $rewardType = 'Reward Silver III';
            switch ($data->reward_id) {
                case 2:
                    $rewardType = 'Reward Silver II';
                case 3:
                    $rewardType = 'Reward Silver I';
                case 4:
                    $rewardType = 'Reward Gold III';
            }
            $shortenHash = substr($txHash, 0, 5) . '...' . substr($txHash, -5);

            $notification = [
                'amount' => $reward->reward_detail,
                'type' => $rewardType,
                'hash' => $txHash
            ];

            if ($user->chat_id != null) {
                $user->notify(new LMBNotification($notification));
            }

            $text = '**' . $user->username . '** baru saja Claim ' . $reward->reward_detail . ' LMB dari ' . $rewardType . chr(10);
            $text .= '*Hash: *[' . $shortenHash . '](https://tronscan.org/#/transaction/' . $txHash . ')';

            Telegram::sendMessage([
                'chat_id' => '@lumbungchannel',
                'text' => $text,
                'parse_mode' => 'markdown',
                'disable_web_page_preview' => 'true'
            ]);

            $LMBbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 1000000;
            if ($LMBbalance < 2000) {

                //Notify when LMBbalance low

                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.supervisor'),
                    'text' => $LMBbalance . ' LMB left in Distribution account',
                    'parse_mode' => 'markdown'
                ]);
            }
        }

        return;
    }
}
