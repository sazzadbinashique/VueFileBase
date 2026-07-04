<?php

return [
    'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
    'store_id' => env('SSLCOMMERZ_STORE_ID', 'testbox'),
    'store_pass' => env('SSLCOMMERZ_STORE_PASSWORD', 'testbox'),

    'urls' => [
        'sandbox' => [
            'init' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
            'validation' => 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
        ],
        'live' => [
            'init' => 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
            'validation' => 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
        ],
    ],

    'currency' => 'BDT',
    'success_url' => env('APP_URL') . '/api/sslcommerz/success',
    'cancel_url' => env('APP_URL') . '/api/sslcommerz/cancel',
    'fail_url' => env('APP_URL') . '/api/sslcommerz/fail',
    'ipn_url' => env('APP_URL') . '/api/sslcommerz/ipn',
];
