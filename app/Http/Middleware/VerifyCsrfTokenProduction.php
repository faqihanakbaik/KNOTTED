<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class VerifyCsrfTokenProduction
{
    /**
     * Handle an incoming request - Extended CSRF token lifetime for production
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // In production, extend session lifetime before CSRF check
        if (app()->environment('production')) {
            // Ensure session is started
            if (!$request->hasSession()) {
                $request->setLaravelSession(app('session.store'));
            }
            
            // Regenerate CSRF token if it's close to expiring
            $session = $request->session();
            
            if ($session) {
                $lastRegenerated = $session->get('csrf_token_regenerated_at', 0);
                $now = time();
                
                // Regenerate token every 30 minutes instead of 2 hours
                if (($now - $lastRegenerated) > 1800) {
                    $session->regenerateToken();
                    $session->put('csrf_token_regenerated_at', $now);
                    
                    Log::info('CSRF token regenerated for session: ' . $session->getId());
                }
            }
        }

        return $next($request);
    }
}
