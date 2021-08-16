<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\User;
use App\Model\Member\BonusRoyalty;
use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Notifications\LMBNotification;
use IEXBase\TronAPI\Exception\TronException;

class SendLMBClaimRoyaltyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $bonus_id;
    public function __construct($bonus_id)
    {
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
        $modelBonusRoyalty = new BonusRoyalty;
        $bonus = BonusRoyalty::find($this->bonus_id);

        // Get User's data
        $user = User::select('id', 'tron', 'username')->where('id', $bonus->user_id)->first();

        // Verify bonus balance
        $bonusRoyalty = $modelBonusRoyalty->getTotalBonusRoyalti($user->id);
        if ($bonusRoyalty->net > 0) {
            $this->delete();
        }

        $tron = $controller->getTron();
        $tron->setPrivateKey(config('services.tron.lmb_distributor'));

        $to = $user->tron;
        $amount = $bonus->amount * 1000000;

        $from = config('services.tron.address.lmb_distributor');
        $tokenID = config('services.tron.token_id.lmb');

        //send LMB
        try {
            $transaction = $tron->getTransactionBuilder()->sendToken($to, $amount, $tokenID, $from);
            $signedTransaction = $tron->signTransaction($transaction);
            $response = $tron->sendRawTransaction($signedTransaction);
        } catch (TronException $e) {
            Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => 'SendLMBClaimRoyalty Fail, ID: ' . $bonus->id . ' UserID: ' . $user->id . ' to: ' . $to . ', amount: ' . $bonus->amount
            ]);
            $this->fail();
        }

        if (!isset($response['result'])) {
            Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => 'SendLMBClaimRoyalty Fail, ID: ' . $bonus->id . ' UserID: ' . $user->id . ' to: ' . $to . ', amount: ' . $bonus->amount
            ]);
            $this->fail();
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

            // Send message to Telegram Channel
            $text = $user->username . ' baru saja Claim ' . $bonus->amount . ' LMB dari Bonus Royalty' . chr(10);
            $text .= 'Hash: [' . $shortenHash . '](https://tronscan.org/#/transaction/' . $txHash . ')';

            Telegram::sendMessage([
                'chat_id' => config('services.telegram.channel'),
                'text' => $text,
                'parse_mode' => 'markdown',
                'disable_web_page_preview' => 'true'
            ]);

            $LMBbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 1000000;
            if ($LMBbalance < 2000) {
                //Notify supervisor
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.overlord'),
                    'text' => $LMBbalance . ' LMB left in Distribution account',
                    'parse_mode' => 'markdown'
                ]);
            }

            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => config('services.telegram.overlord'),
                    'text' => 'SendLMBClaimRoyalty Fail on FAILCHECK, ID: ' . $bonus->id . ' UserID: ' . $user->id . ' to: ' . $to . ', amount: ' . $bonus->amount
                ]);
                $this->fail();
            }

            return;
        }
    }
}
