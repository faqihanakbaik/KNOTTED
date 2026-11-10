<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Set the proxies that should be trusted. Railway, Cloudflare, and other
    | reverse proxies will be automatically trusted in production.
    |
    */

    'proxies' => env('APP_ENV') === 'production' ? '*' : null,

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    |
    | These are the headers that will be trusted from the proxies.
    |
    */

    'headers' => [
        \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB,
    ],

];
