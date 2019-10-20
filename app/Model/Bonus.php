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
                            . 'users.user_code')
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
                    ->selectRaw('claim_reward.id, bonus_reward2.reward_detail, claim_reward.claim_date, claim_reward.status, claim_reward.reason,'
                            . 'users.user_code')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
   
    
}
