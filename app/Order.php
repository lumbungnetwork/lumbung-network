<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'master_sales';

    protected $guarded = ['id'];
}
