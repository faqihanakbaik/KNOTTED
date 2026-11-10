<?php

/**
 * Railway-specific configuration
 * 
 * This config auto-detects Railway environment and sets appropriate defaults
 */

$isRailway = !empty($_SERVER['RAILWAY_ENVIRONMENT']) || 
             str_contains($_SERVER['HTTP_HOST'] ?? '', 'railway.app');

return [
    
    /*
    |--------------------------------------------------------------------------
    | Railway Detection
    |--------------------------------------------------------------------------
    |
    | Automatically detect if running on Railway platform
    |
    */
    
    'is_railway' => $isRailway,
    
    /*
    |--------------------------------------------------------------------------
    | Railway Session Settings
    |--------------------------------------------------------------------------
    |
    | Override session settings for Railway deployment
    |
    */
    
    'session' => [
        'driver' => $isRailway ? 'database' : env('SESSION_DRIVER', 'file'),
        'secure' => false, // Railway handles SSL at proxy
        'same_site' => 'lax', // Not strict for Railway
        'domain' => null, // Auto-detect
        'encrypt' => false, // Railway uses HTTPS
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Railway Trust Proxies
    |--------------------------------------------------------------------------
    |
    | Trust all proxies when on Railway
    |
    */
    
    'trust_proxies' => $isRailway ? '*' : null,
    
];
