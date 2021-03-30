<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusRoyalty extends Model
{
    protected $fillable = ['user_id', 'from_user_id', 'amount', 'level_id', 'bonus_date', 'status', 'hash'];
}
