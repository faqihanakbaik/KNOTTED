<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures the user is authenticated AND has the 'customer' role.
     * This prevents admin users from accidentally accessing customer routes
     * and provides proper role-based access control.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Store intended URL for redirect after login
            return redirect()->guest(route('login'));
        }
        
        // Check if user has customer role
        $user = auth()->user();
        if ($user->role !== 'customer') {
            abort(403, 'Unauthorized access. Customer area only.');
        }

        return $next($request);
    }
}
