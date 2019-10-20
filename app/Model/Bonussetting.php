<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Bonussetting extends Model {
    
    //Bonus Start (Sponsor) 
    public function getInsertBonusStart($data){
        try {
            $lastInsertedID = DB::table('bonus_start')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateBonusStart($fieldName, $name, $data){
        try {
            DB::table('bonus_start')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getActiveBonusStart(){
        $sql = DB::table('bonus_start')
                    ->selectRaw('id, start_price')
                    ->where('is_active', '=', 1)
                    ->first();
        return $sql;
    }
    
    public function getActiveBonusRO(){
        $sql = DB::table('bonus_repeat_order')
                    ->selectRaw('level, ro_price')
                    ->where('is_active', '=', 1)
                    ->get();
        return $sql;
    }
    
    //Bonus Start (Sponsor) 
    public function getInsertReward($data){
        try {
            $lastInsertedID = DB::table('bonus_reward2')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateReward($fieldName, $name, $data){
        try {
            DB::table('bonus_reward2')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getActiveBonusReward(){
        $sql = DB::table('bonus_reward2')
                    ->where('is_active', '=', 1)
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getActiveBonusRewardByID($id){
        $sql = DB::table('bonus_reward2')
                    ->where('is_active', '=', 1)
                    ->where('id', '=', $id)
                    ->first();
        return $sql;
    }
    
    public function getPeringkatByType($type){
        $sql = DB::table('bonus_reward2')
                    ->where('is_active', '=', 1)
                    ->where('type', '=', $type)
                    ->first();
        return $sql;
    }
    
    
}
