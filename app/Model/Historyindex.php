<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Historyindex extends Model {
    
    public function getInsertHistoryIndex($data){
        try {
            $lastInsertedID = DB::table('history_index')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getHistoryIndex($date){
        $sql = DB::table('history_index')
                    ->where('index_date', '=', $date)
                    ->first();
        return $sql;
    }
    
    
    
}
