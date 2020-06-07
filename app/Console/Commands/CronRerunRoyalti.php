<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Sales;


class CronRerunRoyalti extends Command {

    protected $signature = 'ulang_bonus_royalti {ke}';
    protected $description = 'Cron Ulang Bulanan Bonus Royalti dari awal';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 1500);
        $startKe = $this->argument('ke');
        if($startKe < 0){
            dd('stop here 0');
        }
        if($startKe > 7){
            dd('stop here 7');
        }
        $startMonth = '2019-11-01';
        if($startKe == 0){
             DB::table('bonus_member')
                    ->where('type', '=', 3)
                    ->where('poin_type', '=', 1)
                    ->whereDate('bonus_date', '>', $startMonth)
                    ->delete();
             dd('done remove bonus royalti all');
        }
        $bonus_date = date('Y-m-01', strtotime('+'.$startKe .' months', strtotime($startMonth)));
        $bonus_date_end = date('Y-m-t', strtotime('+'.$startKe .' months', strtotime($startMonth)));
        $startDayMonth = date('Y-m-01', strtotime('+'.($startKe - 1) .' months', strtotime($startMonth)));
        $endDayMonth = date('Y-m-t', strtotime('+'.($startKe - 1).' months', strtotime($startMonth)));
         $textMonth = date('F Y', strtotime('+'.($startKe - 1).' months', strtotime($startMonth)));
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBonus = New Bonus;
        $getPreviousMonth = (object) array(
            'startDay' => $startDayMonth,
            'endDay' => $endDayMonth,
            'textMonth' => $textMonth
        );
        $getBonusMonth = (object) array(
            'startDay' => $bonus_date,
            'endDay' => $bonus_date_end,
        );
        $getData = $modelSales->getCronrSalesHistoryMonth($getPreviousMonth);
        $bonus_royalti = 1000/2; //500
        $maxGetBonus = 4;
        $min_belanja = 100000;
        if($getData != null){
            foreach($getData as $row){
                if($row->month_sale_price > $min_belanja){
                    $getLevelSp = $modelMember->getLevelSponsoring($row->id);
                    if($getLevelSp != null){
                        if($getLevelSp->id_lvl1 != null){
                            $getCekBelanja1 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl1, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja1 == true){
                                $cekMax1 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl1, 1, $maxGetBonus, $getBonusMonth);
                                if($cekMax1 == true){
                                    $dataInsertBonusLvl1 = array(
                                        'user_id' => $getLevelSp->id_lvl1,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 1,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl1);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl2 != null){
                            $getCekBelanja2 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl2, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja2 == true){
                                $cekMax2 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl2, 2, $maxGetBonus, $getBonusMonth);
                                if($cekMax2 == true){
                                    $dataInsertBonusLvl2 = array(
                                        'user_id' => $getLevelSp->id_lvl2,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 2,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl2);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl3 != null){
                            $getCekBelanja3 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl3, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja3 == true){
                                $cekMax3 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl3, 3, $maxGetBonus, $getBonusMonth);
                                if($cekMax3 == true){
                                    $dataInsertBonusLvl3 = array(
                                        'user_id' => $getLevelSp->id_lvl3,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 3,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl3);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl4 != null){
                            $getCekBelanja4 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl4, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja4 == true){
                                $cekMax4 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl4, 4, $maxGetBonus, $getBonusMonth);
                                if($cekMax4 == true){
                                    $dataInsertBonusLvl4 = array(
                                        'user_id' => $getLevelSp->id_lvl4,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 4,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl4);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl5 != null){
                            $getCekBelanja5 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl5, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja5 == true){
                                $cekMax5 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl5, 5, $maxGetBonus, $getBonusMonth);
                                if($cekMax5 == true){
                                    $dataInsertBonusLvl5 = array(
                                        'user_id' => $getLevelSp->id_lvl5,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 5,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl5);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl6 != null){
                            $getCekBelanja6 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl6, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja6 == true){
                                $cekMax6 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl6, 6, $maxGetBonus, $getBonusMonth);
                                if($cekMax6 == true){
                                    $dataInsertBonusLvl6 = array(
                                        'user_id' => $getLevelSp->id_lvl6,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 6,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl6);
                                }
                            }
                        }

                        if($getLevelSp->id_lvl7 != null){
                            $getCekBelanja7 = $modelSales->getCekSalesHistoryMemberMonth($getLevelSp->id_lvl7, $getPreviousMonth, $min_belanja);
                            if($getCekBelanja7 == true){
                                $cekMax7 = $modelBonus->getCekNewBonusRoyaltiMax($getLevelSp->id_lvl7, 7, $maxGetBonus, $getBonusMonth);
                                if($cekMax7 == true){
                                    $dataInsertBonusLvl7 = array(
                                        'user_id' => $getLevelSp->id_lvl7,
                                        'from_user_id' => $row->id,
                                        'type' => 3,
                                        'bonus_price' => $bonus_royalti,
                                        'bonus_date' => $bonus_date,
                                        'poin_type' => 1,
                                        'level_id' => 7,
                                    );
                                    $modelBonus->getInsertBonusMember($dataInsertBonusLvl7);
                                }
                            }
                        }
                    }
                    
                    
                }
            }
            dd('Done Bonus Royalti Bulan '.$getPreviousMonth->textMonth);
        }
    }
    
    
    
    
}
