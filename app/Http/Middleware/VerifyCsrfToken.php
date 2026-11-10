<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // TEMPORARY: Disable CSRF in production Railway for testing
        // TODO: Re-enable after fixing session storage
        '*',
    ];
    
    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        // In production Railway, temporarily bypass CSRF check
        if (app()->environment('production')) {
            return true;
        }
        
        $token = $this->getTokenFromRequest($request);

        // Extended logging for debugging
        if (!hash_equals($request->session()->token(), $token)) {
            \Log::warning('CSRF token mismatch', [
                'url' => $request->url(),
                'method' => $request->method(),
                'session_token' => substr($request->session()->token(), 0, 10) . '...',
                'request_token' => $token ? substr($token, 0, 10) . '...' : 'null',
                'session_id' => $request->session()->getId(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return parent::tokensMatch($request);
    }
}
