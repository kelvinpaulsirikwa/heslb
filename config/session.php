<?php

use Illuminate\Support\Str;

return [

   

    'driver' => env('SESSION_DRIVER', 'file'),

  

    'lifetime' => env('SESSION_LIFETIME', 9.5), // Set to 9.5 minutes for security

    'expire_on_close' => false,

  

    'encrypt' => env('SESSION_ENCRYPT', true), // Enable session encryption for security

   

    'files' => storage_path('framework/sessions'),

   

    'connection' => env('SESSION_CONNECTION'),

  

    'table' => 'sessions',

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

   

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

  

    'path' => '/',

    'domain' => env('SESSION_DOMAIN'),

   

    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV') !== 'local'), // Require HTTPS in production, allow HTTP in local

   
    'http_only' => true,

  
    'same_site' => env('SESSION_SAME_SITE', 'lax'), // Use 'lax' or 'strict' for CSRF protection

];
