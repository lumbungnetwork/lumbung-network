<?php

namespace App\Jobs\Member;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member\LMBreward;
use App\User;
use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use IEXBase\TronAPI\Exception\TronException;
use App\Notifications\LMBNotification;

class SendLMBRewardMarketplaceJob implements ShouldQueue
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

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->reward_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the reward data
        $reward = LMBreward::find($this->reward_id);

        // Check the hash, if exist or if Type != 0 delete this job,
        if ($reward->hash !== null || $reward->type !== 0) {
            $this->delete();
        }

        // Get user
        $user = User::find($reward->user_id);

        // Prepare TRON
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.lmb_distributor'));

        $to = $user->tron;
        $amount = $reward->amount * 1000000;

        $from = 'TSqTD8gsnGBKxgqFJkGLAupWwF3JbHGjz8';
        $tokenID = '1002640';

        //send LMB
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            $response = Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => 'SendLMBRewardMarketplace Fail, UserID: ' . $user->id . ' reward_id: ' . $this->reward_id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => 'SendLMBRewardMarketplace Fail, UserID: ' . $user->id . ' reward_id: ' . $this->reward_id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }

        if ($response['result'] == true) {

            $txHash = $response['txid'];

            // Update Hash on LMBreward model record
            $reward->hash = $txHash;
            $reward->save();

            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.overlord'),
                    'text' => 'SendLMBRewardMarketplace Fail on FAILCHECK, UserID: ' . $user->id . ' reward_id: ' . $this->reward_id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            // Send Notification to User's Telegram and Telegram Channel

            $rewardType = 'Reward Jual/Beli';
            $shortenHash = substr($txHash, 0, 5) . '...' . substr($txHash, -5);

            $notification = [
                'amount' => $reward->amount,
                'type' => $rewardType,
                'hash' => $txHash
            ];

            if ($user->chat_id != null) {
                $user->notify(new LMBNotification($notification));
            }

            $text = '**' . $user->username . '** baru saja Claim ' . $reward->amount . ' LMB dari ' . $rewardType . chr(10);
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
                    'chat_id' => config('services.telegram.overlord'),
                    'text' => $LMBbalance . ' LMB left in Distribution account',
                    'parse_mode' => 'markdown'
                ]);
            }

            return;
        }
    }
}
