<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckClaimedKBBMemberBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:bonuscheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send complete details of claimed KBB Member bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sql = DB::table('kbb_bonuses')
            ->join('users', 'users.id', '=', 'kbb_bonuses.user_id')
            ->join('bank', 'bank.user_id', '=', 'kbb_bonuses.user_id')
            ->select('users.user_code', 'kbb_bonuses.amount', 'bank.bank_name', 'bank.account_no', 'bank.account_name')
            ->whereDate('kbb_bonuses.created_at', '>=', date('Y-m-01'))
            ->get();
        if (count($sql) == 0) {
            echo "No available bonus, yet" . PHP_EOL;
            return;
        }
        foreach ($sql as $row) {
            $message_text = $row->user_code . chr(10);
            $message_text .= 'Amount: Rp' . number_format($row->amount) . chr(10);
            $message_text .= 'Bank: ' . $row->bank_name . chr(10);
            $message_text .= 'Acc No.: ' . $row->account_no . chr(10);
            $message_text .= 'Acc Name: ' . $row->account_name . chr(10);

            Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => $message_text,
                'parse_mode' => 'markdown'
            ]);
        }
        return;
    }
}
