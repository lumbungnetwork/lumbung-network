<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Sales;

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
        $modelSales = new Sales;
        $modelMember = new Member;
        $modelBonus = new Bonus;

        $getBonusMonth = (object) array(
            'startDay' => date("Y-m-01"),
            'endDay' => date("Y-m-t"),
        );

        $getPreviousMonth = (object) array(
            'startDay' => date("Y-m-01", strtotime("first day of previous month")),
            'endDay' => date("Y-m-t", strtotime("last day of previous month"))
        );

        $min_belanja = 100000;
        $bonus_royalti = 500;
        $maxGetBonus = 4;
        $getLevelSp = $modelMember->getLevelSponsoring($this->user_id);

        if ($getLevelSp->id_lvl1 != null) {
            $getCekBelanja1 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl1, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja1 == true) {
                $cekMax1 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl1, 1, $maxGetBonus, $getBonusMonth);
                if ($cekMax1 == true) {
                    $dataInsertBonusLvl1 = array(
                        'user_id' => $getLevelSp->id_lvl1,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 1,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl1);
                }
            }
        }

        if ($getLevelSp->id_lvl2 != null) {
            $getCekBelanja2 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl2, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja2 == true) {
                $cekMax2 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl2, 2, $maxGetBonus, $getBonusMonth);
                if ($cekMax2 == true) {
                    $dataInsertBonusLvl2 = array(
                        'user_id' => $getLevelSp->id_lvl2,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 2,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl2);
                }
            }
        }

        if ($getLevelSp->id_lvl3 != null) {
            $getCekBelanja3 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl3, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja3 == true) {
                $cekMax3 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl3, 3, $maxGetBonus, $getBonusMonth);
                if ($cekMax3 == true) {
                    $dataInsertBonusLvl3 = array(
                        'user_id' => $getLevelSp->id_lvl3,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 3,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl3);
                }
            }
        }

        if ($getLevelSp->id_lvl4 != null) {
            $getCekBelanja4 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl4, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja4 == true) {
                $cekMax4 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl4, 4, $maxGetBonus, $getBonusMonth);
                if ($cekMax4 == true) {
                    $dataInsertBonusLvl4 = array(
                        'user_id' => $getLevelSp->id_lvl4,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 4,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl4);
                }
            }
        }

        if ($getLevelSp->id_lvl5 != null) {
            $getCekBelanja5 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl5, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja5 == true) {
                $cekMax5 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl5, 5, $maxGetBonus, $getBonusMonth);
                if ($cekMax5 == true) {
                    $dataInsertBonusLvl5 = array(
                        'user_id' => $getLevelSp->id_lvl5,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 5,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl5);
                }
            }
        }

        if ($getLevelSp->id_lvl6 != null) {
            $getCekBelanja6 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl6, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja6 == true) {
                $cekMax6 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl6, 6, $maxGetBonus, $getBonusMonth);
                if ($cekMax6 == true) {
                    $dataInsertBonusLvl6 = array(
                        'user_id' => $getLevelSp->id_lvl6,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 6,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl6);
                }
            }
        }

        if ($getLevelSp->id_lvl7 != null) {
            $getCekBelanja7 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl7, $getPreviousMonth, $min_belanja);
            if ($getCekBelanja7 == true) {
                $cekMax7 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl7, 7, $maxGetBonus, $getBonusMonth);
                if ($cekMax7 == true) {
                    $dataInsertBonusLvl7 = array(
                        'user_id' => $getLevelSp->id_lvl7,
                        'from_user_id' => $this->user_id,
                        'type' => 3,
                        'bonus_price' => $bonus_royalti,
                        'bonus_date' => date('Y-m-01'),
                        'poin_type' => 1,
                        'level_id' => 7,
                    );
                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl7);
                }
            }
        }

        return;
    }
}
