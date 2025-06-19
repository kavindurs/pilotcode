<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip verification check if user is not authenticated
        if (!$user) {
            return $next($request);
        }

        // Skip verification check for Google authenticated users (they're auto-verified)
        if ($user->google_id) {
            return $next($request);
        }

        // Skip verification check if user is already verified
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        // Skip verification check for the verification routes themselves
        if ($request->routeIs('otp.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Redirect to OTP verification page
        $request->session()->put('pending_user_id', $user->id);
        return redirect()->route('otp.verify.form')
                         ->with('warning', 'Please verify your email address to continue.');
    }
}
