<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'master_sales';

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
        return $this->hasMany('App\OrderDetail', 'master_sales_id', 'id');
    }
}
