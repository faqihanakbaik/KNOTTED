<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SecureSession
{
    /**
     * Handle an incoming request.
     *
     * Validasi tab-scoped authentication:
     * - Setiap tab harus punya tab_token yang valid
     * - Tab token disimpan di sessionStorage (per-tab)
     * - Server validasi tab_token pada setiap request
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip untuk guest users dan AJAX requests tanpa token
        if (!auth()->check()) {
            return $next($request);
        }

        // Get tab token dari header atau query parameter (untuk link navigation)
        $tabToken = $request->header('X-Tab-Token') ?: $request->input('_tab_token');

        // Get active tab tokens dari server session
        $activeTabs = session('active_tabs', []);

        // Jika tidak ada tab token, redirect ke login
        if (!$tabToken) {
            // Ini terjadi saat user copy-paste URL ke tab baru
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tab not authenticated'], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Silakan login untuk tab ini.');
        }

        // Validasi tab token
        if (!isset($activeTabs[$tabToken])) {
            // Tab token tidak valid atau sudah expired
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid tab token'], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Sesi tab tidak valid. Silakan login kembali.');
        }

        // Update last activity untuk tab ini
        $activeTabs[$tabToken]['last_activity'] = time();
        session(['active_tabs' => $activeTabs]);

        // Cleanup expired tabs (lebih dari 2 jam tidak aktif)
        $this->cleanupExpiredTabs();

        // Validasi User Agent untuk security
        $sessionUserAgent = session('user_agent');
        $currentUserAgent = $request->userAgent();

        if ($sessionUserAgent && $sessionUserAgent !== $currentUserAgent) {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        if (!$sessionUserAgent) {
            session(['user_agent' => $currentUserAgent]);
        }

        return $next($request);
    }

    /**
     * Cleanup expired tab tokens
     */
    private function cleanupExpiredTabs(): void
    {
        $activeTabs = session('active_tabs', []);
        $now = time();
        $maxInactivity = 7200; // 2 hours

        foreach ($activeTabs as $token => $data) {
            if ($now - $data['last_activity'] > $maxInactivity) {
                unset($activeTabs[$token]);
            }
        }

        session(['active_tabs' => $activeTabs]);
    }
}
