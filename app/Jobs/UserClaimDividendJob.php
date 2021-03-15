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
use App\User;

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

        $userDividend = $modelBonus->getUserDividend($user->id);

        if ($userDividend->net >= 1000) {
            $to = $user->tron;
            $amount = $userDividend->net * 100;
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
                $this->fail();
            }

            $txHash = $response['txid'];
            //fail check
            sleep(10);
            try {
                $tron->getTransaction($txHash);
            } catch (TronException $e) {
                $this->fail();
            }

            $modelBonus->updateUserDividend('id', $this->div_id, [
                'hash' => $txHash
            ]);

            return;
        }
    }
}
