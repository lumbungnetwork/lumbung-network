<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Binaryhistory extends Model {
    
    public function getInsertBinaryHistory($data){
        try {
            $lastInsertedID = DB::table('binary_history')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getBinaryHistory($id){
        $sql = DB::table('binary_history')
                    ->selectRaw('
		sum(total_left) as sum_total_kiri,
		sum(total_right) as sum_total_kanan
                    ')
                    ->where('user_id', '=', $id)
                    ->first();
        $sum_total_kiri = 0;
        if($sql->sum_total_kiri != null){
            $sum_total_kiri = $sql->sum_total_kiri;
        }
        $sum_total_kanan = 0;
        if($sql->sum_total_kanan != null){
            $sum_total_kanan = $sql->sum_total_kanan;
        }
        $return = (object) array(
            'sum_total_kiri' =>$sum_total_kiri,
            'sum_total_kanan' =>$sum_total_kanan
        );
        return $return;
    }
    
    
    
}
