<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Config;

class protest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $sql = DB::table('transfer_wd')
            ->join('users', 'users.id', '=', 'transfer_wd.user_id')
            ->join('bank', 'bank.user_id', '=', 'transfer_wd.user_id')
            ->select('users.user_code', 'transfer_wd.wd_total', 'bank.bank_name', 'bank.account_no', 'bank.account_name')
            ->where('transfer_wd.id', '>=', 1237)
            ->where('transfer_wd.id', '<=', 1250)
            ->get();
        foreach ($sql as $row) {
            $message_text = $row->user_code . chr(10);
            $message_text .= 'Amount: ' . $row->wd_total . chr(10);
            $message_text .= 'Bank: ' . $row->bank_name . chr(10);
            $message_text .= 'Acc No.: ' . $row->account_no . chr(10);
            $message_text .= 'Acc Name: ' . $row->account_name . chr(10);

            Telegram::sendMessage([
                'chat_id' => Config::get('services.telegram.overlord'),
                'text' => $message_text,
                'parse_mode' => 'markdown'
            ]);

            sleep(4);
        }
        return;
    }
}
