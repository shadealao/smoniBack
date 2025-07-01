<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->user())
            return response()->json([
               'message' => 'Veuillez vous connecter.'
            ], 405);

        if(in_array(auth()->user()->role,array('learner', 'instructor')))
            return response()->json([
                'message' => "Vous n'avez pas l'autorisation."
             ], 405);

        return $next($request);
    }
}
