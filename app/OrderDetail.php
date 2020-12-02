<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'sales';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Product', 'purchase_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'master_sales_id');
    }
}
