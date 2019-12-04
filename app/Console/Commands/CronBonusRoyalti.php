<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Sales;


class CronBonusRoyalti extends Command {

    protected $signature = 'bonus_royalti';
    protected $description = 'Cron Bulanan Bonus Royalti (akumulasi belanja selama 1 bulan)';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 1500);
        $modelSales = New Sales;
        $modelMember = New Member;
        $modelBonus = New Bonus;
        $getPreviousMonth = (object) array(
            'startDay' => date("Y-m-01", strtotime("first day of previous month")),
            'endDay' => date("Y-m-t", strtotime("last day of previous month")),
            'textMonth' => date("F Y", strtotime("first day of previous month"))
        );
        $getData = $modelSales->getCronrSalesHistoryMonth($getPreviousMonth);
        $royalti = 1000/2;
        if($getData != null){
            foreach($getData as $row){
                if($row->month_sale_price > 100000){
                    $bonus_royalti = 500; //(floor($row->month_sale_price/100000)) * $royalti;
                    $getLevelSp = $modelMember->getLevelSponsoring($row->id);
                    if($getLevelSp->id_lvl1 != null){
                        $dataInsertBonusLvl1 = array(
                            'user_id' => $getLevelSp->id_lvl1,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 1,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl1);
                    }
                    if($getLevelSp->id_lvl2 != null){
                        $dataInsertBonusLvl2 = array(
                            'user_id' => $getLevelSp->id_lvl2,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 2,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl2);
                    }
                    if($getLevelSp->id_lvl3 != null){
                        $dataInsertBonusLvl3 = array(
                            'user_id' => $getLevelSp->id_lvl3,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 3,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl3);
                    }
                    if($getLevelSp->id_lvl4 != null){
                        $dataInsertBonusLvl4 = array(
                            'user_id' => $getLevelSp->id_lvl4,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 4,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl4);
                    }
                    if($getLevelSp->id_lvl5 != null){
                        $dataInsertBonusLvl5 = array(
                            'user_id' => $getLevelSp->id_lvl5,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 5,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl5);
                    }
                    if($getLevelSp->id_lvl6 != null){
                        $dataInsertBonusLvl6 = array(
                            'user_id' => $getLevelSp->id_lvl6,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 6,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl6);
                    }
                    if($getLevelSp->id_lvl7 != null){
                        $dataInsertBonusLvl7 = array(
                            'user_id' => $getLevelSp->id_lvl7,
                            'from_user_id' => $row->id,
                            'type' => 3,
                            'bonus_price' => $bonus_royalti,
                            'bonus_date' => date('Y-m-d'),
                            'poin_type' => 1,
                            'level_id' => 7,
                        );
                        $modelBonus->getInsertBonusMember($dataInsertBonusLvl7);
                    }
                }
            }
            dd('Done Bonus Royalti Manthly');
        }
    }
    
    
    
    
}
