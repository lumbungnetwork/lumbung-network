<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transferwd;
use App\Model\Bonus;


class CronPushNotifWdRoyalti extends Command {

    protected $signature = 'push_notif_royalti';
    protected $description = 'Cron harian Notifikasi Request WD Royalti from Member';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWDRoyaltiYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.push_notif_royalti', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request WD Royalti Member '.$yesterday.' Lumbung Network')
                    ->subject('Data Request WD Royalti Member '.$yesterday.' Lumbung Network');
        });
    }
    
    
    
    
}
