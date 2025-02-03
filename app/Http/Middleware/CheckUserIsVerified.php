<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() && ! Auth::user()->is_active) {
            $isActive = Auth::user()->is_active;
            Auth::logout();

            return redirect()->back()->withErrors(! $isActive ? 'Your account is not active. Please contact to administrator.' : 'Please verify your email address.');
        }

        return $response;
    }
}
