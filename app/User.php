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
        'name', 'email', 'password', 'username', 'sponsor_id'
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
        return $this->hasOne('App\Model\Member\SellerProfile', 'seller_id', 'id');
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

    /**
     * This is a function to get binary tree data under a user_id.
     * Using member_type > 0 as a constraint to query only premium Member (or Premiumed)
     *
     * @var User id
     */
    public function getBinary($user_id)
    {
        $node1 = $this->where('id', $user_id)->where('member_type', '>', 0)->first();
        $node2 = $node3 = $node4 = $node5 = $node6 = $node7 = null;

        // 1st level
        if ($node1->left_id) {
            $node2 = $this->where('id', $node1->left_id)->where('member_type', '>', 0)->first();
        }

        if ($node1->right_id) {
            $node3 = $this->where('id', $node1->right_id)->where('member_type', '>', 0)->first();
        }

        // 2nd level
        if ($node2 && $node2->left_id) {
            $node4 = $this->where('id', $node2->left_id)->where('member_type', '>', 0)->first();
        }

        if ($node2 && $node2->right_id) {
            $node5 = $this->where('id', $node2->right_id)->where('member_type', '>', 0)->first();
        }
        if ($node3 && $node3->left_id) {
            $node6 = $this->where('id', $node3->left_id)->where('member_type', '>', 0)->first();
        }

        if ($node3 && $node3->right_id) {
            $node7 = $this->where('id', $node3->right_id)->where('member_type', '>', 0)->first();
        }

        return [$node1, $node2, $node3, $node4, $node5, $node6, $node7];
    }
}
