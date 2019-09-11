<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Transaction extends Model {
    
    public function getInsertTransaction($data){
        try {
            $lastInsertedID = DB::table('transaction')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }
    
    public function getUpdateTransaction($fieldName, $name, $data){
        try {
            DB::table('transaction')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }
    
    public function getCodeTransaction(){
        $getTransCount = DB::table('transaction')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount+1;
        $code = sprintf("%04s", $tmp);
        return $code;
    }
    
    public function getTransactionsMember($data){
        $sql = DB::table('transaction')
                    ->where('user_id', '=', $data->id)
                    ->get();
        return $sql;
    }
    
    public function getDetailTransactionsMember($id, $data){
        $sql = DB::table('transaction')
                    ->where('id', '=', $id)
                    ->where('user_id', '=', $data->id)
                    ->first();
        return $sql;
    }
    
    public function getDetailTransactionsAdmin($id, $user_id){
        $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('bank', 'transaction.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->where('transaction.status', '=', 1)
                        ->first();
        return $sql;
    }
    
    public function getTransactionsByAdmin($status){
        if($status == null){
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.user_code, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id')
                        ->where('transaction.status', '<', 2)
                        ->get();
        } else {
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.user_code, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id')
                        ->where('transaction.status', '=', $status)
                        ->get();
        }
        $cek = null;
        if(count($sql) > 0){
            $cek = $sql;
        }
        return $cek;
    }
    
    
}
