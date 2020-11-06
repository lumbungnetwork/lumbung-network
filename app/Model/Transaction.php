<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Transaction extends Model
{

    public function getInsertTransaction($data)
    {
        try {
            $lastInsertedID = DB::table('transaction')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateTransaction($fieldName, $name, $data)
    {
        try {
            DB::table('transaction')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getCodeTransaction()
    {
        $getTransCount = DB::table('transaction')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = sprintf("%04s", $tmp);
        return $code;
    }

    public function getTransactionsMember($data)
    {
        $sql = DB::table('transaction')
            ->where('user_id', '=', $data->id)
            ->get();
        return $sql;
    }

    public function getDetailTransactionsMember($id, $data)
    {
        $sql = DB::table('transaction')
            ->where('id', '=', $id)
            ->where('user_id', '=', $data->id)
            ->first();
        return $sql;
    }

    public function getDetailTransactionsMemberNew($id, $user_id, $isTron)
    {
        if ($isTron == 0) {
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

    public function getDetailTransactionsAdmin($id, $user_id)
    {
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

    public function getDetailRejectTransactionsAdminByID($id, $user_id)
    {
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

    public function getDetailTransactionsAdminNew($id, $user_id, $is_tron)
    {
        if ($is_tron == 0) {
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

    public function getDetailRejectTransactionsAdmin($id, $user_id, $is_tron)
    {
        if ($is_tron == 0) {
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

    public function getTransactionsByAdmin($status)
    {
        if ($status == null) {
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
        if (count($sql) > 0) {
            $cek = $sql;
        }
        return $cek;
    }

    public function getAdminHistoryTransactions()
    {
        $sql = DB::table('transaction')
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->join('users as u', 'transaction.submit_by', '=', 'u.id')
            ->selectRaw('users.user_code, users.hp, users.user_code, '
                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron, transaction.submit_by, u.name')
            ->where('transaction.status', '>', 1)
            ->get();
        $cek = null;
        if (count($sql) > 0) {
            $cek = $sql;
        }
        return $cek;
    }

    public function getAllRequestBeliPinYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('transaction')
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->selectRaw('users.user_code, users.hp, users.user_code, '
                . 'transaction.transaction_code, transaction.type, transaction.total_pin, transaction.price, transaction.status,'
                . 'transaction.created_at, transaction.unique_digit, transaction.user_id, transaction.id, transaction.is_tron')
            ->where('transaction.status', '<', 2)
            ->whereDate('transaction.created_at', '=', $yesterday)
            ->orderBy('transaction.status', 'DESC')
            ->orderBy('transaction.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getInsertDepositTransaction($data)
    {
        try {
            $lastInsertedID = DB::table('deposit_transaction')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateDepositTransaction($fieldName, $name, $data)
    {
        try {
            DB::table('deposit_transaction')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getCodeDepositTransaction()
    {
        $getTransCount = DB::table('deposit_transaction')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = sprintf("%04s", $tmp);
        return $code;
    }

    public function getDepositTransactionsMember($data)
    {
        $sql = DB::table('deposit_transaction')
            ->where('user_id', '=', $data->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $sql;
    }

    public function getDetailDepositTransactionsMember($id, $data)
    {
        $sql = DB::table('deposit_transaction')
            ->where('id', '=', $id)
            ->where('user_id', '=', $data->id)
            ->first();
        return $sql;
    }

    public function getDetailDepositTransactionsMemberNew($id, $user_id, $isTron)
    {
        if ($isTron == 0) {
            $sql = DB::table('deposit_transaction')
                ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
                ->join('bank', 'deposit_transaction.bank_perusahaan_id', '=', 'bank.id')
                ->selectRaw('users.name, users.hp, users.user_code, '
                    . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                    . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron, '
                    . 'bank.bank_name, bank.account_name, bank.account_no')
                ->where('deposit_transaction.id', '=', $id)
                ->where('deposit_transaction.user_id', '=', $user_id)
                ->first();
        } else {
            $sql = DB::table('deposit_transaction')
                ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
                ->join('tron', 'deposit_transaction.bank_perusahaan_id', '=', 'tron.id')
                ->selectRaw('users.name, users.hp, users.user_code, '
                    . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                    . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron, '
                    . 'tron.tron_name, tron.tron, deposit_transaction.tron_transfer ')
                ->where('deposit_transaction.id', '=', $id)
                ->where('deposit_transaction.user_id', '=', $user_id)
                ->first();
        }
        return $sql;
    }

    public function getDetailRejectDepositTransactionsAdmin($id, $user_id, $is_tron)
    {
        if ($is_tron == 0) {
            $sql = DB::table('deposit_transaction')
                ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
                ->selectRaw('users.name, users.hp, users.user_code, '
                    . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                    . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron ')
                ->where('deposit_transaction.id', '=', $id)
                ->where('deposit_transaction.user_id', '=', $user_id)
                ->first();
        } else {
            $sql = DB::table('deposit_transaction')
                ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
                ->join('tron', 'deposit_transaction.bank_perusahaan_id', '=', 'tron.id')
                ->selectRaw('users.name, users.hp, users.user_code, '
                    . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                    . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron, '
                    . 'tron.tron_name, tron.tron')
                ->where('deposit_transaction.id', '=', $id)
                ->where('deposit_transaction.user_id', '=', $user_id)
                ->first();
        }

        return $sql;
    }

    public function getTransactionsIsiDepositByAdmin()
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->selectRaw('users.user_code, users.hp,  '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron')
            ->where('deposit_transaction.type', '=', 1)
            ->where('deposit_transaction.status', '=', 1)
            ->orderBy('deposit_transaction.id', 'DESC')
            ->get();
        $cek = null;
        if (count($sql) > 0) {
            $cek = $sql;
        }
        return $cek;
    }

    public function getDetailDepositTransactionsAdmin($id, $user_id)
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->join('bank', 'deposit_transaction.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.name, users.hp, users.user_code, '
                . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron, '
                . 'bank.bank_name, bank.account_name, bank.account_no')
            ->where('deposit_transaction.id', '=', $id)
            ->where('deposit_transaction.user_id', '=', $user_id)
            ->where('deposit_transaction.status', '=', 1)
            ->first();
        return $sql;
    }

    public function getDetailRejectDepositTransactionsAdminByID($id, $user_id)
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->selectRaw('users.name, users.hp, users.user_code, '
                . 'deposit_transaction.transaction_code, deposit_transaction.type, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, deposit_transaction.is_tron')
            ->where('deposit_transaction.id', '=', $id)
            ->where('deposit_transaction.user_id', '=', $user_id)
            ->first();
        return $sql;
    }

    public function getMyTotalTarikDeposit($data)
    {
        $sql = DB::table('deposit_transaction')
            ->selectRaw('sum(price) as deposit_keluar')
            ->where('user_id', '=', $data->id)
            ->where('type', '=', 2)
            //                    ->whereIn('status', array(1, 2))
            ->where('status', '=', 1)
            ->first();
        return $sql;
    }

    public function getTransactionsTarikDepositByAdmin()
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->leftJoin('bank', 'deposit_transaction.user_bank', '=', 'bank.id')
            ->selectRaw('users.user_code, users.hp, '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, '
                . 'deposit_transaction.id, deposit_transaction.is_tron, users.tron as user_tron,'
                . 'bank.bank_name, bank.account_no, bank.account_name')
            ->where('deposit_transaction.type', '=', 2)
            ->where('deposit_transaction.status', '=', 1)
            ->orderBy('deposit_transaction.id', 'DESC')
            ->get();
        $cek = null;
        if (count($sql) > 0) {
            $cek = $sql;
        }
        return $cek;
    }

    public function getTransactionsTarikDepositByAdminByID($id)
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->leftJoin('bank', 'deposit_transaction.user_bank', '=', 'bank.id')
            ->selectRaw('users.user_code, users.hp, '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, '
                . 'deposit_transaction.id, deposit_transaction.is_tron, users.tron as user_tron,'
                . 'bank.bank_name, bank.account_no, bank.account_name')
            ->where('deposit_transaction.id', '=', $id)
            ->where('deposit_transaction.type', '=', 2)
            ->where('deposit_transaction.status', '=', 1)
            ->first();
        return $sql;
    }

    public function getTotalAllTarikDeposit()
    {
        $sql = DB::table('deposit_transaction')
            ->selectRaw('sum(price) as deposit_keluar')
            ->where('type', '=', 2)
            ->where('status', '=', 2)
            ->first();
        return $sql;
    }

    public function getHistoryTransactionsDepositByAdmin()
    {
        $sql1 = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->join('users as a', 'deposit_transaction.submit_by', '=', 'a.id')
            ->leftJoin('bank', 'deposit_transaction.bank_perusahaan_id', '=', 'bank.id')
            ->selectRaw('users.user_code, users.hp, '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, '
                . 'deposit_transaction.is_tron, deposit_transaction.type,  deposit_transaction.user_bank, deposit_transaction.bank_perusahaan_id,'
                . 'deposit_transaction.tron_transfer, deposit_transaction.tuntas_at, '
                . 'CONCAT(bank.bank_name, bank.account_no, bank.account_name) as buy_metode,'
                . 'a.user_code as submit_name, deposit_transaction.submit_by ')
            ->orderBy('deposit_transaction.id', 'DESC')
            ->where('deposit_transaction.is_tron', '=', 0);
        $sql2 = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->join('users as a', 'deposit_transaction.submit_by', '=', 'a.id')
            ->leftJoin('tron', 'deposit_transaction.bank_perusahaan_id', '=', 'tron.id')
            ->selectRaw('users.user_code, users.hp, '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, '
                . 'deposit_transaction.is_tron, deposit_transaction.type,  deposit_transaction.user_bank, deposit_transaction.bank_perusahaan_id,'
                . 'deposit_transaction.tron_transfer, deposit_transaction.tuntas_at, '
                . 'tron.tron as buy_metode, a.user_code as submit_name, deposit_transaction.submit_by')
            ->orderBy('deposit_transaction.id', 'DESC')
            ->where('deposit_transaction.is_tron', '=', 1)
            ->union($sql1)
            ->orderBy('id', 'DESC')
            ->get();
        return $sql2;
    }

    public function getHistoryTransactionsDepositByAdminTron()
    {
        $sql = DB::table('deposit_transaction')
            ->join('users', 'deposit_transaction.user_id', '=', 'users.id')
            ->leftJoin('tron', 'deposit_transaction.bank_perusahaan_id', '=', 'tron.id')
            ->selectRaw('users.user_code, users.hp, '
                . 'deposit_transaction.transaction_code, deposit_transaction.price, deposit_transaction.status,'
                . 'deposit_transaction.created_at, deposit_transaction.unique_digit, deposit_transaction.user_id, deposit_transaction.id, '
                . 'deposit_transaction.is_tron, deposit_transaction.type,  deposit_transaction.user_bank, deposit_transaction.bank_perusahaan_id,'
                . 'deposit_transaction.tron_transfer, deposit_transaction.tuntas_at, '
                . 'tron.tron')
            ->orderBy('deposit_transaction.id', 'DESC')
            ->where('deposit_transaction.is_tron', '=', 1)
            ->get();
        $cek = null;
        if (count($sql) > 0) {
            $cek = $sql;
        }
        return $cek;
    }
}
