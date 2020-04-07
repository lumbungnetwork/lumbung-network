<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Bonus;


class CrontPushNotifTopUpEidr extends Command {

    protected $signature = 'push_notif_topup_tron';
    protected $description = 'Cron harian Notifikasi Top Up eIDR';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelBonus = New Bonus;
        $getData = $modelBonus->getAllRequestTopupYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.push_notif_topup_eidr', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request TopUp eIDR Member '.$yesterday.' Lumbung Network')
                    ->subject('Data Request TopUp eIDR Member '.$yesterday.' Lumbung Network');
        });
    }
    
}
