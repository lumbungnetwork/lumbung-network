<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Bonus;
use Illuminate\Support\Facades\DB;
use App\Model\Member\EidrBalance;

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

    public function middleware()
    {
        return [(new WithoutOverlapping($this->div_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelBonus = new Bonus;
<<<<<<< HEAD
        $controller = new Controller;
        $tron = $controller->getTron();
        $tron->setPrivateKey(Config::get('services.telegram.test'));
        $user = User::where('id', $this->user_id)->select('id', 'tron', 'username')->first();

=======
>>>>>>> lumbung3
        $claim = DB::table('users_dividend')->select('hash', 'amount')->where('id', $this->div_id)->first();

        // check before execute
        if ($claim->hash == null) {

            // create EidrBalance
            $balance = new EidrBalance;
            $balance->user_id = $this->user_id;
            $balance->amount = $claim->amount;
            $balance->type = 1;
            $balance->source = 1;
            $balance->note = "Claim Dividend from LMB Stake";
            $balance->save();


            // update claim record with hash
            $modelBonus->updateUserDividend('id', $this->div_id, [
                'hash' => "Claimed to Internal eIDR Balance"
            ]);

<<<<<<< HEAD
            //fail check
            sleep(10);
            try {
                $response = $tron->getTransaction($txHash);
            } catch (TronException $e) {
                Telegram::sendMessage([
                    'chat_id' => Config::get('services.telegram.overlord'),
                    'text' => 'UserClaimDividend Fail, UserID: ' . $this->user_id . ' div_id: ' . $this->div_id,
                    'parse_mode' => 'markdown'
                ]);
            }

            // record to eIDR log
            DB::table('eidr_logs')->insert([
                'amount' => $claim->amount,
                'from' => $from,
                'to' => $to,
                'hash' => $txHash,
                'type' => 2,
                'detail' => 'Claim Staking LMB Dividend by: ' . $user->username,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Check remaining balance and rebalance if needed
            $eIDRbalance = $tron->getTokenBalance($tokenID, $from, $fromTron = false) / 100;

            if ($eIDRbalance < 2500000) {
                eIDRrebalanceJob::dispatch()->onQueue('tron');
            }


=======
>>>>>>> lumbung3
            return;
        } else {
            $this->delete();
        }
    }
}
