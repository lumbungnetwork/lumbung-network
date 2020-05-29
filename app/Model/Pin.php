<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Pin extends Model {
    
    public function getInsertMemberPin($data){
        try {
            $lastInsertedID = DB::table('member_pin')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateMemberPin($fieldName, $name, $data){
        try {
            DB::table('member_pin')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getTotalPinAdmin(){
        $sql = DB::table('master_pin')
                    ->selectRaw('
		sum(case when type_pin = 1 then total_pin end) as sum_pin_masuk,
		sum(case when type_pin = 2 then total_pin end) as sum_pin_keluar
                    ')
                    ->first();
        return $sql;
    }
    
    public function getTotalPinMember($data){
        $sql = DB::table('member_pin')
                    ->selectRaw('
		sum(case when is_used = 0 then total_pin else 0 end) as sum_pin_masuk,
		sum(case when is_used = 1 then total_pin else 0 end) as sum_pin_keluar
                    ')
                    ->where('user_id', '=', $data->id)
                    ->first();
        return $sql;
    }
    
    public function getMyLastPin($data){
        $sql = DB::table('member_pin')
                    ->selectRaw('setting_pin, pin_code')
                    ->where('user_id', '=', $data->id)
                    ->where('is_used', '=', 0)
                    ->orderBy('id', 'DESC')
                    ->first();
        return $sql;
    }
    
    public function getMyHistoryPin($data){
        $sql = DB::table('member_pin')
                    ->leftJoin('users as u1', 'member_pin.used_user_id', '=', 'u1.id')
                    ->leftJoin('users as u2', 'member_pin.transfer_user_id', '=', 'u2.id')
                    ->leftJoin('users as u3', 'member_pin.transfer_from_user_id', '=', 'u3.id')
                    ->selectRaw('member_pin.total_pin, member_pin.pin_status, member_pin.is_used, member_pin.used_at, '
                            . 'member_pin.created_at, member_pin.transaction_code, '
                            . 'u1.name as name_activation, '
                            . 'u2.name as name_transfer_to, '
                            . 'u3.name as name_transfer_from')
                    ->where('member_pin.user_id', '=', $data->id)
                    ->orderBy('member_pin.id', 'DESC')
                    ->get();
        return $sql;
    }
    
    public function getMyTotalPinPengiriman($data){
        $sql = DB::table('member_pin')
                    ->selectRaw('sum(total_pin) as pin_tersedia')
                    ->where('user_id', '=', $data->id)
                    ->where('is_used', '=', 0)
                    ->first();
        return $sql;
    }
    
    public function getCheckMaxPinROByDate($sp_id, $startDate, $endDate){
        $sql = DB::table('member_pin')
                    ->leftJoin('users', 'member_pin.user_id', '=', 'users.id')
                    ->selectRaw('sum(member_pin.total_pin) as total_pin_ro')
                    ->where('users.sponsor_id', '=', $sp_id)
                    ->whereDate('member_pin.used_at', '>=', $startDate)
                    ->whereDate('member_pin.used_at', '<', $endDate)
                    ->where('member_pin.is_used', '=', 1)
                    ->where('member_pin.is_ro', '=', 1)
                    ->first();
        $return = 0;
        if($sql->total_pin_ro != null){
            $return = $sql->total_pin_ro;
        }
        return $return;
    }
    
    public function getInsertMemberDeposit($data){
        try {
            $lastInsertedID = DB::table('member_deposito')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateMemberDeposit($fieldName, $name, $data){
        try {
            DB::table('member_deposito')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getMyHistoryDeposit($data){
        $sql = DB::table('member_deposito')
                    ->selectRaw('member_deposito.total_deposito, member_deposito.deposito_status,  '
                            . 'member_deposito.created_at, member_deposito.transaction_code')
                    ->where('member_deposito.user_id', '=', $data->id)
                    ->orderBy('member_deposito.id', 'DESC')
                    ->get();
        return $sql;
    }
    
    public function getTotalDepositMember($data){
        $sql = DB::table('member_deposito')
                    ->selectRaw('
		sum(case when deposito_status = 0 then total_deposito else 0 end) as sum_deposit_masuk,
		sum(case when deposito_status in (1, 2) then total_deposito else 0 end) as sum_deposit_keluar
                    ')
                    ->where('user_id', '=', $data->id)
                    ->first();
        return $sql;
    }
    
    
}
