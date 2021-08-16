<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member\EidrBalance;
use App\Model\Member\UsersDividend;

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
        $claim = UsersDividend::find($this->div_id);

        // check before execute
        if ($claim->hash == null) {

            // create EidrBalance
            $balance = new EidrBalance;
            $balance->user_id = $this->user_id;
            $balance->amount = $claim->amount;
            $balance->type = 1;
            $balance->source = 1;
            $balance->note = "Claim Dividend from LMB Stake " . date('d M y');
            $balance->save();


            // update claim record with hash
            $claim->hash = "Claimed to Internal eIDR Balance " . date('d M y');
            $claim->save();

            return;
        } else {
            $this->delete();
        }
    }
}
