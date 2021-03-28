<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFinanceRegistrationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $dataEmail;
    protected $emailSend;

    public function __construct($dataEmail, $emailSend)
    {
        $this->dataEmail = $dataEmail;
        $this->emailSend = $emailSend;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailData = $this->dataEmail;
        $emailTo = $this->emailSend;
        Mail::send('member.email.fi_email', $emailData, function ($message) use ($emailTo) {
            $message->to($emailTo, 'Lumbung Finance Registration')
                ->subject('Welcome to Lumbung Finance');
        });
    }
}
