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

    /**
     * This is a function to get 7 levels sponsors above a user_id.
     * Using LeftJoin with table 'users' to each sponsor_id
     * @return Array of arrays with individual data (id, level, username and user_type)
     *
     * @param User id
     */
    public function get7LevelsSponsors($user_id)
    {
        $sql = $this->selectRaw('users.id, users.username, '
            . 'u1.id as id_lvl1, u1.username as username_lvl1, u1.user_type as user_type_lvl1,'
            . 'u2.id as id_lvl2, u2.username as username_lvl2, u2.user_type as user_type_lvl2,'
            . 'u3.id as id_lvl3, u3.username as username_lvl3, u3.user_type as user_type_lvl3,'
            . 'u4.id as id_lvl4, u4.username as username_lvl4, u4.user_type as user_type_lvl4,'
            . 'u5.id as id_lvl5, u5.username as username_lvl5, u5.user_type as user_type_lvl5,'
            . 'u6.id as id_lvl6, u6.username as username_lvl6, u6.user_type as user_type_lvl6,'
            . 'u7.id as id_lvl7, u7.username as username_lvl7, u7.user_type as user_type_lvl7')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->leftJoin('users as u2', 'u1.sponsor_id', '=', 'u2.id')
            ->leftJoin('users as u3', 'u2.sponsor_id', '=', 'u3.id')
            ->leftJoin('users as u4', 'u3.sponsor_id', '=', 'u4.id')
            ->leftJoin('users as u5', 'u4.sponsor_id', '=', 'u5.id')
            ->leftJoin('users as u6', 'u5.sponsor_id', '=', 'u6.id')
            ->leftJoin('users as u7', 'u6.sponsor_id', '=', 'u7.id')
            ->where('users.id', '=', $user_id)
            ->where('users.user_type', '=', 10)
            ->first();

        $return = [];
        $i = 1;
        do {
            $_id = 'id_lvl' . $i;
            $_username = 'username_lvl' . $i;
            $_usertype = 'user_type_lvl' . $i;
            $return[] = (object) [
                'level' => $i,
                'id' => $sql->$_id ?? null,
                'username' => $sql->$_username ?? null,
                'user_type' => $sql->$_usertype ?? null
            ];
            $i++;
        } while ($i < 8);

        return $return;
    }
}
