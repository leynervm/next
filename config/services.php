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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],

    'recaptcha_v2' => [
        'key_web' => env('RECAPTCHA_KEY_WEB_V2'),
        'key_secret' => env('RECAPTCHA_KEY_SECRET_V2'),
    ],

    'recaptcha_v3' => [
        'key_web' => env('RECAPTCHA_KEY_WEB_V3'),
        'key_secret' => env('RECAPTCHA_KEY_SECRET_V3'),
    ],

    'niubiz' => [
        'url_api' => env('NIUBIZ_URL_API'),
        'url_js' => env('NIUBIZ_URL_JS'),
        'user' => env('NIUBIZ_USER'),
        'password' => env('NIUBIZ_PASSWORD'),
        'merchant_id' => env('NIUBIZ_MERCHANT_ID'),
        'currency' => env('NIUBIZ_CURRENCY')
    ],

    'hashids' => [
        'password' => env('HASHIDS_PASSWORD', 'next2024*'),
        'length' => env('HASHIDS_LENGTH', 12),
    ],

    'apisnet' => [
        'token' => env('TOKEN_APIS_NET'),
    ]

];
