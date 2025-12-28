<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Low Stock Threshold
    |--------------------------------------------------------------------------
    |
    | When a product's stock quantity falls to or below this value,
    | a notification will be sent to admin users.
    |
    */
    'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 5),

    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    |
    | The email address to send admin notifications to.
    |
    */
    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | Currency configuration for the shop.
    |
    */
    'currency' => 'USD',
    'currency_symbol' => '$',
];
