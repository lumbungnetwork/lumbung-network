<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'digiflazz' => [
        'user' => env('DF_U'),
        'key' => env('DF_AK'),
    ],

    'eidr' => [
        'deposit' => env('VENDOR_DEPOSIT'),
        'royalti' => env('ROYALTI'),
        'kbbmaster' => env('KBBMASTER'),
    ],

    'telegram' => [
        'eidr' => '1300893823:' . env('BEIDR'),
        'build' => env('AEIDR') . env('DEIDR'),
        'test' => env('CEIDR') . env('AEIDR'),
        'lmb' => '1402924705:' . env('BLMB'),
        'rebuild' => env('DLMB') . env('CLMB'),
        'retest' => env('DLMB') . env('ALMB'),
        'delegates' => env('TELEGRAM_DELEGATES_CHAT_ID'),
        'overlord' => env('TELEGRAM_OVERLORD_CHAT_ID'),

    ],

    'telegram-bot-api' => [
        'token' => '1402924705:' . env('BLMB')
    ],

    'tron' => [
        'lmb_staking' => env('LMBSTAKING')
    ],

    'app' => [
        'url' => env('BASE_URL'),
        'domain' => env('APP_DOMAIN'),
        'protocol_url' => env('APP_URL')
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

];
