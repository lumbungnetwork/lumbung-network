<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Transferwd extends Model {
    
    public function getInsertWD($data){
        try {
            $lastInsertedID = DB::table('transfer_wd')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getTotalDiTransfer($data){
        $sql = DB::table('transfer_wd')
                    ->selectRaw('sum(case when status = 1 then wd_total else 0 end) total_wd, '
                            . 'sum(case when status = 0 then wd_total else 0 end) total_tunda,'
                            . 'sum(case when status = 2 then wd_total else 0 end) total_cancel')
                    ->where('user_id', '=', $data->id)
                    ->first();
        $total_wd = 0;
        if($sql->total_wd != null){
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if($sql->total_tunda != null){
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if($sql->total_cancel != null){
            $total_cancel = $sql->total_cancel;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel
        );
        return $return;
    }
    
    public function getCodeWD($data){
        $getTransCount = DB::table('transfer_wd')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount+1;
        $code = 'WD'.$data->id.date('Ymd').sprintf("%04s", $tmp);
        return $code;
    }
    
    public function getAllRequestWD(){
        $sql = DB::table('transfer_wd')
                    ->join('users', 'transfer_wd.user_id', '=', 'users.id')
                    ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
                    ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                            . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
                    ->where('transfer_wd.status', '=', 0)
                    ->orderBy('transfer_wd.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
    public function getAllMemberWD($data){
        $sql = DB::table('transfer_wd')
                    ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
                    ->selectRaw('transfer_wd.id, bank.bank_name, bank.account_no, bank.account_name,'
                            . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee,'
                            . 'transfer_wd.status, transfer_wd.reason, transfer_wd.wd_date')
                    ->where('transfer_wd.user_id', '=', $data->id)
                    ->orderBy('transfer_wd.id', 'DESC')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
   
    
}
