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

    'mailgun'   => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses'       => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe'    => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook'  => [
        'client_id'     => '162103697654424',
        'client_secret' => 'c72d72ca6929e62fa09a83e67bd4409a',
        'redirect'      => 'http://toylabs.dev/login/callback/facebook',
    ],

    'google'    => [
        'client_id'     => '328908169512-93fanb64d9cj1098dule03492mhqll3d.apps.googleusercontent.com',
        'client_secret' => 't9ksb23KvSyOLDiSHqDWnXKb',
        'redirect'      => 'http://toylabs.dev/login/callback/google',
    ],
];
