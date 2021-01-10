<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Model\Member;
use App\Model\Bonus;
use App\Model\Transferwd;

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

        if (empty($args)) {
            $text = 'Perintah yang anda gunakan kurang tepat.' . chr(10) . chr(10);
            $text .= 'Pergunakan parameter "status" atau "bonus" diikuti dengan "username" yang ingin diperiksa.' . chr(10) . chr(10);
            $text .= 'Contoh: /kbb status Budi001';
            $this->replyWithMessage(compact('text'));
            return;
        }

        $modelMember = new Member;
        $dataUser = $modelMember->getKBBMember($args['username']);

        if ($dataUser == null) {
            $text = 'Username yang anda masukkan tidak ada atau tidak terdaftar sebagai member KBB';
            $this->replyWithMessage(compact('text'));
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
            $this->replyWithMessage(compact('text'));
            return;
        } elseif ($args['param'] == 'bonus') {
            $modelBonus = new Bonus;
            $modelWD = new Transferwd;
            $totalBonus = $modelBonus->getTotalBonus($dataUser);
            $totalWD = $modelWD->getTotalDiTransfer($dataUser);
            $totalWDeIDR = $modelWD->getTotalDiTransfereIDR($dataUser);
            $totalBonusRoyalti = $modelBonus->getTotalBonusRoyalti($dataUser);
            $totalWDRoyalti = $modelWD->getTotalDiTransferRoyalti($dataUser);

            $totalClaimedLMBfromMarketplace = $modelBonus->getTotalClaimedLMBfromMarketplace($dataUser->id);

            $saldoBonus = floor($totalBonus->total_bonus) - $totalWD->total_wd - $totalWD->total_tunda - $totalWD->total_fee_admin - $totalWDeIDR->total_wd - $totalWDeIDR->total_tunda - $totalWDeIDR->total_fee_admin;
            $saldoBonusRoyalti = floor($totalBonusRoyalti->total_bonus) - $totalWDRoyalti->total_wd - $totalWDRoyalti->total_tunda - $totalWDRoyalti->total_fee_admin;
            $bonusTuntas = $totalWDeIDR->total_wd + $totalWDeIDR->total_fee_admin + $totalWDRoyalti->total_wd + $totalWDRoyalti->total_fee_admin;
            $bonusTersedia = $saldoBonus + $saldoBonusRoyalti;

            $text = 'Username: ' . $dataUser->user_code . chr(10);
            $text .= 'Bonus tersedia: Rp' . number_format($bonusTersedia) . chr(10);
            $text .= 'Bonus tuntas: Rp' . number_format($bonusTuntas) .  chr(10);
            $bonusRights = 100;
            if ($dataUser->affiliate == 2) {
                $bonusRights = 25;
            }
            $text .= 'Hak Bonus: Rp' . number_format(($bonusRights / 100) * $saldoBonus) . chr(10);
            $text .= 'Reward LMB Jual-Beli: ' . number_format($totalClaimedLMBfromMarketplace, 2) . ' LMB' . chr(10);
            $this->replyWithMessage(compact('text'));
            return;
        } else {
            $text = 'Perintah yang anda gunakan kurang tepat.' . chr(10) . chr(10);
            $text .= 'Pergunakan parameter "status" atau "bonus" diikuti dengan "username" yang ingin diperiksa.' . chr(10) . chr(10);
            $text .= 'Contoh: /kbb status Budi001';
            $this->replyWithMessage(compact('text'));
            return;
        }
    }
}