<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use App\Model\Bonus;
use App\Jobs\SendLMBRewardPeringkatJob;
use Telegram\Bot\Laravel\Facades\Telegram;

class ClaimKBBRewardLMBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kbb:rewardclaim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for claiming KBB Rewards';

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
        $modelBonus = new Bonus;
        $message = 'Claim Reward LMB KBB hari ini:' . chr(10) . chr(10);
        //get accounts for Silver III

        $silver3 = DB::table('users')
            ->select('users.id', 'users.user_code')
            ->leftJoin('claim_reward', 'claim_reward.user_id', '=', 'users.id')
            ->where('users.affiliate', 1)
            ->where('users.member_type', '>=', 10)
            ->where('claim_reward.reward_id', null)
            ->get();
        if (count($silver3) > 0) {
            $message .= '*Silver III*' . chr(10);
            foreach ($silver3 as $s3) {

                $dataInsert = array(
                    'user_id' => $s3->id,
                    'reward_id' => 1,
                    'claim_date' => date('Y-m-d')
                );
                $getRewardId = $modelBonus->getInsertClaimReward($dataInsert);
                SendLMBRewardPeringkatJob::dispatch($getRewardId->lastID)->onQueue('tron');
                $message .= $s3->user_code . ' 100 LMB' . chr(10);
            }
        }

        //silver II

        $silver2 = DB::table('users')
            ->select('users.id', 'users.user_code')
            ->leftJoin('claim_reward', 'claim_reward.user_id', '=', 'users.id')
            ->where('users.affiliate', 1)
            ->where('users.member_type', '>=', 11)
            ->where('claim_reward.reward_id', '>', 0)
            ->get();

        if (count($silver2) > 0) {
            $message .= chr(10) . '*Silver II*' . chr(10);
            foreach ($silver2 as $s2) {

                $dataInsert = array(
                    'user_id' => $s2->id,
                    'reward_id' => 2,
                    'claim_date' => date('Y-m-d')
                );
                $getRewardId = $modelBonus->getInsertClaimReward($dataInsert);
                SendLMBRewardPeringkatJob::dispatch($getRewardId->lastID)->onQueue('tron');
                $message .= $s2->user_code . ' 200 LMB' . chr(10);
            }
        }

        //silver I

        $silver1 = DB::table('users')
            ->select('users.id', 'users.user_code')
            ->leftJoin('claim_reward', 'claim_reward.user_id', '=', 'users.id')
            ->where('users.affiliate', 1)
            ->where('users.member_type', '>=', 12)
            ->where('claim_reward.reward_id', '>', 1)
            ->get();

        if (count($silver1) > 0) {
            $message .= chr(10) . '*Silver I*' . chr(10);
            foreach ($silver1 as $s1) {

                $dataInsert = array(
                    'user_id' => $s1->id,
                    'reward_id' => 3,
                    'claim_date' => date('Y-m-d')
                );
                $getRewardId = $modelBonus->getInsertClaimReward($dataInsert);
                SendLMBRewardPeringkatJob::dispatch($getRewardId->lastID)->onQueue('tron');
                $message .= $s1->user_code . ' 500 LMB' . chr(10);
            }
        }

        //gold III

        $gold3 = DB::table('users')
            ->select('users.id', 'users.user_code')
            ->leftJoin('claim_reward', 'claim_reward.user_id', '=', 'users.id')
            ->where('users.affiliate', 1)
            ->where('users.member_type', '>=', 13)
            ->where('claim_reward.reward_id', '>', 2)
            ->get();

        if (count($gold3) > 0) {
            $message .= chr(10) . '*Gold III*' . chr(10);
            foreach ($gold3 as $g3) {

                $dataInsert = array(
                    'user_id' => $g3->id,
                    'reward_id' => 4,
                    'claim_date' => date('Y-m-d')
                );
                $getRewardId = $modelBonus->getInsertClaimReward($dataInsert);
                SendLMBRewardPeringkatJob::dispatch($getRewardId->lastID)->onQueue('tron');
                $message .= $g3->user_code . ' 2000 LMB' . chr(10);
            }
        }

        Telegram::sendMessage([
            'chat_id' => '365874331',
            'text' => $message,
            'parse_mode' => 'markdown'
        ]);

        return;
    }
}
