<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
        'forgot-password',
        'reset-password',
        'email/*',
        'user/password',
        'user/profile-information',
    ],

    'allowed_methods' => ['*'],

    // Pinned to the explicit origins in CORS_ALLOWED_ORIGINS. Never use '*'
    // here while supports_credentials is true — browsers refuse the combo,
    // and it would be a CSRF/credential-leak hole anyway.
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Required for stateful Sanctum SPA auth (cookies)
    'supports_credentials' => true,

];
