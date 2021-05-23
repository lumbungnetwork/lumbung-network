<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MasterSales extends Model
{
    use HasFactory;

    public function sales()
    {
        return $this->hasMany('App\Model\Member\Sales', 'master_sales_id', 'id');
    }

    public function getMemberSpending($user_id, $month, $year)
    {
        // Get UNIX timestamp from month and year, set to mySQL date
        $range = strtotime($year . '-' . $month);
        $date = (object) [
            'startDay' => date('Y-m-01', $range),
            'endDay' => date('Y-m-t', $range)
        ];
        // Query with date constraint
        // Physical Products
        $physical = DB::table('master_sales')
            ->selectRaw('SUM(master_sales.total_price) AS total')
            ->where('status', 2)
            ->where('user_id', $user_id)
            ->whereDate('sale_date', '>=', $date->startDay)
            ->whereDate('sale_date', '<=', $date->endDay)
            ->whereNull('deleted_at')
            ->first();

        // Digital Products
        $digital = DB::table('ppob')
            ->selectRaw('SUM(CASE WHEN type <= 2 THEN ppob_price ELSE 0 END) AS pulsaData,'
                . 'SUM(CASE WHEN type > 2 THEN 2500 ELSE 0 END) AS payment')
            ->where('status', 2)
            ->where('user_id', $user_id)
            ->whereDate('ppob_date', '>=', $date->startDay)
            ->whereDate('ppob_date', '<=', $date->endDay)
            ->whereNull('deleted_at')
            ->first();
        $totalDigital = $digital->pulsaData + $digital->payment;

        // return object
        return (object) [
            'physical' => (float) $physical->total,
            'digital' => (float) $totalDigital,
            'total' => $physical->total + $totalDigital
        ];
    }

    public function getCodeMasterSales($id)
    {
        $getTransCount = DB::table('master_sales')->selectRaw('id')->whereDate('created_at', date('Y-m-d'))->count();
        $tmp = $getTransCount + 1;
        $code = 'Cart_' . date('Ymds') . $id . sprintf("%04s", $tmp);
        return $code;
    }
}
