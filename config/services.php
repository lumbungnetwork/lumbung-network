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

    'affiliate' => [
        'ksga' => [
            'password' => env('KSGA_PASSWORD'),
            '2fa' => env('KSGA_2FA')
        ],
    ],

    'app' => [
        'url' => env('BASE_URL'),
        'domain' => env('APP_DOMAIN'),
        'protocol_url' => env('APP_URL')
    ],

    'digiflazz' => [
        'user' => env('DF_U'),
        'key' => env('DF_AK'),
        'dev-key' => env('DF_DEVKEY'),
        'webhook' => env('DIGIFLAZZ_WEBHOOK_URI'),
        'webhook_secret' => env('DIGIFLAZZ_WEBHOOK_SECRET')
    ],

    'eidr' => [
        'deposit' => env('VENDOR_DEPOSIT'),
        'royalti' => env('ROYALTI'),
        'kbbmaster' => env('KBBMASTER'),
    ],

    'lmb_div' => [
        'proportion' => env('LMB_DIV_PROPORTION'),
        'cellular' =>env('LMB_DIV_CELLULAR'),
        'pln_prepaid' => env('LMB_DIV_PLNPREPAID'),
        'telkom' => env('LMB_DIV_TELKOM'),
        'pln_postpaid' => env('LMB_DIV_PLNPOSTPAID'),
        'hp_postpaid' => env('LMB_DIV_HPPOSTPAID'),
        'bpjs' => env('LMB_DIV_BPJS'),
        'pdam' => env('LMB_DIV_PDAM'),
        'pgn' => env('LMB_DIV_PGN'),
        'multifinance' => env('LMB_DIV_MULTIFINANCE'),
        'emoney' => env('LMB_DIV_EMONEY')
    ],

    'telegram' => [
        'eidr' => env('EIDRBOT_TOKEN'),
        'build' => env('AEIDR') . env('DEIDR'),
        'test' => env('CEIDR') . env('AEIDR'),
        'lmb' => env('LMBBOT_TOKEN'),
        'rebuild' => env('DLMB') . env('CLMB'),
        'retest' => env('DLMB') . env('ALMB'),
        'delegates' => env('TELEGRAM_DELEGATES_CHAT_ID'),
        'supervisor' => env('TELEGRAM_OVERLORD_CHAT_ID'),
        'webhook' => env('TELEGRAM_WEBHOOK_URL'),
        'channel' => env('TELEGRAM_CHANNEL_ID')

    ],

    'tron' => [
        'lmb_staking' => env('LMBSTAKING'),
        'lmb_distributor' => env('LMBDISTRIBUTOR'),
        'eidr_hot' => env('EIDRHOT'),
        'address' => [
            'lmb_distributor' => env('LMBDISTRIBUTOR_ADDRESS'),
            'lmb_staking' => env('LMBSTAKING_ADDRESS'),
            'eidr_hot' => env('EIDRHOT_ADDRESS'),
            'eidr_deposit' => env('EIDRDEPOSIT_ADDRESS'),
            'burn' => env('TRONBURN_ADDRESS')
        ],
        'token_id' => [
            'lmb' => env('LMB_TOKEN_ID'),
            'eidr' => env('EIDR_TOKEN_ID')
        ],
        'decimals' => [
            'lmb' => env('LMB_DECIMALS'),
            'eidr' => env('EIDR_DECIMALS')
        ]
    ],


];
