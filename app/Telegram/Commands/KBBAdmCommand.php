<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use Illuminate\Support\Facades\Cache;
use App\Model\Member;
use App\User;

/**
 * Class HelpCommand.
 */
class KBBAdmCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'adm';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['a'];

    /**
     * @var string Command Description
     */
    protected $description = 'Adminstratif KBB Commands';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $response = $this->getUpdate();
        $chat_id = $response->getMessage()->getChat()->getId();
        $whitelist = [1049542881, 365874331, -418820766];
        $textContent = $response->getMessage()->getText();

        $modelMember = new Member;

        if (!in_array($chat_id, $whitelist)) {
            $text = 'unauthorized access!';
            $this->replyWithMessage(compact('text'));
            return;
        } else {
            $params = explode(' ', $textContent);
            if (!isset($params[1])) {
                $text = 'Perintah tidak lengkap! (param1)';
                $this->replyWithMessage(compact('text'));
                return;
            } elseif (!isset($params[2])) {
                $text = 'Perintah tidak lengkap! (param2)';
                $this->replyWithMessage(compact('text'));
                return;
            }

            if ($params[2] == 'mengajak') {
                $host = User::where('user_code', $params[1])
                    ->whereIn('affiliate', [2, 3])
                    ->where('is_active', 1)
                    ->first();

                if ($host == null) {
                    $text = 'Username yang *mengajak* tidak terdaftar atau bukan member KBB';
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                    return;
                }

                $guest = User::where('user_code', $params[3])
                    ->whereIn('affiliate', [2, 3])
                    ->where('is_active', 1)
                    ->first();

                if ($guest == null) {
                    $text = 'Username yang *diajak* tidak terdaftar atau bukan member KBB';
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                    return;
                }

                if ($guest->invited_by != null) {
                    $text = 'Akun yang hendak diajak, sudah terdaftar diajak akun lain!';
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                    return;
                } else {
                    $guest->invited_by = $host->id;
                    $guest->save();

                    $hostInviteCount = $modelMember->getInviteCount($host->id);
                    if (count($hostInviteCount) > 3) {
                        $host->affiliate = 3;
                        $host->save();
                    }
                    $text = 'Akun ' . $host->user_code . ' telah *berhasil mengajak* ' . $guest->user_code;
                    $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                    return;
                }
            } else {
                $text = 'Perintah salah, periksa kembali';
                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            }
        }
    }
}
