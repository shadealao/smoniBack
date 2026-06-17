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
    //
    // ForceJsonResponse sets `Accept: application/json` on every inbound auth
    // request so Fortify ALWAYS returns JSON and never falls back to a browser
    // 302 redirect. Without it, a request that loses the Accept header (e.g. a
    // stale SPA bundle) gets a 302 → home, which the SPA's cross-origin fetch
    // follows into a CORS-blocked URL and reports as "Failed to fetch".
    'middleware' => ['web', \App\Http\Middleware\ForceJsonResponse::class],

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
        // emailVerification() intentionally disabled: it gated nothing (no
        // route used the `verified` middleware, login never checked it), its
        // cross-origin verify link 500'd, and it sent rate-limited emails.
        // Removing it also drops the latent route('login')/route('notice')
        // 500 footguns. Re-add with a custom SPA-aware verify controller if
        // verification is ever actually required.
        Features::updatePasswords(),
    ],
];
