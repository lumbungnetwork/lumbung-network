<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Bonus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\User;
use Illuminate\Support\Facades\DB;

class UserClaimDividendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    protected $div_id;
    public function __construct($user_id, $div_id)
    {
        $this->user_id = $user_id;
        $this->div_id = $div_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelBonus = new Bonus;
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.telegram.test'));
        $user = User::where('id', $this->user_id)->select('id', 'tron')->first();

        $claim = DB::table('users_dividend')->select('hash', 'amount')->where('id', $this->div_id)->first();

        if ($claim->hash == null) {
            $to = $user->tron;
            $amount = $claim->amount * 100;
            $tokenID = '1002652';
            $from = 'TWJtGQHBS8PfZTXvWAYhQEMrx36eX2F9Pc';
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
                    'text' => 'UserClaimDividend Fail, UserID: ' . $this->user_id . ' div_id: ' . $this->div_id,
                    'parse_mode' => 'markdown'
                ]);
            }

            $txHash = $response['txid'];
            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $response = Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'UserClaimDividend Fail, UserID: ' . $this->user_id . ' div_id: ' . $this->div_id,
                    'parse_mode' => 'markdown'
                ]);
            }

            $modelBonus->updateUserDividend('id', $this->div_id, [
                'hash' => $txHash
            ]);

            return;
        }
    }
}
