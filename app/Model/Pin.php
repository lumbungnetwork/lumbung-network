<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class Pin extends Model
{

    public function getInsertMemberPin($data)
    {
        try {
            $lastInsertedID = DB::table('member_pin')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateMemberPin($fieldName, $name, $data)
    {
        try {
            DB::table('member_pin')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getTotalPinAdmin()
    {
        $sql = DB::table('master_pin')
            ->selectRaw('
		sum(case when type_pin = 1 then total_pin end) as sum_pin_masuk,
		sum(case when type_pin = 2 then total_pin end) as sum_pin_keluar
                    ')
            ->first();
        return $sql;
    }

    public function getTotalPinMember($data)
    {
        $sql = DB::table('member_pin')
            ->selectRaw('
		sum(case when is_used = 0 then total_pin else 0 end) as sum_pin_masuk,
		sum(case when is_used = 1 then total_pin else 0 end) as sum_pin_keluar,
		sum(case when pin_status = 1 then total_pin else 0 end) as sum_pin_terpakai,
		sum(case when pin_status = 2 then total_pin else 0 end) as sum_pin_transfer
                    ')
            ->where('user_id', '=', $data->id)
            ->first();
        return $sql;
    }

    public function getMyLastPin($data)
    {
        $sql = DB::table('member_pin')
            ->selectRaw('setting_pin, pin_code')
            ->where('user_id', '=', $data->id)
            ->where('is_used', '=', 0)
            ->orderBy('id', 'DESC')
            ->first();
        return $sql;
    }

    public function getMyHistoryPin($data)
    {
        $sql = DB::table('member_pin')
            ->leftJoin('users as u1', 'member_pin.used_user_id', '=', 'u1.id')
            ->leftJoin('users as u2', 'member_pin.transfer_user_id', '=', 'u2.id')
            ->leftJoin('users as u3', 'member_pin.transfer_from_user_id', '=', 'u3.id')
            ->selectRaw('member_pin.total_pin, member_pin.pin_status, member_pin.is_used, member_pin.used_at, '
                . 'member_pin.created_at, member_pin.transaction_code, '
                . 'u1.name as name_activation, '
                . 'u2.name as name_transfer_to, '
                . 'u3.name as name_transfer_from')
            ->where('member_pin.user_id', '=', $data->id)
            ->orderBy('member_pin.id', 'DESC')
            ->get();
        return $sql;
    }

    public function getMyTotalPinPengiriman($data)
    {
        $sql = DB::table('member_pin')
            ->selectRaw('sum(total_pin) as pin_tersedia')
            ->where('user_id', '=', $data->id)
            ->where('is_used', '=', 0)
            ->first();
        return $sql;
    }

    public function getCheckMaxPinROByDate($sp_id, $startDate, $endDate)
    {
        $sql = DB::table('member_pin')
            ->leftJoin('users', 'member_pin.user_id', '=', 'users.id')
            ->selectRaw('sum(member_pin.total_pin) as total_pin_ro')
            ->where('users.sponsor_id', '=', $sp_id)
            ->whereDate('member_pin.used_at', '>=', $startDate)
            ->whereDate('member_pin.used_at', '<', $endDate)
            ->where('member_pin.is_used', '=', 1)
            ->where('member_pin.is_ro', '=', 1)
            ->first();
        $return = 0;
        if ($sql->total_pin_ro != null) {
            $return = $sql->total_pin_ro;
        }
        return $return;
    }

    public function getInsertMemberDeposit($data)
    {
        try {
            $lastInsertedID = DB::table('member_deposito')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateMemberDeposit($fieldName, $name, $data)
    {
        try {
            DB::table('member_deposito')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getDeleteMemberDeposit($id)
    {
        try {
            DB::table('member_deposito')
                ->where('id', '=', $id)
                ->delete();
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getMyHistoryDeposit($data)
    {
        $sql = DB::table('member_deposito')
            ->selectRaw('member_deposito.total_deposito, member_deposito.deposito_status,  '
                . 'member_deposito.created_at, member_deposito.transaction_code')
            ->where('member_deposito.user_id', '=', $data->id)
            ->orderBy('member_deposito.id', 'DESC')
            ->get();
        return $sql;
    }

    public function getJagaGaBolehDuaKali($trans_code)
    {
        $sql = DB::table('member_deposito')
            ->selectRaw('id')
            ->where('transaction_code', '=', $trans_code)
            ->first();
        return $sql;
    }

    public function getTotalDepositMember($data)
    {
        $sql = DB::table('member_deposito')
            ->selectRaw('
		sum(case when deposito_status = 0 then total_deposito else 0 end) as sum_deposit_masuk,
		sum(case when deposito_status in (1, 2) then total_deposito else 0 end) as sum_deposit_keluar
                    ')
            ->where('user_id', '=', $data->id)
            ->first();
        return $sql;
    }

    public function getVendorDepositBalance($vendor_id)
    {
        $sql = DB::table('member_deposito')
            ->selectRaw('
		sum(case when deposito_status = 0 then total_deposito else 0 end) as credits,
		sum(case when deposito_status in (1, 2) then total_deposito else 0 end) as debits
                    ')
            ->where('user_id', '=', $vendor_id)
            ->first();
        return $sql;
    }

    public function getTotalDepositAll()
    {
        $sql = DB::table('member_deposito')
            ->selectRaw('
		sum(case when deposito_status = 0 then total_deposito else 0 end) as sum_deposit_masuk,
		sum(case when deposito_status in (1, 2) then total_deposito else 0 end) as sum_deposit_keluar
                    ')
            ->first();
        $deposit_masuk = 0;
        $deposit_keluar = 0;
        if ($sql->sum_deposit_masuk != null) {
            $deposit_masuk = $sql->sum_deposit_masuk;
        }
        if ($sql->sum_deposit_keluar != null) {
            $deposit_keluar = $sql->sum_deposit_keluar;
        }
        $return = (object) array(
            'sum_deposit_masuk' => $deposit_masuk,
            'sum_deposit_keluar' => $deposit_keluar
        );
        return $return;
        return $sql;
    }

    public function getInsertMasterDeposit($data)
    {
        try {
            $lastInsertedID = DB::table('master_deposit')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdateMasterDeposit($fieldName, $name, $data)
    {
        try {
            DB::table('master_deposit')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getTotalMasterDepositAll()
    {
        $sql = DB::table('master_deposit')
            ->selectRaw('
		sum(case when type_deposit = 1 then total_deposit else 0 end) as sum_deposit_masuk,
		sum(case when type_deposit in (2, 3) then total_deposit else 0 end) as sum_deposit_keluar
                    ')
            ->first();
        $deposit_masuk = 0;
        $deposit_keluar = 0;
        if ($sql->sum_deposit_masuk != null) {
            $deposit_masuk = $sql->sum_deposit_masuk;
        }
        if ($sql->sum_deposit_keluar != null) {
            $deposit_keluar = $sql->sum_deposit_keluar;
        }
        $return = (object) array(
            'sum_deposit_masuk' => $deposit_masuk,
            'sum_deposit_keluar' => $deposit_keluar
        );
        return $return;
    }

    public function getCodeTransactionSystem()
    {
        $getTransCount = DB::table('master_deposit')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = sprintf("%04s", $tmp);
        return 'TS' . $code . '_' . date('Ymd');
    }

    public function getInsertPPOB($data)
    {
        try {
            $lastInsertedID = DB::table('ppob')->insertGetId($data);
            $result = (object) array('status' => true, 'message' => null, 'lastID' => $lastInsertedID);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message, 'lastID' => null);
        }
        return $result;
    }

    public function getUpdatePPOB($fieldName, $name, $data)
    {
        try {
            DB::table('ppob')->where($fieldName, '=', $name)->update($data);
            $result = (object) array('status' => true, 'message' => null);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $result = (object) array('status' => false, 'message' => $message);
        }
        return $result;
    }

    public function getCodePPOBRef($type)
    {
        $charsetlower = "abcdefghijklmnopqrstuvwxyz";
        $key_lower = '';
        for ($i = 0; $i < 3; $i++) {
            $key_lower .= $charsetlower[(mt_rand(0, strlen($charsetlower) - 1))];
        }

        $charsetnumber = "1234567890";
        $key_number = '';
        for ($i = 0; $i < 3; $i++) {
            $key_number .= $charsetnumber[(mt_rand(0, strlen($charsetnumber) - 1))];
        }

        $charset = $key_lower . $key_number;
        $rand = str_shuffle($charset);

        $getTransCount = DB::table('ppob')
            ->selectRaw('id')
            ->where('type', '=', $type)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();
        $tmp = $getTransCount + 1;
        $code = sprintf("%03s", $tmp);
        return 'ref_' . $type . '_' . $code . '_' . date('Ymd') . $rand;
    }

    public function getMemberHistoryPPOB($id, $date)
    {
        $sql = DB::table('ppob')
            ->join('users', 'ppob.vendor_id', '=', 'users.id')
            ->selectRaw('ppob.ppob_date, users.user_code, ppob.ppob_price as sale_price, ppob.type, '
                . 'ppob.id, ppob.status, ppob.buy_metode, ppob.message, ppob.vendor_id')
            ->where('ppob.user_id', '=', $id)
            ->whereDate('ppob.ppob_date', '>=', $date->startDay)
            ->whereDate('ppob.ppob_date', '<=', $date->endDay)
            ->whereNull('ppob.deleted_at')
            ->orderBy('ppob.ppob_date', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getVendorTransactionPPOB($id)
    {
        $sql = DB::table('ppob')
            ->join('users', 'ppob.user_id', '=', 'users.id')
            ->selectRaw('ppob.*, users.user_code, users.email')
            ->where('ppob.vendor_id', '=', $id)
            //                    ->where('ppob.status', '=', 1)
            ->orderBy('ppob.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminTransactionPPOBEiDR()
    {
        $sql = DB::table('ppob')
            ->join('users as a', 'ppob.user_id', '=', 'a.id')
            ->join('users as b', 'ppob.vendor_id', '=', 'b.id')
            ->selectRaw('ppob.*, a.user_code as user_code_pembeli, b.user_code user_code_vendor')
            ->where('ppob.status', '=', 1)
            ->where('ppob.buy_metode', '=', 3)
            ->orderBy('ppob.id', 'DESC')
            ->get();
        $return = null;
        if (count($sql) > 0) {
            $return = $sql;
        }
        return $return;
    }

    public function getAdminDetailTransactionPPOBEiDR($id)
    {
        $sql = DB::table('ppob')
            ->join('users as a', 'ppob.user_id', '=', 'a.id')
            ->join('users as b', 'ppob.vendor_id', '=', 'b.id')
            ->selectRaw('ppob.*, a.user_code as user_code_pembeli, b.user_code user_code_vendor')
            ->where('ppob.id', '=', $id)
            ->where('ppob.status', '=', 1)
            ->where('ppob.buy_metode', '=', 3)
            ->first();
        return $sql;
    }

    public function getMemberPembayaranPPOB($id, $data)
    {
        $sql = DB::table('ppob')
            ->where('ppob.id', '=', $id)
            ->where('ppob.user_id', '=', $data->id)
            ->whereNull('ppob.deleted_at')
            ->first();
        return $sql;
    }

    public function getJobExecutePPOB($masterSalesID)
    {
        $sql = DB::table('ppob')
            ->where('ppob.id', '=', $masterSalesID)
            ->where('ppob.status', '=', 1)
            ->whereNull('ppob.deleted_at')
            ->first();
        return $sql;
    }

    public function getJobPPOBAutoCancel($masterSalesID)
    {
        $sql = DB::table('ppob')
            ->where('ppob.id', '=', $masterSalesID)
            ->where('ppob.status', '<', 2)
            ->whereNull('ppob.deleted_at')
            ->first();
        return $sql;
    }

    public function checkUsedHashExist($hash, $table, $column)
    {
        $sql = DB::table($table)
            ->where($column, $hash)
            ->exists();
        return $sql;
    }

    public function getVendorPPOBDetail($id, $data)
    {
        $sql = DB::table('ppob')
            ->where('ppob.id', '=', $id)
            ->where('ppob.vendor_id', '=', $data->id)
            //                    ->whereNull('ppob.deleted_at')
            ->first();
        return $sql;
    }

    public function getStatusPPOBDetail($id)
    {
        $sql = DB::table('ppob')
            ->where('ppob.id', '=', $id)
            ->first();
        return $sql;
    }

    public function getCekHpOn10Menit($hp)
    {
        $date = date('Y-m-d H:i:s');
        $startTime = strtotime("-13 minutes", strtotime($date));
        $tenMinute = date('Y-m-d H:i:s', $startTime);
        $sql = DB::table('ppob')
            ->where('ppob.product_name', '=', $hp)
            ->where('ppob.created_at', '>', $tenMinute)
            ->whereNull('ppob.deleted_at')
            ->first();
        return $sql;
    }

    public function getPPOBFly($vendor_id)
    {
        $sql = DB::table('ppob')
            ->selectRaw('sum(harga_modal) as deposit_out')
            ->where('vendor_id', '=', $vendor_id)
            ->whereIn('status', array(0, 1))
            ->first();
        return $sql;
    }

    public function getPPOBFlyAll()
    {
        $sql = DB::table('ppob')
            ->selectRaw('sum(harga_modal) as deposit_out')
            ->whereIn('status', array(0, 1))
            ->first();
        return $sql;
    }
}
