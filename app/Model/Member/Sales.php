<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo('App\Model\Member\Product', 'purchase_id', 'id');
    }

    public function masterSales()
    {
        return $this->belongsTo('App\Model\Member\MasterSales', 'master_sales_id', 'id');
    }
}
