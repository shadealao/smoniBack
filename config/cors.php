<?php

return [

    /*
     * Paths that receive CORS headers.
     * Must include every endpoint the SPA frontend calls directly.
     */
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

    /*
     * Comma-separated list of allowed origins from CORS_ALLOWED_ORIGINS.
     *
     * NEVER use '*' while supports_credentials is true — browsers reject that
     * combination, and it would be a CSRF/credential-leak hole anyway.
     *
     * Production example:
     *   CORS_ALLOWED_ORIGINS=https://smoni-tanstack.vercel.app,https://smoni.fr
     */
    'allowed_origins' => array_filter(
        array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')))
    ),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * Required for stateful Sanctum SPA auth (session cookies + XSRF-TOKEN).
     * Without this, the browser rejects the CORS preflight even when the
     * frontend sends withCredentials: true.
     */
    'supports_credentials' => true,

];
