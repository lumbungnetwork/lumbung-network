<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['royalty'];

    public function product()
    {
        return $this->belongsTo('App\Product', 'id', 'category_id');
    }
}
