<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // If no roles specified, just check if authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Check if admin has required role
        if (!in_array($admin->role, $roles)) {
            // If it's a worker trying to access non-properties pages, redirect to properties
            if ($admin->role === 'worker') {
                return redirect()->route('admin.properties.index')
                    ->with('error', 'You only have access to the properties section.');
            }

            // For other unauthorized access
            return redirect()->back()
                ->with('error', 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
