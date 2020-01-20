<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Bonus extends Model {
    
    public function getInsertBonusMember($data){
        try {
            $lastInsertedID = DB::table('bonus_member')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getTotalBonus($data){
        $sql = DB::table('bonus_member')
                    ->selectRaw('sum(bonus_price) as total_bonus')
                    ->where('user_id', '=', $data->id)
                    ->whereIn('type', array(1, 2))
                    ->where('poin_type', '=', 1)
                    ->first();
        $total_bonus = 0;
        if($sql->total_bonus != null){
            $total_bonus = $sql->total_bonus;
        }
        $return = (object) array(
            'total_bonus' => $total_bonus
        );
        return $return;
    }
    
    public function getTotalBonusRoyalti($data){
        $sql = DB::table('bonus_member')
                    ->selectRaw('sum(bonus_price) as total_bonus')
                    ->where('user_id', '=', $data->id)
                    ->where('type', '=', 3)
                    ->where('poin_type', '=', 1)
                    ->first();
        $total_bonus = 0;
        if($sql->total_bonus != null){
            $total_bonus = $sql->total_bonus;
        }
        $return = (object) array(
            'total_bonus' => $total_bonus
        );
        return $return;
    }
    
    public function getCekBonusRoyaltiMax($id, $level, $maxBonus){
        $return = true;
        if($id > 11){
            $sql = DB::table('bonus_member')
                        ->selectRaw('count(id) as total_max')
                        ->where('user_id', '=', $id)
                        ->where('type', '=', 3)
                        ->where('level_id', '=', $level)
                        ->first();
            if($sql->total_max != null){
                if($sql->total_max >= pow($maxBonus, $level)){
                    $return = false;
                }
            }
        }
        return $return;
    }
    
    public function getBonusSponsor($data){
        $sql = DB::table('bonus_member')
                    ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
                    ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.user_code')
                    ->where('bonus_member.user_id', '=', $data->id)
                    ->where('bonus_member.type', '=', 1)
                    ->where('bonus_member.poin_type', '=', 1)
                    ->orderBy('bonus_member.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getBonusLevel($data){
        $sql = DB::table('bonus_member')
                    ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
                    ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.user_code, bonus_member.level_id')
                    ->where('bonus_member.user_id', '=', $data->id)
                    ->where('bonus_member.type', '=', 3)
                    ->where('bonus_member.poin_type', '=', 1)
                    ->orderBy('bonus_member.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getBonusRO($data){
        $sql = DB::table('bonus_member')
                    ->join('users', 'bonus_member.from_user_id', '=', 'users.id')
                    ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, users.name, users.user_code')
                    ->where('bonus_member.user_id', '=', $data->id)
                    ->where('bonus_member.type', '=', 4)
                    ->where('bonus_member.poin_type', '=', 1)
                    ->orderBy('bonus_member.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getBonusSponsorByAdmin(){
        $sql = DB::table('bonus_member')
                    ->join('users', 'bonus_member.user_id', '=', 'users.id')
                    ->join('bank', 'users.id', '=', 'bank.user_id')
                    ->selectRaw('sum(bonus_member.bonus_price) as total_bonus, '
                            . 'users.name, users.user_code, users.total_sponsor, users.hp, '
                            . 'bank.bank_name, bank.account_no,'
                            . 'users.full_name')
                    ->where('bonus_member.type', '=', 1)
                    ->where('bonus_member.poin_type', '=', 1)
                    ->where('bank.is_active', '=', 1)
                    ->groupBy('users.name')
                    ->groupBy('users.full_name')
                    ->groupBy('users.user_code')
                    ->groupBy('users.total_sponsor')
                    ->groupBy('bank.bank_name')
                    ->groupBy('bank.account_no')
                    ->groupBy('users.hp')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getBonusBinary($data){
        $sql = DB::table('bonus_member')
                    ->selectRaw('bonus_member.bonus_price, bonus_member.bonus_date, bonus_member.total_binary,'
                            . 'bonus_member.total_activated, bonus_member.bonus_index, bonus_member.total_all_binary, '
                            . 'bonus_member.index_date, bonus_member.bonus_setting')
                    ->where('bonus_member.user_id', '=', $data->id)
                    ->where('bonus_member.type', '=', 2)
                    ->where('bonus_member.poin_type', '=', 1)
                    ->orderBy('bonus_member.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getInsertClaimReward($data){
        try {
            $lastInsertedID = DB::table('claim_reward')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateClaimReward($fieldName, $name, $data){
        try {
            DB::table('claim_reward')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getMemberRewardByUser($data, $reward_id){
        $sql = DB::table('claim_reward')
                    ->selectRaw('claim_reward.id')
                    ->where('claim_reward.user_id', '=', $data->id)
                    ->where('claim_reward.reward_id', '=', $reward_id)
                    ->where('claim_reward.status', '!=', 2)
                    ->first();
        return $sql;
    }
    
    public function getMemberRewardHistory($data){
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->selectRaw('bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason')
                    ->where('claim_reward.user_id', '=', $data->id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllReward(){
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->join('users', 'claim_reward.user_id', '=', 'users.id')
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code, users.tron')
                    ->where('claim_reward.status', '=', 0)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminRewardByID($id){
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->join('users', 'claim_reward.user_id', '=', 'users.id')
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code')
                    ->where('claim_reward.id', '=', $id)
                    ->where('claim_reward.status', '=', 0)
                    ->first();
        return $sql;
    }
    
    public function getAdminDetailRewardByID($id){
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->join('users', 'claim_reward.user_id', '=', 'users.id')
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code')
                    ->where('claim_reward.id', '=', $id)
                    ->first();
        return $sql;
    }
    
    public function getAdminHistoryReward(){
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->join('users', 'claim_reward.user_id', '=', 'users.id')
                    ->join('users as u', 'claim_reward.submit_by', '=', 'u.id')
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code, claim_reward.submit_by, u.name')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getInsertBelanjaReward($data){
        try {
            $lastInsertedID = DB::table('belanja_reward')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateBelanjaReward($fieldName, $name, $data){
        try {
            DB::table('belanja_reward')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getTotalBelanjaReward($id){
        $sql = DB::table('belanja_reward')
                    ->where('belanja_reward.user_id', '=', $id)
                    ->where('belanja_reward.status', '=', 1)
                    ->where('belanja_reward.type', '=', 1)
                    ->sum('belanja_reward.reward');
        return $sql;
    }
    
    public function getBelanjaRewardByMonthYear($id, $month, $year){
        $sql = DB::table('belanja_reward')
                    ->selectRaw('id')
                    ->where('user_id', '=', $id)
                    ->where('month', '=', $month)
                    ->where('year', '=', $year)
                    ->where('status', '!=', 2)
                    ->where('type', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getAdminAllBelanjaReward(){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, users.is_tron')
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllBelanjaRewardByID($id){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron')
                    ->where('belanja_reward.id', '=', $id)
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getAdminHistoryBelanjaReward(){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
                    ->where('belanja_reward.type', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminDetailBelanjaReward($id){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, belanja_reward.status, belanja_reward.reason')
                    ->where('belanja_reward.id', '=', $id)
                    ->where('belanja_reward.type', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getTotalPenjualanReward($id){
        $sql = DB::table('belanja_reward')
                    ->where('belanja_reward.user_id', '=', $id)
                    ->where('belanja_reward.status', '=', 1)
                    ->where('belanja_reward.type', '=', 2)
                    ->sum('belanja_reward.reward');
        return $sql;
    }
    
    public function getPenjualanRewardByMonthYear($id, $month, $year){
        $sql = DB::table('belanja_reward')
                    ->selectRaw('id')
                    ->where('user_id', '=', $id)
                    ->where('month', '=', $month)
                    ->where('year', '=', $year)
                    ->where('status', '!=', 2)
                    ->where('type', '=', 2)
                    ->first();
        return $sql;
    }
    
    public function getAdminAllPenjualanReward(){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, users.is_tron')
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 2)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllPenjualanRewardByID($id){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron')
                    ->where('belanja_reward.id', '=', $id)
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 2)
                    ->first();
        return $sql;
    }
    
    public function getAdminDetailPenjualanReward($id){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, belanja_reward.status, belanja_reward.reason')
                    ->where('belanja_reward.id', '=', $id)
                    ->where('belanja_reward.type', '=', 2)
                    ->first();
        return $sql;
    }
    
    public function getAdminHistoryPenjualanReward(){
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->join('users as u', 'belanja_reward.submit_by', '=', 'u.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.tron, belanja_reward.status, belanja_reward.submit_by, u.name')
                    ->where('belanja_reward.type', '=', 2)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllBelanjaRewardYesterday(){
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.hp, users.tron, users.is_tron')
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 1)
                    ->whereDate('belanja_reward.belanja_date', '=', $yesterday)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllPenjualanRewardYesterday(){
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $sql = DB::table('belanja_reward')
                    ->join('users', 'belanja_reward.user_id', '=', 'users.id')
                    ->selectRaw('belanja_reward.id, belanja_reward.reward, belanja_reward.month, belanja_reward.year, '
                            . 'belanja_reward.belanja_date, belanja_reward.total_belanja, DATE_FORMAT(belanja_reward.belanja_date, "%M - %Y") as monthly, '
                            . 'belanja_reward.created_at, users.user_code, users.hp, users.tron, users.is_tron')
                    ->where('belanja_reward.status', '=', 0)
                    ->where('belanja_reward.type', '=', 2)
                    ->whereDate('belanja_reward.belanja_date', '=', $yesterday)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminAllRewardYesterday(){
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        $sql = DB::table('claim_reward')
                    ->join('bonus_reward2', 'claim_reward.reward_id', '=', 'bonus_reward2.id')
                    ->join('users', 'claim_reward.user_id', '=', 'users.id')
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code, users.hp, users.tron')
                    ->where('claim_reward.status', '=', 0)
                    ->whereDate('claim_reward.claim_date', '=', $yesterday)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAllClaimLMB(){
        $sql = DB::table('belanja_reward')
                    ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
                    ->where('belanja_reward.status', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getAllClaimRewardLMB(){
        $sql = DB::table('claim_reward')
                    ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                            . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                            . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                            . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
                    ->where('status', '=', 1)
                    ->whereIn('reward_id', array(1,2,3,4))
                    ->first();
        return $sql;
    }
    
    public function getAllClaimLMBByIDUserCode($data){
        $sql = DB::table('belanja_reward')
                    ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
                    ->where('belanja_reward.user_id', '=', $data->id)
                    ->where('belanja_reward.status', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getAllClaimRewardLMBByIDUserCode($data){
        $sql = DB::table('claim_reward')
                    ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                            . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                            . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                            . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
                    ->where('claim_reward.user_id', '=', $data->id)
                    ->where('status', '=', 1)
                    ->whereIn('reward_id', array(1,2,3,4))
                    ->first();
        return $sql;
    }
    
    public function getAllClaimLMBLastMonth($date){
        $sql = DB::table('belanja_reward')
                    ->selectRaw('sum(belanja_reward.reward) as total_claim_shop')
                    ->where('belanja_reward.status', '=', 1)
                    ->whereDate('belanja_reward.belanja_date', '>=', $date->start_day)
                    ->whereDate('belanja_reward.belanja_date', '<=', $date->end_day)
                    ->first();
        return $sql;
    }
    
    public function getAllClaimRewardLMBLastMonth($date){
        $sql = DB::table('claim_reward')
                    ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as tot_reward_1, '
                            . 'sum(case when reward_id = 2 then 200 else 0 end) as tot_reward_2,'
                            . 'sum(case when reward_id = 3 then 500 else 0 end) as tot_reward_3,'
                            . 'sum(case when reward_id = 4 then 2000 else 0 end) as tot_reward_4')
                    ->where('status', '=', 1)
                    ->whereIn('reward_id', array(1,2,3,4))
                    ->whereDate('claim_date', '>=', $date->start_day)
                    ->whereDate('claim_date', '<=', $date->end_day)
                    ->first();
        return $sql;
    }
    
    public function getInsertTopUp($data){
        try {
            $lastInsertedID = DB::table('top_up')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateTopUp($fieldName, $name, $data){
        try {
            DB::table('top_up')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getAllTopUpSaldo($data){
        $sql = DB::table('top_up')
                    ->where('user_id', '=', $data->id)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getTopUpSaldoID($id){
        $sql = DB::table('top_up')
                    ->where('id', '=', $id)
                    ->first();
        return $sql;
    }
    
    public function getTopUpSaldoIDUserId($id, $data){
        $sql = DB::table('top_up')
                    ->where('id', '=', $id)
                    ->where('user_id', '=', $data->id)
                    ->first();
        return $sql;
    }
    
    public function getTotalSaldoUserId($data){
        $sql = DB::table('top_up')
                    ->selectRaw('sum(nominal) as total_topup')
                    ->where('user_id', '=', $data->id)
                    ->where('status', '=', 2)
                    ->first();
        $total = 0;
        if($sql->total_topup != null){
            $total = $sql->total_topup;
        }
        return $total;
    }
    
    public function getAllRequestTopup(){
        $sql = DB::table('top_up')
                        ->join('users', 'top_up.user_id', '=', 'users.id')
                        ->join('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, users.tron, '
                                . 'top_up.status, top_up.updated_at, top_up.id,'
                                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->where('top_up.status', '=', 1)
                        ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAdminTopUpSaldoIDUserId($id, $user_id){
        $sql = DB::table('top_up')
                        ->join('users', 'top_up.user_id', '=', 'users.id')
                        ->join('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'top_up.status, top_up.updated_at, top_up.id,'
                                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->where('top_up.id', '=', $id)
                        ->where('top_up.user_id', '=', $user_id)
                        ->first();
        return $sql;
    }
    
    public function getAllHistoryTopup(){
        $sql = DB::table('top_up')
                        ->join('users', 'top_up.user_id', '=', 'users.id')
                        ->leftJoin('bank', 'top_up.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'top_up.status, top_up.updated_at, top_up.id,'
                                . 'top_up.created_at, top_up.unique_digit, top_up.user_id, top_up.nominal, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
   
    
}
