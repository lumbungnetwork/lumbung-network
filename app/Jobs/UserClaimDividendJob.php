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

            return;
        } else {
            $this->delete();
        }
    }
}
