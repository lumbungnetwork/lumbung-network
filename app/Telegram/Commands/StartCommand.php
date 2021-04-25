<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use Illuminate\Support\Facades\Cache;
use App\Model\Member;
use App\Finance;

/**
 * Class HelpCommand.
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['s'];

    /**
     * @var string Command Description
     */
    protected $description = 'Start linking the bot to user';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $response = $this->getUpdate();
        $chat_id = $response->getMessage()->getChat()->getId();
        $textContent = $response->getMessage()->getText();

        $text = 'Selamat Datang di Layanan Bot Lumbung Network!' . chr(10);
        $text = 'Welcome to Lumbung Network Bot Service!' . chr(10) . chr(10);

        if (strlen($textContent) < 17) {
            $text .= 'Untuk menautkan akun anda dengan fitur notifikasi dari Bot ini, silakan login ke akun anda dari browser, dan tekan tombol Tautkan Telegram dari menu Akun. ';
        } elseif (substr($textContent, 7, 7) == 'finance') {
            $key = substr($textContent, 14);
            if (Cache::has($key)) {
                $user_id = Cache::pull($key);

                $user = Finance::find($user_id);
                $user->chat_id = $chat_id;
                $user->save();

                $text .= 'Account successfully linked!';
            } else {
                $text .= 'Something is wrong!' . chr(10) . chr(10);
                $text .= 'If you trying to link your Lumbung Finance account, do it from your account.';
            }
        } else {
            $key = substr($textContent, 7);
            if (Cache::has($key)) {
                $user_id = Cache::pull($key);
                $data = ['chat_id' => $chat_id];

                $modelMember = new Member;
                $modelMember->getUpdateUsers('id', $user_id, $data);
                $text .= 'Akun anda telah berhasil ditautkan!';
            } else {
                $text .= 'Ada yang salah!' . chr(10) . chr(10);
                $text .= 'Bila anda mencoba mentautkan akun Lumbung Network dengan Telegram, silakan melakukannya dari menu Akun.';
            }
        }

        $this->replyWithMessage(compact('text'));
        return;
    }
}
