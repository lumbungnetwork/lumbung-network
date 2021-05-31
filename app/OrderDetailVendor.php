<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailVendor extends Model
{
    protected $table = 'vsales';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Model\Member\Product', 'purchase_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'vmaster_sales_id', 'id');
    }
}
