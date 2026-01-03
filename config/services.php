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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ipaymu' => [
        'va' => env('IPAYMU_VA', ''),
        'api_key' => env('IPAYMU_API_KEY', ''),
        'environment' => env('IPAYMU_ENVIRONMENT', 'sandbox'),
        'sandbox_url' => 'https://sandbox.ipaymu.com/api/v2',
        'production_url' => 'https://my.ipaymu.com/api/v2',
    ],

    'binderbyte' => [
        'api_key' => env('BINDERBYTE_API_KEY', '8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d'),
    ],

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY', ''),
        'account_type' => env('RAJAONGKIR_ACCOUNT_TYPE', 'starter'), // starter, basic, or pro
        'origin_city' => env('RAJAONGKIR_ORIGIN_CITY', '151'), // Default: Bandung (151)
    ],

];
