<?php

return [

    'infile' => [
        'environment' => env('INFILE_ENVIRONMENT', 'test'),
        'invalidate_url' => env('INFILE_INVALIDATE_URL'),
        'api_key' => env('INFILE_API_KEY'),
        'ca_bundle' => env('INFILE_CA_BUNDLE'),
        'invalidation_response_confirmed' => filter_var(env('INFILE_INVALIDATION_RESPONSE_CONFIRMED', true), FILTER_VALIDATE_BOOLEAN),
    ],

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
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
