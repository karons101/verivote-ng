<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the conventional
    | location for this type of information so packages and application
    | services can access credentials consistently.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | VeriVote NG Cryptographic Signing
    |--------------------------------------------------------------------------
    |
    | The Ed25519 signing keys are loaded from environment variables.
    | The secret key must never be persisted in application data or
    | committed to source control.
    |
    */

    'verivote' => [
        'signing_secret_key' => env('VERIVOTE_SIGNING_SECRET_KEY'),
        'signing_public_key' => env('VERIVOTE_SIGNING_PUBLIC_KEY'),
    ],

];