<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Update the last activity timestamp in the session
            Session::put('last_activity_time', now());
        }

        return $next($request);
    }
}

