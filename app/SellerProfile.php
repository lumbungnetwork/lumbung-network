<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    protected $guarded = [];

    public function seller()
    {
        return $this->belongsTo('App\User');
    }
}
