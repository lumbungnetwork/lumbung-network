<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalSale extends Model
{
    use HasFactory;

    protected $table = 'ppob';

    public function seller()
    {
        return $this->belongsTo('App\User', 'vendor_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
