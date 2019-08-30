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
                            . 'sum(case when status = 0 then wd_total else 0 end) total_tunda')
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
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda
        );
        return $return;
    }
   
    
}
