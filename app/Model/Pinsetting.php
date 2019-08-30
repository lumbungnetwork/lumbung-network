<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Pinsetting extends Model {
    
    public function getInsertPinSetting($data){
        try {
            DB::table('pin_setting')->insert($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getUpdatePinSetting($id, $data){
        try {
            DB::table('pin_setting')->where('id', '=', $id)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getRemoveAllPinSetting($data){
        try {
            DB::table('pin_setting')->where('is_active', '=', 1)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getActivePinSetting(){
        $sql = DB::table('pin_setting')
                    ->selectRaw('id, price')
                    ->where('is_active', '=', 1)
                    ->first();
        return $sql;
    }
    
    
}
