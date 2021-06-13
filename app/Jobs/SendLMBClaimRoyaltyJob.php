<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\User;
use App\Model\Bonus;
use App\Model\Member\BonusRoyalty;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Notifications\LMBNotification;

class SendLMBClaimRoyaltyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    protected $bonus_id;
    public function __construct($user_id, $bonus_id)
    {
        $this->user_id = $user_id;
        $this->bonus_id = $bonus_id;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->bonus_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $controller = new Controller;
        $modelBonus = new Bonus;
        $bonus = BonusRoyalty::find($this->bonus_id);

        // Get User's
        $user = User::select('id', 'tron', 'username')->where('id', $this->user_id)->first();

        // Verify bonus balance
        $bonusRoyalty = $modelBonus->getTotalBonusRoyalti($user->id);
        if ($bonusRoyalty->net > 0) {
            $this->delete();
        }

        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.telegram.rebuild'));

        $to = $user->tron;
        $amount = $bonus->amount * 1000000;

        $from = 'TSqTD8gsnGBKxgqFJkGLAupWwF3JbHGjz8';
        $tokenID = '1002640';

        //send eIDR
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            $response = Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'SendLMBClaimRoyalty Fail, UserID: ' . $user->id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }

        if (!isset($response['result'])) {
            $response = Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => 'SendLMBClaimRoyalty Fail, UserID: ' . $user->id,
                'parse_mode' => 'markdown'
            ]);
            return;
        }


        if ($response['result'] == true) {
            $txHash = $response['txid'];
            // Update the Bonus model
            $bonus->hash = $txHash;
            $bonus->save();

            $shortenHash = substr($txHash, 0, 5) . '...' . substr($txHash, -5);

            $notification = [
                'amount' => $bonusRoyalty->net,
                'type' => 'Claim Bonus Royalty',
                'hash' => $response['txid']
            ];

            if ($user->chat_id != null) {
                $user->notify(new LMBNotification($notification));
            }

            $tgMessage = '
            Selamat!
*' . $user->username . '* baru saja Claim ' . $bonus->amount . ' LMB dari Bonus Royalty 
    *Hash: *[' . $shortenHash . '](https://tronscan.org/#/transaction/' . $txHash . ')
            ';

            Telegram::sendMessage([
                'chat_id' => '@lumbungchannel',
                'text' => $tgMessage,
                'parse_mode' => 'markdown',
                'disable_web_page_preview' => 'true'
            ]);

            $LMBbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 1000000;
            if ($LMBbalance < 2000) {
                //Notify admin

                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => $LMBbalance . ' LMB left in Distribution account',
                    'parse_mode' => 'markdown'
                ]);
            }

            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'SendLMBClaimRoyalty Fail, UserID: ' . $user->id,
                    'parse_mode' => 'markdown'
                ]);
                return;
            }

            return;
        }
    }
}
