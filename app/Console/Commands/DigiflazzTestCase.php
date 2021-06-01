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

        if ($no == 1) {
            // Prepaid Sukses 
            $json = json_encode([
                'username' => 'username',
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001230',
                'ref_id' => 'test1',
                'testing' => true,
                'sign' => '740b00a1b8784e028cc8078edf66d12b',
            ]);
        } elseif ($no == 2) {
            // Prepaid Gagal
            $json = json_encode([
                'username' => 'username',
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001232',
                'ref_id' => 'test2',
                'sign' => '740b00a1b8784e028cc8078edf66d12b',
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
