<?php

namespace App;

use App\Notifications\FinanceResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Finance extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'sponsor_id'
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new FinanceResetPassword($token));
    }

    public function loginSecurity()
    {
        return $this->hasOne('App\LoginSecurity', 'user_id', 'id');
    }

    public function USDTbalance()
    {
        return $this->hasMany('App\Model\Finance\USDTbalance', 'user_id', 'id');
    }

    public function credit()
    {
        return $this->hasMany('App\Model\Finance\Credit', 'user_id', 'id');
    }

    public function contract()
    {
        return $this->hasMany('App\Model\Finance\Contract', 'user_id', 'id');
    }
}
