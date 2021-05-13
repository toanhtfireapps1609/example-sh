<?php

return [
    'scope' => [
        'read_products',
        'write_products',
        'read_product_listings',
    ],
    'spf_api_key' => env('SPF_API_KEY', ''),
    'spf_secret_key' => env('SPF_SECRET_KEY', ''),
    'redirect_url' => env('SPF_REDIRECT_URI', ''),

];
