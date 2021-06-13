<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use App\Model\Member\MasterSales;
use App\Model\Member\BonusRoyalty;

class ProcessRoyaltiBonusJob implements ShouldQueue
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
        $modelUser = new User;
        $modelMasterSales = new MasterSales;
        $modelBonusRoyalty = new BonusRoyalty;

        $threshold = 100000; // Spending threshold to be eligible for this bonus
        $amount = 1; // 1 LMB per node
        // Get all 7 levels sponsor above this user
        $sponsors = $modelUser->get7LevelsSponsors($this->user_id);
        foreach ($sponsors as $sponsor) {
            if ($sponsor->id) {
                // Check this sponsor spending
                $spending = $modelMasterSales->getMemberSpending(1, $sponsor->id, date('m', strtotime('last month')), date('Y', strtotime('last month')));
                if ($spending->total > $threshold) {
                    // Check this sponsor Matrix Limit
                    $check = $modelBonusRoyalty->CheckBonusRoyaltyMatrixLimit($sponsor->id, $sponsor->level);
                    if ($check && $sponsor->user_type == 10) {
                        BonusRoyalty::create([
                            'user_id' => $sponsor->id,
                            'from_user_id' => $this->user_id,
                            'amount' => $amount,
                            'bonus_date' => date('Y-m-01'),
                            'level_id' => $sponsor->level
                        ]);
                    }
                }
            }
        }


        return;
    }
}
