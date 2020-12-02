<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orderBuyStockist()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }

    public function orderSellStockist()
    {
        return $this->hasMany('App\Order', 'stockist_id', 'id');
    }

    public function sellerProfile()
    {
        return $this->hasOne('App\SellerProfile', 'seller_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'seller_id');
    }
}
