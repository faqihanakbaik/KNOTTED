<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        /*
        |--------------------------------------------------------------------------
        | Local Storage
        |--------------------------------------------------------------------------
        */

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Public Storage
        |--------------------------------------------------------------------------
        */

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Default AWS S3 Storage
        |--------------------------------------------------------------------------
        */

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Product Images (Public)
        |--------------------------------------------------------------------------
        */

        'supabase_products' => [
            'driver' => 's3',
            'key' => env('SUPABASE_STORAGE_KEY'),
            'secret' => env('SUPABASE_STORAGE_SECRET'),
            'region' => env('SUPABASE_STORAGE_REGION'),
            'bucket' => env('SUPABASE_PRODUCT_BUCKET'),
            'endpoint' => env('SUPABASE_STORAGE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'url' => rtrim(env('SUPABASE_URL'), '/') .
                '/storage/v1/object/public/' .
                env('SUPABASE_PRODUCT_BUCKET'),
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Payment Proofs (Private)
        |--------------------------------------------------------------------------
        */

        'supabase_payments' => [
            'driver' => 's3',
            'key' => env('SUPABASE_STORAGE_KEY'),
            'secret' => env('SUPABASE_STORAGE_SECRET'),
            'region' => env('SUPABASE_STORAGE_REGION'),
            'bucket' => env('SUPABASE_PAYMENT_BUCKET'),
            'endpoint' => env('SUPABASE_STORAGE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Supabase Storage - Custom Order References (Private)
        |--------------------------------------------------------------------------
        */

        'supabase_custom_orders' => [
            'driver' => 's3',
            'key' => env('SUPABASE_STORAGE_KEY'),
            'secret' => env('SUPABASE_STORAGE_SECRET'),
            'region' => env('SUPABASE_STORAGE_REGION'),
            'bucket' => env('SUPABASE_CUSTOM_ORDER_BUCKET'),
            'endpoint' => env('SUPABASE_STORAGE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'throw' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
