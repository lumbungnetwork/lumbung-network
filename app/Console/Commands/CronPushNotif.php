<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transferwd;
use App\Model\Bonus;


class CronPushNotif extends Command {

    protected $signature = 'push_notif';
    protected $description = 'Cron harian Notifikasi Request WD from Member';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelWD = new Transferwd;
        $getData = $modelWD->getAllRequestWDYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'chairil.hakim@domikado.com';
        Mail::send('member.email.push_notif', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request WD Member '.$yesterday.' Lumbung Network')
                    ->subject('Data Request WD Member '.$yesterday.' Lumbung Network');
        });
    }
    
    
    
    
}
