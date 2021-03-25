<?php

namespace App\Jobs;

use App\Http\Middleware\Cors;
use App\Model\Bonus;
use IEXBase\TronAPI\Provider\HttpProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\User;
use App\Notifications\LMBNotification;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendLMBRewardJualBeliJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $reward_id;

    public function __construct($reward_id)
    {
        $this->reward_id = $reward_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Prepare Telegram
        $fuse = Config::get('services.telegram.rebuild');
        $tgAk = Config::get('services.telegram.lmb');
        $client = new Client;

        //prepare Tron
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey($fuse);

        //Get Claim Reward Data
        $modelBonus = new Bonus;
        $getData = $modelBonus->getJobDetailBelanjaReward($this->reward_id);
        if ($getData == null) {
            $this->delete();
        }

        $user = User::find($getData->user_id);

        $rewardType = 'Reward Belanja di Stockist';
        if ($getData->type == 2) {
            $rewardType = 'Reward Penjualan Stockist';
        }
        if ($getData->type == 3) {
            $rewardType = 'Reward Belanja di Vendor';
        }
        if ($getData->type == 4) {
            $rewardType = 'Reward Penjualan Vendor';
        }

        $to = $getData->tron;
        $amount = $getData->reward * 1000000;

        $from = 'TSqTD8gsnGBKxgqFJkGLAupWwF3JbHGjz8';
        $tokenID = '1002640';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            die($e->getMessage());
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'SendLMBRewardJualBeli Fail, UserID: ' . $user->id . ' reward_id: ' . $this->reward_id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }


        if ($response['result'] == true) {

            $txHash = $response['txid'];
            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'SendLMBRewardJualBeli Fail, UserID: ' . $user->id . ' reward_id: ' . $this->reward_id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }


            //log to app history
            $dataUpdate = array(
                'status' => 1,
                'tuntas_at' => date('Y-m-d H:i:s'),
                'reason' => $txHash,
                'submit_by' => 1,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateBelanjaReward('id', $getData->id, $dataUpdate);

            $shortenHash = substr($txHash, 0, 5) . '...' . substr($txHash, -5);

            $notification = [
                'amount' => $getData->reward,
                'type' => $rewardType . ' ' . $getData->monthly,
                'hash' => $response['txid']
            ];

            if ($user->chat_id != null) {
                $user->notify(new LMBNotification($notification));
            }

            $tgMessage = '
*' . $getData->user_code . '* baru saja Claim ' . $getData->reward . ' LMB dari ' . $rewardType . '
    _Periode: ' . $getData->monthly . '_
    *Hash: *[' . $shortenHash . '](https://tronscan.org/#/transaction/' . $txHash . ')
            ';
            $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                'query' => [
                    'chat_id' => '@lumbungchannel',
                    'text' => $tgMessage,
                    'parse_mode' => 'markdown',
                    'disable_web_page_preview' => 'true'
                ]
            ]);

            $LMBbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 1000000;
            if ($LMBbalance < 2000) {
                //Notify admin
                $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                    'query' => [
                        'chat_id' => '365874331',
                        'text' => $LMBbalance . 'LMB left in Distribution account',
                        'parse_mode' => 'markdown'
                    ]
                ]);
            }

            return;
        } else {
            $this->fail();
        }
    }
}
