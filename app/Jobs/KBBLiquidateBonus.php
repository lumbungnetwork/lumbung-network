<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Controller;
use App\Model\Transferwd;

class KBBLiquidateBonus implements ShouldQueue
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
        //build user_id object
        $dataUser = (object) array(
            'id' => $this->user_id
        );

        //get available bonuses
        $controller = new Controller;
        $modelWD = new Transferwd;
        $availableBonuses = $controller->getMemberAvailableBonus($this->user_id);
        $daily = $availableBonuses->daily_bonus;
        $royalti = $availableBonuses->royalti_bonus;

        if ($daily > 3000) {
            $getCode = $modelWD->getCodeWDeIDR($dataUser);
            $dataInsert = array(
                'user_id' => $dataUser->id,
                'user_bank' => 0,
                'type' => 5,
                'wd_code' => $getCode,
                'wd_total' => $daily - 3000,
                'wd_date' => date('Y-m-d'),
                'admin_fee' => 3000,
                'is_tron' => 1
            );
            $result = $modelWD->getInsertWD($dataInsert);
            KonversieIDRjob::dispatch($result->lastID)->onQueue('tron');
        }

        if ($royalti > 3000) {
            $getCode = $modelWD->getCodeWDeIDR($dataUser);
            $dataInsert = array(
                'user_id' => $dataUser->id,
                'user_bank' => 1,
                'is_tron' => 1,
                'wd_code' => $getCode,
                'type' => 3,
                'wd_total' => $royalti - 3000,
                'wd_date' => date('Y-m-d'),
                'admin_fee' => 3000
            );
            $result = $modelWD->getInsertWD($dataInsert);
            WDRoyaltiByeIDRjob::dispatch($result->lastID)->onQueue('tron');
        }

        return;
    }
}
