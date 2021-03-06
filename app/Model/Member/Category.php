<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['royalty'];

    public function product()
    {
        return $this->belongsTo('App\Model\Member\Product', 'id', 'category_id');
    }
}
