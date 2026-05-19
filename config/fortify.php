<?php

use Laravel\Fortify\Features;

return [

    // Fortify drives the stateful web session guard (cookies). Sanctum
    // then gates API routes via `auth:sanctum` and uses that session for
    // SPA requests. Setting this to 'sanctum' breaks because Sanctum's
    // guard is a RequestGuard, not a StatefulGuard.
    'guard' => 'web',

    // Use the 'web' middleware stack so Fortify's session, cookie, and
    // CSRF middleware run on /login, /register, /forgot-password etc.
    // Using 'api' would skip session middleware and break cookie auth.
    'middleware' => ['web'],

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    /*
    | API-only app. The default Fortify views are HTML; we don't render them.
    | Setting views=false disables view-binding so Fortify responds with JSON.
    */
    'views' => false,

    'home' => env('FRONTEND_URL', 'http://localhost:5173') . '/connexion?email_verified=1',

    'prefix' => '',

    'domain' => null,

    /*
    | Each feature can be enabled or disabled. We omit two-factor and profile
    | information updates for now (existing UserController endpoints still
    | handle profile updates). Email verification is on so the User model's
    | MustVerifyEmail contract is enforced via the `verified` middleware.
    */
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updatePasswords(),
    ],
];
