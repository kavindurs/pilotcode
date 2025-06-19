<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip 2FA check if user is not authenticated or doesn't have 2FA enabled
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return $next($request);
        }

        // Skip 2FA check if user has already verified 2FA in this session
        if ($request->session()->get('2fa_verified') === $user->id) {
            return $next($request);
        }

        // Skip 2FA check for the 2FA verification routes themselves
        if ($request->routeIs('two-factor.*')) {
            return $next($request);
        }

        // Redirect to 2FA verification page
        return redirect()->route('two-factor.challenge');
    }
}
