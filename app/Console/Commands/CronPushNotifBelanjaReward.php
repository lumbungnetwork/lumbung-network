<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Transferwd;
use App\Model\Bonus;


class CronPushNotifBelanjaReward extends Command {

    protected $signature = 'push_notif_belanja';
    protected $description = 'Cron harian Notifikasi Request Belanja Reward from Member';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $modelBonus = new Bonus;
        $getData = $modelBonus->getAdminAllBelanjaRewardYesterday();
        $dataEmail = array(
            'dataEmail' => $getData
        );
//        dd($dataEmail['dataEmail']);
        $emailSend = 'noreply@lumbung.network';
        Mail::send('member.email.push_notif_belanja_reward', $dataEmail, function($message) use($emailSend){
            $yesterday = date('d F Y',strtotime("-1 days"));
            $message->to($emailSend, 'Data Request Claim Belanja Reward Member '.$yesterday.' Lumbung Network')
                    ->subject('Data Request Claim Belanja Reward Member '.$yesterday.' Lumbung Network');
        });
    }
    
    
    
    
}
