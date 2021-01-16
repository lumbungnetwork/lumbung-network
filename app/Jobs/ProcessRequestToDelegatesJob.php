<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Model\Member;
use App\User;
use App\Http\Controllers\TelegramBotController;
use Monolog\Handler\TelegramBotHandler;

class ProcessRequestToDelegatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $request_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $request_id)
    {
        $this->type = $type;
        $this->request_id = $request_id;
    }

    //$type 1 = Stockist, 2 = Vendor, 3 = Tron Address

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelMember = new Member;
        $telegramBotController = new TelegramBotController;

        if ($this->type == 1) {
            $reqData = $modelMember->getRequestStockist($this->request_id);
            $usernames = explode(' ', $reqData->usernames);
            $username1 = $usernames[0];
            $username2 = $usernames[1];
            $username3 = $usernames[2];
            $nama1 = User::where('user_code', $username1)->first()->full_name;
            $nama2 = User::where('user_code', $username2)->first()->full_name;
            $nama3 = User::where('user_code', $username3)->first()->full_name;
            $data = [
                'nama1' => $nama1,
                'nama2' => $nama2,
                'nama3' => $nama3,
                'username1' => $username1,
                'username2' => $username2,
                'username3' => $username3,
                'delegate' => $reqData->delegate,
                'request_id' => $reqData->id,
            ];

            $telegramBotController->sendStockistApplyRequest($data);

            return;
        } elseif ($this->type == 2) {
            $reqData = $modelMember->getRequestVendor($this->request_id);
            $usernames = explode(' ', $reqData->usernames);
            $username1 = $usernames[0];
            $username2 = $usernames[1];
            $username3 = $usernames[2];
            $username4 = $usernames[3];
            $username5 = $usernames[4];
            $nama1 = User::where('user_code', $username1)->first()->full_name;
            $nama2 = User::where('user_code', $username2)->first()->full_name;
            $nama3 = User::where('user_code', $username3)->first()->full_name;
            $nama4 = User::where('user_code', $username4)->first()->full_name;
            $nama5 = User::where('user_code', $username5)->first()->full_name;
            $data = [
                'nama1' => $nama1,
                'nama2' => $nama2,
                'nama3' => $nama3,
                'nama4' => $nama4,
                'nama5' => $nama5,
                'username1' => $username1,
                'username2' => $username2,
                'username3' => $username3,
                'username4' => $username4,
                'username5' => $username5,
                'delegate' => $reqData->delegate,
                'request_id' => $reqData->id,
            ];

            $telegramBotController->sendVendorApplyRequest($data);

            return;
        }
    }
}
