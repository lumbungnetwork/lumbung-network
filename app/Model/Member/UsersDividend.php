<?php

namespace App\Model\Member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDividend extends Model
{
    use HasFactory;

    protected $table = 'users_dividend';

    public function getUserDividend($id)
    {
        $div = $this->selectRaw('
                        sum(case when type = 0 then amount else 0 end) as claimed,
                        sum(case when type = 1 then amount else - amount end) as net
                    ')->where('user_id', $id)
                    ->first();
        return (object) [
            'claimed' => round($div->claimed, 2),
            'net' => round($div->net, 2) 
        ];
    }
}
