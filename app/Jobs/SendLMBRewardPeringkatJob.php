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

use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Exception\TronException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class SendLMBRewardPeringkatJob implements ShouldQueue
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
        $getData = $modelBonus->getClaimRewardLMBbyId($this->reward_id);
        if ($getData == null) {
            dd('SendLMBRewardPeringkatjob stopped, data not found!');
        }

        $rewardType = 'Silver III';
        $reward = 100;
        if ($getData->reward_id == 2) {
            $rewardType = 'Silver II';
            $reward = 200;
        }
        if ($getData->reward_id == 3) {
            $rewardType = 'Silver I';
            $reward = 500;
        }
        if ($getData->reward_id == 4) {
            $rewardType = 'Gold III';
            $reward = 2000;
        }
        if ($getData->reward_id > 4) {
            $client->request('GET', 'https://api.telegram.org/bot' . $tgAk . '/sendMessage', [
                'query' => [
                    'chat_id' => '365874331',
                    'text' => $getData->user_code . ' claim reward peringkat no. ' . $getData->reward_id,
                    'parse_mode' => 'markdown',
                    'disable_web_page_preview' => 'true'
                ]
            ]);
            return;
        }

        $to = $getData->tron;
        $amount = $reward * 1000000;

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


        if ($response['result'] == true) {
            $txHash = $response['txid'];
            //log to app history
            $dataUpdate = array(
                'status' => 1,
                'transfer_at' => date('Y-m-d H:i:s'),
                'reason' => $txHash,
                'submit_by' => 1,
                'submit_at' => date('Y-m-d H:i:s'),
            );
            $modelBonus->getUpdateClaimReward('id', $getData->id, $dataUpdate);

            $shortenHash = substr($txHash, 0, 5) . '...' . substr($txHash, -5);

            $tgMessage = '
            Selamat!
*' . $getData->user_code . '* baru saja Claim ' . $reward . ' LMB dari pencapaian ' . $rewardType . '
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
            dd('SendLMBRewardJualBelijob FAILED, Tron Transfer FAILED. (' . $getData->user_code . ' claim: ' . $reward . ')');
        }
    }
}
