<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is for the seller guard
        if ($request->is('seller/*') && !Auth::guard('seller')->check()) {
            // If not authenticated as a seller, redirect to the seller login page
            return redirect()->route('seller.sellerLogin');
        }

        // Check if the user is authenticated using the default guard
        if (!$request->is('seller/*') && !Auth::guard('web')->check()) {
            // If not authenticated as a regular user, redirect to the user login page
            return redirect()->route('login');
        }

        // Allow the request to proceed if authenticated
        return $next($request);
    }
}

