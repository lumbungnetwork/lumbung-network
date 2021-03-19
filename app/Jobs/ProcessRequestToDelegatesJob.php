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
            $username1 = $reqData->usernames;
            $nama1 = User::where('user_code', $username1)->first()->full_name;

            $telegramBotController->sendStockistApplyRequest([
                'nama1' => $nama1,
                'username1' => $username1,
                'delegate' => $reqData->delegate,
                'request_id' => $reqData->id,
            ]);

            return;
        } elseif ($this->type == 2) {
            $reqData = $modelMember->getRequestVendor($this->request_id);
            $username1 = $reqData->usernames;
            $nama1 = User::where('user_code', $username1)->first()->full_name;

            $telegramBotController->sendVendorApplyRequest([
                'nama1' => $nama1,
                'username1' => $username1,
                'delegate' => $reqData->delegate,
                'request_id' => $reqData->id,
            ]);

            return;
        } elseif ($this->type == 3) {
            $reqData = $modelMember->getResetTronRequest('id', $this->request_id);
            $user = User::find($reqData->user_id);

            $data = [

                'name' => $user->full_name,
                'username' => $user->user_code,
                'delegate' => $reqData->delegate,
                'request_id' => $reqData->id,
            ];

            $telegramBotController->sendResetTronRequest($data);

            return;
        }
    }
}
