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

    public function seller()
    {
        return $this->belongsTo('App\User', 'stockist_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /*
    * Get Member Spending or Selling on a monthly basis
    * @var $type 1 = Buying, 2 = Selling
    */
    public function getMemberSpending($type, $user_id, $month, $year)
    {
        // set selector for Buyer or Seller
        $physical_column = 'user_id';
        $digital_column = 'user_id';
        if ($type == 2) {
            $physical_column = 'stockist_id';
            $digital_column = 'vendor_id';
        }
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
            ->where($physical_column, $user_id)
            ->whereDate('sale_date', '>=', $date->startDay)
            ->whereDate('sale_date', '<=', $date->endDay)
            ->whereNull('deleted_at')
            ->first();

        // Digital Products
        $digital = DB::table('ppob')
            ->selectRaw('SUM(CASE WHEN type <= 2 THEN ppob_price ELSE 0 END) AS pulsaData,'
                . 'SUM(CASE WHEN type > 2 THEN 2500 ELSE 0 END) AS payment')
            ->where('status', 2)
            ->where($digital_column, $user_id)
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
