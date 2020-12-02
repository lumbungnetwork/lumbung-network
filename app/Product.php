<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function orderDetail()
    {
        return $this->hasMany('App\OrderDetail', 'purchase_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo('App\User', 'seller_id');
    }

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function getProductImage($product_name)
    {
        return DB::table('product_images')
            ->where('name', 'LIKE', '%' . $product_name . '%')
            ->orderBy('name', 'ASC')
            ->get();
    }
}
