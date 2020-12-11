<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderVendor extends Model
{
    use SoftDeletes;

    protected $table = 'vmaster_sales';

    protected $guarded = ['status', 'deleted_at'];

    public function buyer()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\User', 'vendor_id');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail', 'vmaster_sales_id', 'id');
    }
}
