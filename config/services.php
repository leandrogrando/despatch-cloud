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

    'despatch-cloud' => [
        'api_url' => env('DESPATCH_CLOUD_API_URL', 'https://sample-market.despatchcloud.uk/api'),
        'api_key' => env('DESPATCH_CLOUD_API_KEY', 'UQ6VMuNHP1C4FWzJ0HJhNE64BLEa4p5rKuFY8yK6PZa4VR3cpfRKB13yWzkFOSoDjyju3bVKkaXNPcg0Zh06cmy8QR6VJspw2ELXvILtM1vCj6NMOWg7YvACKIqyGMF'),
    ],

];
