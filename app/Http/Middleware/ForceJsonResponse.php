<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // The email-verification link (GET /email/verify/{id}/{hash}) is opened
        // directly from the user's inbox in a browser and must keep its HTML
        // 302 redirect to the SPA. Every other auth/API request is JSON, so
        // Fortify never falls back to a browser redirect (which a cross-origin
        // SPA fetch would follow into a CORS-blocked URL → "Failed to fetch").
        if (! $request->routeIs('verification.verify')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
