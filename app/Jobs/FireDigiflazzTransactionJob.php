<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Member\DigitalSale;
use GuzzleHttp\Client;

class FireDigiflazzTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $salesID;
    public function __construct($salesID)
    {
        $this->salesID = $salesID;
    }

    // Prevent Overlap
    public function middleware()
    {
        return [(new WithoutOverlapping($this->salesID))->dontRelease()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get Data
        $data = DigitalSale::findOrFail($this->salesID);
        $ref_id = $data->ppob_code;

        // Prepare Digiflazz Credentials
        $username = config('services.digiflazz.user');
        $apiKey = config('services.digiflazz.key');
        $sign = md5($username . $apiKey . $ref_id);

        // Prepare Payload according to tx type (Prepaid or Postpaid)

        if ($data->type >= 4 && $data->type < 11) {
            $json = json_encode([
                'commands' => 'pay-pasca',
                'username' => $username,
                'buyer_sku_code' => $data->buyer_code,
                'customer_no' => $data->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            ]);
        } else {
            $json = json_encode([
                'username' => $username,
                'buyer_sku_code' => $data->buyer_code,
                'customer_no' => $data->product_name,
                'ref_id' => $ref_id,
                'sign' => $sign,
            ]);
        }

        // endpoint
        $url = 'https://api.digiflazz.com/v1/transaction';

        // use Guzzle Client to send the POST request
        $client = new Client;
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => $json
        ]);

        // Let webhook handle the response
        return;
    }
}
