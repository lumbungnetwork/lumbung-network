<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Sales;
use App\Model\Bonus;
use App\User;
use App\Jobs\SendLMBRewardJualBeliJob;

class KBBMasterClaimShoppingRewardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get user
        $user = User::find($this->user_id);

        //get last month spending
        $modelSales = new Sales;
        $modelBonus = new Bonus;
        $spending = $modelSales->getSingleMemberPreviousMonthStockistSpending($this->user_id);

        if ($spending == null) {
            $this->delete();
        } else {

            $rewardFactor = 10;
            if ($user->pin_activate == 2) {
                $rewardFactor = 5;
            }
            if ($user->pin_activate == 3) {
                $rewardFactor = 2.5;
            }
            if ($user->pin_activate >= 4) {
                $rewardFactor = 1;
            }
            $reward = floor(($spending / 10000) / 10) * $rewardFactor;

            if ($reward > 50) {
                $reward = 50;
            }

            $month = date('m', strtotime('last month'));
            $year = date('Y', strtotime('last month'));

            $dataInsert = array(
                'user_id' => $this->user_id,
                'reward' => $reward,
                'month' => $month,
                'year' => $year,
                'belanja_date' => $year . '-' . $month . '-01',
                'total_belanja' => $spending
            );
            $getRewardId = $modelBonus->getInsertBelanjaReward($dataInsert);
            SendLMBRewardJualBeliJob::dispatch($getRewardId->lastID)->onQueue('tron');
        }
    }
}
