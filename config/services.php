<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'app' => [
        'url' => env('APP_URL'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'mail' => [
        'to' => env('MAIl_TO'),
        'cc' => env('MAIl_CC'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google_maps' => [
        'api_key' => env('GEOIP_GOOGLE_API_KEY'),
    ],

    'workingType' => [
        0 => 'A勤務',
        1 => 'B勤務',
        2 => 'C勤務',
        3 => 'D勤務',
    ],

    'type' => [
        0 => '打刻修正',
        1 => '有給申請',
        2 => '交通費修正',
    ],

];
