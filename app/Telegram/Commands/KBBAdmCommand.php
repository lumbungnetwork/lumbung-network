<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use Illuminate\Support\Facades\Cache;
use App\Model\Member;
use App\User;
use App\Jobs\KBBLiquidateBonus;
use App\KbbBonus;

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
            } elseif ($params[1] == 'cek') {
                $query = User::where('user_code', 'LIKE', '%' . $params[2] . '%')->where('is_active', 1)->select('user_code')->get();
                $text = 'Berikut beberapa username yang serupa dengan: ' . $params[2] . chr(10) . chr(10);

                foreach ($query as $row) {
                    $text .= $row->user_code . chr(10);
                }
                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            } elseif ($params[1] == 'reset-pass') {
                $query = User::where('user_code', $params[2])->first();
                if ($query == null) {
                    $text = 'User tidak ditemukan';
                } else {
                    $query->password = bcrypt('QWERTASD123a');
                    $query->save();
                    $text = 'Password berhasil direset menjadi QWERTASD123a';
                }

                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            } elseif ($params[1] == 'reset-invite') {
                $query = User::where('user_code', $params[2])->first();
                if ($query == null) {
                    $text = 'User tidak ditemukan';
                } else {
                    $query->invited_by = null;
                    $query->save();
                    $text = 'Data invited telah direset';
                }

                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            } elseif ($params[1] == 'liquidate') {
                $query = User::where('user_code', $params[2])->select('id')->first();
                if ($query == null) {
                    $text = 'User tidak ditemukan';
                } else {
                    KBBLiquidateBonus::dispatchNow($query->id);
                    sleep(7);
                    $results = KbbBonus::where('user_id', $query->id)
                        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 minutes')))
                        ->select('amount', 'type')->get();
                    $text = '';

                    if (count($results) < 1) {
                        $text = 'nada';
                        $this->replyWithMessage(['text' => $text]);
                        return;
                    }
                    foreach ($results as $result) {
                        $text .= 'Rp' . $result->amount . ' (' . $result->type . ')' . chr(10);
                    }
                    $this->replyWithMessage(['text' => $text]);
                    return;
                }
            } else {
                $text = 'Perintah salah, periksa kembali';
                $this->replyWithMessage(['text' => $text]);
                return;
            }
        }
    }
}
