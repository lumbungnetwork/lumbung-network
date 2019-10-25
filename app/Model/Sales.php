<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Sales extends Model {
    
    public function getInsertPurchase($data){
        try {
            $lastInsertedID = DB::table('purchase')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdatePurchase($fieldName, $name, $data){
        try {
            DB::table('purchase')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getAllPurchase(){
        $sql = DB::table('purchase')
                    ->whereNull('deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return =$sql;
        }
        return $return;
    }
    
    public function getDetailPurchase($id){
        $sql = DB::table('purchase')
                    ->where('id', '=', $id)
                    ->whereNull('deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getInsertStock($data){
        try {
            $lastInsertedID = DB::table('stock')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getInsertSales($data){
        try {
            $lastInsertedID = DB::table('sales')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getMemberSales($id){
        $start_day = date("Y-m-01");
        $end_day = date("Y-m-t");
        $sql = DB::table('sales')
                    ->selectRaw('sum(sale_price) as jml_price')
                    ->where('user_id', '=', $id)
                    ->whereDate('sales.sale_date', '>=', $start_day)
                    ->whereDate('sales.sale_date', '<=', $end_day)
                    ->whereNull('deleted_at')
                    ->first();
        return $sql;
    }
    
    public function getThisMonth(){
        $start_day = date('Y-m-01');
        $end_day = date('Y-m-t');
        $text_month = date('F Y');
        $data = (object) array(
            'startDay' => $start_day,
            'endDay' => $end_day,
            'textMonth' => $text_month
        );
        return $data;
    }
    
    public function getMemberSalesHistory($id, $date){
        $sql = DB::table('sales')
                    ->join('users', 'sales.stockist_id', '=', 'users.id')
                    ->selectRaw('sales.sale_date, users.user_code, sales.sale_price')
                    ->where('sales.user_id', '=', $id)
                    ->whereDate('sales.sale_date', '>=', $date->startDay)
                    ->whereDate('sales.sale_date', '<=', $date->endDay)
                    ->whereNull('sales.deleted_at')
                    ->get();
        $return = null;
        if(count($sql) > 0){
            $return = $sql;
        }
        return $return;
    }
    
}

