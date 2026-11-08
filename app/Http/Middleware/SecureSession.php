<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureSession
{
    /**
     * Handle an incoming request.
     *
     * Validasi session security:
     * - User Agent harus sama dengan saat login
     * - IP Address harus sama (opsional, bisa dimatikan untuk mobile users)
     * - Regenerate session ID secara berkala
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip untuk guest users
        if (!auth()->check()) {
            return $next($request);
        }

        // Validasi User Agent
        $sessionUserAgent = session('user_agent');
        $currentUserAgent = $request->userAgent();

        if ($sessionUserAgent && $sessionUserAgent !== $currentUserAgent) {
            // User Agent berubah - kemungkinan session hijacking
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }

        // Simpan User Agent di session saat pertama kali (setelah login)
        if (!$sessionUserAgent) {
            session(['user_agent' => $currentUserAgent]);
        }

        // Validasi IP Address (Opsional - uncomment jika perlu)
        // $sessionIp = session('ip_address');
        // $currentIp = $request->ip();
        //
        // if ($sessionIp && $sessionIp !== $currentIp) {
        //     auth()->logout();
        //     session()->invalidate();
        //     session()->regenerateToken();
        //     return redirect()->route('login')->with('error', 'Alamat IP berubah. Silakan login kembali.');
        // }
        //
        // if (!$sessionIp) {
        //     session(['ip_address' => $currentIp]);
        // }

        // Regenerate session ID setiap 15 menit untuk keamanan
        $lastRegeneration = session('last_regeneration', 0);
        $now = time();

        if ($now - $lastRegeneration > 900) { // 15 menit
            $request->session()->regenerate();
            session(['last_regeneration' => $now]);
        }

        return $next($request);
    }
}
