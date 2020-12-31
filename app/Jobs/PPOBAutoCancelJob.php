<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Model\Pin;

class PPOBAutoCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $masterSalesID;
    public function __construct($masterSalesID)
    {
        $this->masterSalesID = $masterSalesID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelPin = new Pin;
        $getData = $modelPin->getJobPPOBAutoCancel($this->masterSalesID);

        //checking
        if ($getData == null) {
            return;
        }

        if ($getData->vendor_cek != null) {
            return;
        }

        if ($getData->vendor_approve != 0) {
            return;
        }

        $dataUpdate = array(
            'status' => 3,
            'reason' => 'Batal Otomatis',
            'deleted_at' => date('Y-m-d H:i:s')
        );
        $modelPin->getUpdatePPOB('id', $this->masterSalesID, $dataUpdate);

        return;
    }
}
