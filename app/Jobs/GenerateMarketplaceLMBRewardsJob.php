<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member\MasterSales;
use App\Model\Member\LMBreward;

class GenerateMarketplaceLMBRewardsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user_id;
    protected $is_store;
    public function __construct($user_id, $is_store)
    {
        $this->user_id = $user_id;
        $this->is_store = $is_store;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->user_id))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $MasterSales = new MasterSales;

        // Get user's last month spending
        // @params: ($type => 1 == Buyer, 2 == Seller), using 1 to get Spending
        $spending = $MasterSales->getMemberSpending(1, $this->user_id, date('m', strtotime('last month')), date('Y', strtotime('last month')));
        $spent = $spending->total;

        // Require minimum 1000 IDR spent to be eligible for Spending Reward
        if ($spent >= 1000) {
            $multiplier = 1;
            // LMB Reward Rate (0.025 for Buyer, 0.01 for Seller)
            $rate = 0.025;
            // If Spending > 300000, bonus 100% LMB
            if ($spent > 300000) {
                $multiplier = 2;
            }

            // Calculate the base spending (per 1000)
            $base = floor($spent / 1000);
            $reward_amount = round($base * $rate * $multiplier, 2);
            // Max Reward (Buyer = 50, Seller = 200)
            if ($reward_amount > 50) {
                $reward_amount = 50;
            }

            // Create Reward record
            $reward = new LMBreward;
            $reward->user_id = $this->user_id;
            $reward->amount = $reward_amount;
            $reward->sales = $spent;
            $reward->type = 1;
            $reward->is_store = 0;
            $reward->date = date('m-Y', strtotime('last month'));
            $reward->save();
        }

        // If this user is store, count selling reward based on sales
        if ($this->is_store) {
            // get user's (Store) current month sales
            // @params: ($type => 1 == Buyer, 2 == Seller), using 2 to get Sales
            $selling = $MasterSales->getMemberSpending(2, $this->user_id, date('m', strtotime('last month')), date('Y', strtotime('last month')));
            $sold = $selling->total;

            if ($sold >= 1000) {
                // LMB Reward Rate (0.025 for Buyer, 0.01 for Seller)
                $rate = 0.01;

                // Calculate the base sales (per 1000)
                $base = floor($sold / 1000);
                $selling_reward_amount = round($base * $rate, 2);
                // Max Reward (Buyer = 50, Seller = 200)
                if ($selling_reward_amount > 200) {
                    $selling_reward_amount = 200;
                }

                // Create Reward record
                $reward = new LMBreward;
                $reward->user_id = $this->user_id;
                $reward->amount = $selling_reward_amount;
                $reward->sales = $sold;
                $reward->type = 1;
                $reward->is_store = 1;
                $reward->date = date('m-Y', strtotime('last month'));
                $reward->save();
            }
        }

        return;
    }
}
