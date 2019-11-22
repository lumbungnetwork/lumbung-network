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
    
    public function getDetailTransactionsMemberNew($id, $user_id, $isTron){
        if($isTron == 0){
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('bank', 'transaction.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'bank.bank_name as to_name, bank.account_name, bank.account_no as account')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->first();
        } else {
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('tron', 'transaction.bank_perusahaan_id', '=', 'tron.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'tron.tron_name as to_name, tron.tron as account, " " as account_name ')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->first();
        }
        
        return $sql;
    }
    
    public function getDetailTransactionsAdmin($id, $user_id){
        $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('bank', 'transaction.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->where('transaction.status', '=', 1)
                        ->first();
        return $sql;
    }
    
    public function getDetailRejectTransactionsAdminByID($id, $user_id){
        $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->first();
        return $sql;
    }
    
    public function getDetailTransactionsAdminNew($id, $user_id, $is_tron){
        if($is_tron == 0){
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('bank', 'transaction.bank_perusahaan_id', '=', 'bank.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'bank.bank_name, bank.account_name, bank.account_no')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->where('transaction.status', '=', 1)
                        ->first();
        } else {
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('tron', 'transaction.bank_perusahaan_id', '=', 'tron.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'tron.tron_name, tron.tron')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->where('transaction.status', '=', 1)
                        ->first();
        }
        
        return $sql;
    }
    
    public function getDetailRejectTransactionsAdmin($id, $user_id, $is_tron){
        if($is_tron == 0){
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron ')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->first();
        } else {
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->join('tron', 'transaction.bank_perusahaan_id', '=', 'tron.id')
                        ->selectRaw('users.name, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, '
                                . 'tron.tron_name, tron.tron')
                        ->where('transaction.id', '=', $id)
                        ->where('transaction.user_id', '=', $user_id)
                        ->first();
        }
        
        return $sql;
    }
    
    public function getTransactionsByAdmin($status){
        if($status == null){
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.user_code, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron')
                        ->where('transaction.status', '<', 2)
                        ->orderBy('transaction.status', 'DESC')
                        ->orderBy('transaction.id', 'DESC')
                        ->get();
        } else {
            $sql = DB::table('transaction')
                        ->join('users', 'transaction.user_id', '=', 'users.id')
                        ->selectRaw('users.user_code, users.hp, users.user_code, '
                                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron')
                        ->where('transaction.status', '=', $status)
                        ->get();
        }
        $cek = null;
        if(count($sql) > 0){
            $cek = $sql;
        }
        return $cek;
    }
    
    public function getAdminHistoryTransactions(){
        $sql = DB::table('transaction')
                    ->join('users', 'transaction.user_id', '=', 'users.id')
                    ->join('users as u', 'transaction.submit_by', '=', 'u.id')
                    ->selectRaw('users.user_code, users.hp, users.user_code, '
                            . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                            . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, transaction.submit_by, u.name')
                    ->where('transaction.status', '>', 1)
                    ->get();
        $cek = null;
        if(count($sql) > 0){
            $cek = $sql;
        }
        return $cek;
    }
    
    
}
