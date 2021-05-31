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

    public function getCodeRef($type)
    {
        $charsetlower = "abcdefghijklmnopqrstuvwxyz";
        $key_lower = '';
        for ($i = 0; $i < 3; $i++) {
            $key_lower .= $charsetlower[(mt_rand(0, strlen($charsetlower) - 1))];
        }

        $charsetnumber = "1234567890";
        $key_number = '';
        for ($i = 0; $i < 3; $i++) {
            $key_number .= $charsetnumber[(mt_rand(0, strlen($charsetnumber) - 1))];
        }

        $charset = $key_lower . $key_number;
        $rand = str_shuffle($charset);

        $getTransCount = $this->select('id')
            ->where('type', '=', $type)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();
        $tmp = $getTransCount + 1;
        $code = sprintf("%03s", $tmp);
        return 'ref_' . $type . '_' . $code . '_' . date('Ymd') . $rand;
    }
}
