<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;
use Validator;

class Transferwd extends Model
{

    public function getInsertWD($data)
    {
        try {
            $lastInsertedID = DB::table('transfer_wd')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateWD($fieldName, $name, $data)
    {
        try {
            DB::table('transfer_wd')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Throwable $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getTotalDiTransfer($data)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->where('user_id', '=', $data->id)
            ->where('type', '=', 1)
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getTotalDiTransfereIDR($data)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->where('user_id', '=', $data->id)
            ->where('type', '=', 5)
            ->where('is_tron', '=', 1)
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getTotalDiTransferRoyaltieIDR($data)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->where('user_id', '=', $data->id)
            ->where('type', '=', 6)
            ->where('is_tron', '=', 1)
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getTotalDiTransferRoyalti($data)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->where('user_id', '=', $data->id)
            ->where('type', '=', 3)
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getCodeWD($data)
    {
        $getTransCount = DB::table('transfer_wd')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = 'WD' . $data->id . date('Ymd') . sprintf("%04s", $tmp);
        return $code;
    }

    public function getCodeWDeIDR($data)
    {
        $getTransCount = DB::table('transfer_wd')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = 'eIDR' . $data->id . date('Ymd') . sprintf("%04s", $tmp);
        return $code;
    }

    public function getAllRequestWD()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.is_tron', '=', 0)
            ->where('transfer_wd.type', '=', 1)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestWDeIDR()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, transfer_wd.user_id, '
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.type', '=', 5)
            ->where('transfer_wd.is_tron', '=', 1)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestWDRoyalti()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.is_tron, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.type', '=', 3)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    // public function getAllRequestWDRoyalti()
    // {
    //     $sql1 = DB::table('transfer_wd')
    //         ->join('users', 'transfer_wd.user_id', '=', 'users.id')
    //         ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
    //         ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name, users.tron, transfer_wd.is_tron, '
    //             . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
    //         ->where('transfer_wd.status', '=', 0)
    //         ->where('transfer_wd.is_tron', '=', 0)
    //         ->where('transfer_wd.type', '=', 3);
    //     $sql2 = DB::table('transfer_wd')
    //         ->join('users', 'transfer_wd.user_id', '=', 'users.id')
    //         ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
    //         ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name, users.tron, transfer_wd.is_tron, '
    //             . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
    //         ->where('transfer_wd.status', '=', 0)
    //         ->where('transfer_wd.is_tron', '=', 1)
    //         ->where('transfer_wd.type', '=', 6)
    //         ->union($sql1)
    //         ->get();
    //     $return = null;
    //     if (count($sql2) > 0) {
    //         $return = $sql2;
    //     }
    //     return $return;
    // }

    public function getAllMemberWD($data)
    {
        $sql = DB::table('transfer_wd')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee,'
                . 'transfer_wd.status, transfer_wd.reason, transfer_wd.is_tron, transfer_wd.wd_date, transfer_wd.type')
            ->where('transfer_wd.user_id', '=', $data->id)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getIDRequestWD($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, users.full_name,'
                . 'transfer_wd.reason, transfer_wd.status')
            ->where('transfer_wd.id', '=', $id)
            ->where('transfer_wd.type', '=', 1)
            ->orderBy('transfer_wd.id', 'DESC')
            ->first();
        return $sql;
    }

    public function getIDRequestWDReject($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, users.full_name,'
                . 'transfer_wd.reason, transfer_wd.status, transfer_wd.is_tron')
            ->where('transfer_wd.id', '=', $id)
            ->orderBy('transfer_wd.id', 'DESC')
            ->first();
        return $sql;
    }

    public function getIDRequestWDeIDR($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, '
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, users.full_name,'
                . 'transfer_wd.reason, transfer_wd.is_tron, transfer_wd.status')
            ->where('transfer_wd.id', '=', $id)
            ->orderBy('transfer_wd.id', 'DESC')
            ->where('transfer_wd.is_tron', '=', 1)
            ->first();
        return $sql;
    }

    public function getIDKonversiWDeIDR($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, transfer_wd.user_id, users.tron, '
                . 'transfer_wd.wd_total')
            ->where('transfer_wd.id', '=', $id)
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.type', '=', 5)
            ->where('transfer_wd.reason', '=', null)
            ->where('transfer_wd.is_tron', '=', 1)
            ->first();
        return $sql;
    }

