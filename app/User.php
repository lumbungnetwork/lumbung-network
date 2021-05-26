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

    public function sellerProfile()
    {
        return $this->hasOne('App\SellerProfile', 'seller_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Model\Member\Product', 'seller_id');
    }

    public function localWallet()
    {
        return $this->hasOne('App\LocalWallet');
    }

    public function routeNotificationForTelegram()
    {
        return $this->chat_id;
    }

    public function bank()
    {
        return $this->hasOne('App\Model\Member\Bank');
    }
}
