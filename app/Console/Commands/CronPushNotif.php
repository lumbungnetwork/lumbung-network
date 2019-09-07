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
        $getData = $modelWD->getAllRequestWD();
        $dataEmail = array(
            'dataEmail' => $getData
        );
        dd($dataEmail);
        $emailSend = 'chairil.ptmgahama@gmail.com';
        Mail::send('member.email.push_notif', $dataEmail, function($message) use($emailSend){
            $message->to($emailSend, 'Data Request WD Member '.date('d F Y').' Lumbung Network')
                    ->subject('Data Request WD Member '.date('d F Y').' Lumbung Network');
        });
    }
    
    
    
    
}
