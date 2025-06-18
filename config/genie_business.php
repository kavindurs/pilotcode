<?php
// filepath: c:\xampp\htdocs\pilot\config\genie_business.php

return [
    'app_id' => env('GENIE_BUSINESS_APP_ID'),
    'app_key' => env('GENIE_BUSINESS_APP_KEY'),
    'public_key' => env('GENIE_BUSINESS_PUBLIC_KEY'),
    'api_url' => env('GENIE_BUSINESS_API_URL', 'https://api.geniebiz.lk'),
    'environment' => env('GENIE_BUSINESS_ENVIRONMENT', 'sandbox'),

    'endpoints' => [
        'customers' => '/public-customers/',
        'transactions' => '/public/v2/transactions',
        'transaction_status' => '/public/transactions/',
    ],

    'currency' => 'LKR',
    'webhook_url' => env('APP_URL') . '/genie-business/webhook',
];
