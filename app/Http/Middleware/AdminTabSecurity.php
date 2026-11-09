<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTabSecurity
{
    /**
     * Handle an incoming request.
     * 
     * STRICT MODE FOR ADMIN ONLY:
     * - Admin MUST have valid tab token for each tab
     * - Copy-paste URL to new tab = must re-login
     * - Each admin tab has independent session verification
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated admin users
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Get tab token from header (set by JavaScript)
        $tabToken = $request->header('X-Tab-Token');

        // Get active admin tabs from session
        $adminTabs = session('admin_tabs', []);

        // STRICT: If no tab token, force re-login
        if (!$tabToken) {
            // This happens when admin copy-pastes URL to new tab
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Admin tab authentication required',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Admin harus login untuk setiap tab baru.');
        }

        // Validate tab token exists in admin_tabs
        if (!isset($adminTabs[$tabToken])) {
            // Tab token is invalid or expired
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid admin tab token',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Sesi admin tidak valid. Silakan login kembali.');
        }

        // Update last activity for this tab
        $adminTabs[$tabToken]['last_activity'] = time();
        $adminTabs[$tabToken]['user_agent'] = $request->userAgent();
        session(['admin_tabs' => $adminTabs]);

        // Cleanup expired tabs (30 minutes of inactivity)
        $this->cleanupExpiredTabs();

        // Extra security: Verify User Agent hasn't changed
        $storedUserAgent = $adminTabs[$tabToken]['user_agent'] ?? null;
        $currentUserAgent = $request->userAgent();

        if ($storedUserAgent && $storedUserAgent !== $currentUserAgent) {
            // Potential session hijacking attempt
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Security violation detected. Please login again.');
        }

        return $next($request);
    }

    /**
     * Cleanup expired admin tab tokens
     * Admin tabs expire faster (30 minutes) for security
     */
    private function cleanupExpiredTabs(): void
    {
        $adminTabs = session('admin_tabs', []);
        $now = time();
        $maxInactivity = 1800; // 30 minutes

        foreach ($adminTabs as $token => $data) {
            if ($now - ($data['last_activity'] ?? 0) > $maxInactivity) {
                unset($adminTabs[$token]);
            }
        }

        session(['admin_tabs' => $adminTabs]);
    }
}