    public function getIDRequestWDRoyalti($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, users.full_name,'
                . 'transfer_wd.reason, transfer_wd.status')
            ->where('transfer_wd.id', '=', $id)
            ->where('transfer_wd.type', '=', 3)
            ->orderBy('transfer_wd.id', 'DESC')
            ->first();
        return $sql;
    }

    public function getIDWDRoyaltiByeIDR($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, transfer_wd.user_id, users.tron, users.user_code,'
                . 'transfer_wd.wd_total')
            ->where('transfer_wd.id', '=', $id)
            ->where('transfer_wd.type', '=', 3)
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.is_tron', '=', 1)
            ->where('transfer_wd.reason', '=', null)
            ->first();
        return $sql;
    }

    public function getIDRequestWDRejectRoyalti($id)
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, users.full_name,'
                . 'transfer_wd.reason, transfer_wd.status, transfer_wd.is_tron')
            ->where('transfer_wd.id', '=', $id)
            ->where('transfer_wd.type', '=', 3)
            ->orderBy('transfer_wd.id', 'DESC')
            ->first();
        return $sql;
    }

    public function getAllHistoryWD()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->join('users as u', 'transfer_wd.submit_by', '=', 'u.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, transfer_wd.status,  '
                . 'transfer_wd.reason, transfer_wd.submit_by, u.name')
            ->where('transfer_wd.type', '=', 1)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllHistoryWDeIDR()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('users as u', 'transfer_wd.submit_by', '=', 'u.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, '
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, transfer_wd.status,  '
                . 'transfer_wd.reason, transfer_wd.submit_by, u.name')
            ->orderBy('transfer_wd.id', 'DESC')
            ->where('transfer_wd.is_tron', '=', 1)
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllHistoryWDRoyalti()
    {
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->join('users as u', 'transfer_wd.submit_by', '=', 'u.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee, transfer_wd.status,  '
                . 'transfer_wd.reason, transfer_wd.submit_by, transfer_wd.is_tron, u.name')
            ->whereIn('transfer_wd.type', [3, 6])
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestWDYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->whereDate('transfer_wd.wd_date', '=', $yesterday)
            ->where('transfer_wd.type', '=', 1)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestWDeIDRYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, users.tron, '
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.is_tron', '=', 1)
            ->whereDate('transfer_wd.wd_date', '=', $yesterday)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAllRequestWDRoyaltiYesterday()
    {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql = DB::table('transfer_wd')
            ->join('users', 'transfer_wd.user_id', '=', 'users.id')
            ->join('bank', 'transfer_wd.user_bank', '=', 'bank.id')
            ->selectRaw('transfer_wd.id, users.user_code, users.hp, bank.bank_name, bank.account_no, bank.account_name,'
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee')
            ->where('transfer_wd.status', '=', 0)
            ->where('transfer_wd.type', '=', 3)
            ->whereDate('transfer_wd.wd_date', '=', $yesterday)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getTotalDiTransferAll()
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getTotalDiTransferAllLastMonth($date)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total else 0 end) as total_wd, '
                . 'sum(case when status = 0 then wd_total else 0 end) as total_tunda,'
                . 'sum(case when status = 2 then wd_total else 0 end) as  total_cancel,'
                . 'sum(case when status IN (0, 1) then admin_fee else 0 end) as total_fee_admin,'
                . 'sum(case when status = 1 then admin_fee else 0 end) as fee_tuntas,'
                . 'sum(case when status = 0 then admin_fee else 0 end) as fee_tunda')
            ->whereDate('wd_date', '>=', $date->start_day)
            ->whereDate('wd_date', '<=', $date->end_day)
            ->first();
        $total_wd = 0;
        if ($sql->total_wd != null) {
            $total_wd = $sql->total_wd;
        }
        $total_tunda = 0;
        if ($sql->total_tunda != null) {
            $total_tunda = $sql->total_tunda;
        }
        $total_cancel = 0;
        if ($sql->total_cancel != null) {
            $total_cancel = $sql->total_cancel;
        }
        $total_fee_admin = 0;
        if ($sql->total_fee_admin != null) {
            $total_fee_admin = $sql->total_fee_admin;
        }
        $fee_tuntas = 0;
        if ($sql->fee_tuntas != null) {
            $fee_tuntas = $sql->fee_tuntas;
        }
        $fee_tunda = 0;
        if ($sql->fee_tunda != null) {
            $fee_tunda = $sql->fee_tunda;
        }
        $return = (object) array(
            'total_wd' => $total_wd,
            'total_tunda' => $total_tunda,
            'total_cancel' => $total_cancel,
            'total_fee_admin' => $total_fee_admin,
            'fee_tuntas' => $fee_tuntas,
            'fee_tunda' => $fee_tunda
        );
        return $return;
    }

    public function getAllMemberWDeIDR($data)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('transfer_wd.id, '
                . 'transfer_wd.wd_code, transfer_wd.wd_total, transfer_wd.wd_date, transfer_wd.admin_fee,'
                . 'transfer_wd.status, transfer_wd.reason, transfer_wd.wd_date')
            ->where('transfer_wd.type', '=', 5)
            ->where('transfer_wd.is_tron', '=', 1)
            ->where('transfer_wd.user_id', '=', $data->id)
            ->orderBy('transfer_wd.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }
}
