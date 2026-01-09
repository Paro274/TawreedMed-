<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // ✅ إضافة supplier guard
        'supplier' => [
            'driver' => 'session',
            'provider' => 'suppliers',
        ],

        // ✅ إضافة customer guard
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // ✅ إضافة supplier provider
        'suppliers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Supplier::class,
        ],

        // ✅ إضافة customer provider
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'customer_password_resets', // ✅ الجدول الجديد
            'expire' => 60,
            'throttle' => 60,
        ],

        // ✅ إضافة passwords للموردين
        'suppliers' => [
            'provider' => 'suppliers',
            'table' => 'supplier_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        // ✅ إعدادات العملاء
        'customers' => [
            'provider' => 'customers',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
