<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class DigiflazzTestCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'df:test {no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $no = $this->argument('no');

        // Prepare Digiflazz Credentials
        $username = config('services.digiflazz.user');
        $apiKey = config('services.digiflazz.dev-key');
        $ref_id = 'test' . rand(23, 99);
        $sign = md5($username . $apiKey . $ref_id);

        if ($no == 1) {
            // Prepaid Pending lalu callback Sukses 
            $json = json_encode([
                'username' => $username,
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001233',
                'ref_id' => $ref_id,
                'testing' => true,
                'sign' => $sign,
            ]);
        } elseif ($no == 2) {
            // Prepaid Pending lalu callback Gagal
            $json = json_encode([
                'username' => $username,
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001234',
                'ref_id' => $ref_id,
                'testing' => true,
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

        \Log::info($response->getBody());

        return;
    }
}
