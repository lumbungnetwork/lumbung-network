<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Transferwd;
use App\User;
use App\Http\Controllers\Controller;

/**
 * Class KBBCommand.
 */
class KBBCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'kbb';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['k'];

    /**
     * @var string Command Description
     */
    protected $description = 'KBB related command';

    /**
     * @var string Command Pattern
     */
    protected $pattern = '{param} {username}';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $args = $this->getArguments();
        $response = $this->getUpdate();
        $controller = new Controller;

        if (empty($args)) {
            $text = 'Petunjuk penggunaan "perintah" Bot KBB yang tepat: .' . chr(10) . chr(10);
            $text .= 'Pergunakan parameter seperti "status" atau "bonus" diikuti dengan "username" yang ingin diperiksa.' . chr(10) . chr(10);
            $text .= 'Contoh: "/kbb status Budi001"' . chr(10) . chr(10);
            $text .= 'Parameter yang tersedia:' . chr(10);
            $text .= '1. _status_ (untuk melihat status suatu akun)' . chr(10);
            $text .= '2. _bonus_ (untuk melihat bonus suatu akun)' . chr(10);
            $text .= '3. _sponsoring_ (untuk melihat daftar akun yang disponsori oleh suatu akun)' . chr(10);
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
            return;
        }

        $modelMember = new Member;
        $dataUser = $modelMember->getKBBMember($args['username']);

        if ($dataUser == null) {
            $text = 'Username yang anda masukkan tidak ada atau tidak terdaftar sebagai member KBB';
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
            return;
        }

        if ($args['param'] == 'status') {

            $active_at = $dataUser->active_at;
            if ($dataUser->pin_activate_at != null) {
                $active_at = $dataUser->pin_activate_at;
            }
            $expired = strtotime('+365 days', strtotime($active_at));
            $now = time();
            $timeleft = $expired - $now;
            $daysleft = round((($timeleft / 24) / 60) / 60);

            $kbbStatus = "Pasif";
            if ($dataUser->affiliate == 3) {
                $kbbStatus = "Aktif";
            }

            $rank = 'Member Biasa';
            if ($dataUser->member_type == 10) {
                $rank = 'Silver III';
            } elseif ($dataUser->member_type == 11) {
                $rank = 'Silver II';
            } elseif ($dataUser->member_type == 12) {
                $rank = 'Silver I';
            } elseif ($dataUser->member_type == 13) {
                $rank = 'Gold III';
            } elseif ($dataUser->member_type == 14) {
                $rank = 'Gold II';
            } elseif ($dataUser->member_type == 15) {
                $rank = 'Gold I';
            } elseif ($dataUser->member_type == 16) {
                $rank = 'Sapphire';
            } elseif ($dataUser->member_type == 17) {
                $rank = 'Diamond';
            } elseif ($dataUser->member_type == 18) {
                $rank = 'Royal Diamond';
            }

            $text = 'Username: ' . $dataUser->user_code . chr(10);
            $text .= 'Type KBB: ' . $kbbStatus . chr(10);
            if ($daysleft > 0) {
                $text .= 'Masa aktif akun: ' . $daysleft . ' hari sebelum expired (' . date('d-m-Y', $expired) . ')' . chr(10);
            } else {
                $text .= 'Masa aktif akun: Sudah expired pada ' . date('d-m-Y', $expired) . chr(10);
            }

            $text .= 'Peringkat: ' . $rank . chr(10);

            if ($dataUser->invited_by != null) {
                $host = User::find($dataUser->invited_by);
                $text .= 'Diajak oleh: ' . $host->user_code . chr(10);
            }
            $this->replyWithMessage(compact('text'));
            return;
        } elseif ($args['param'] == 'bonus') {
            $bonuses = $controller->getMemberAvailableBonus($dataUser->id);
            $modelBonus = new Bonus;
            $totalClaimedLMBfromMarketplace = $modelBonus->getTotalClaimedLMBfromMarketplace($dataUser->id);
            $bonusTuntas = $bonuses->daily_withdrawn;
            $bonusTersedia = $bonuses->daily_bonus;

            $text = 'Username: ' . $dataUser->user_code . chr(10);
            $text .= 'Bonus tersedia: Rp' . number_format($bonusTersedia) . chr(10);
            $text .= 'Bonus tuntas: Rp' . number_format($bonusTuntas) .  chr(10);
            $bonusRights = 100;
            if ($dataUser->affiliate == 2) {
                $bonusRights = 25;
            }
            $text .= 'Hak Bonus: Rp' . number_format(($bonusRights / 100) * $bonusTersedia) . chr(10);
            $text .= 'Reward LMB Jual-Beli: ' . number_format($totalClaimedLMBfromMarketplace, 2) . ' LMB' . chr(10);
            $this->replyWithMessage(compact('text'));
            return;
        } elseif ($args['param'] == 'sponsoring') {
            $getInvite = $modelMember->getInviteCount($dataUser->id);
            $getSponsoring = $modelMember->getSponsorPeringkat($dataUser);
            $text = '';
            if ($getSponsoring == null && count($getInvite) == 0) {
                $text .= 'Akun ' . $dataUser->user_code . ' belum ada mensponsori/mengajak akun lain.';
                $this->replyWithMessage(compact('text'));
                return;
            } else {
                if (count($getInvite) > 0) {
                    $text .=
                        'Daftar member KBB yang diajak oleh akun ' . $dataUser->user_code . ':' . chr(10) . chr(10);
                    $no = 1;
                    foreach ($getInvite as $row) {
                        $text .= $no . '. ' . $row->user_code . chr(10);
                        $no++;
                    }
                    $text .= chr(10) . '============' . chr(10);
                }
                if ($getSponsoring != null) {
                    $text .= 'Daftar akun yang disponsori oleh akun ' . $dataUser->user_code . ':' . chr(10) . chr(10);
                    $no = 1;
                    foreach ($getSponsoring as $row) {
                        $text .= $no . '. ' . $row->user_code . ' - ' . $row->name . ' (' . $row->total_sponsor . ')' . chr(10);
                        $no++;
                    }
                }

                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            }
        } else {
            $text = 'Perintah yang anda masukkan kurang tepat!' . chr(10) . chr(10);
            $text .= 'Pergunakan parameter seperti "status" atau "bonus" diikuti dengan "username" yang ingin diperiksa.' . chr(10) . chr(10);
            $text .= 'Contoh: "/kbb status Budi001"' . chr(10) . chr(10);
            $text .= 'Parameter yang tersedia:' . chr(10);
            $text .= '1. _status_ (untuk melihat status suatu akun)' . chr(10);
            $text .= '2. _bonus_ (untuk melihat bonus suatu akun)' . chr(10);
            $text .= '3. _sponsoring_ (untuk melihat daftar akun yang disponsori oleh suatu akun)' . chr(10);
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
            return;
        }
    }
}
