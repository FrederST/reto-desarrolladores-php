<?php

return [

    'default_currency' => env('DEFAULT_CURRENCY_SHOP', 'USD'),
    'default_payment_method' => env('DEFAULT_PAYMENT_METHOD', 'place_to_pay'),
    'payment_methods' => [
        'place_to_pay' => [
            'api_url' => env('PLACE_TO_PAY_API_URL', 'https://dev.placetopay.com/redirection/'),
            'login' => env('PLACE_TO_PAY_LOGIN', null),
            'secretkey' => env('PLACE_TO_PAY_SECRET_KEY', null),
        ],
    ],

];
