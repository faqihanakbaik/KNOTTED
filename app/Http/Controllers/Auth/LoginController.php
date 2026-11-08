<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Store session security data
            session([
                'user_agent' => $request->userAgent(),
                'last_regeneration' => time(),
                // 'ip_address' => $request->ip(), // Uncomment jika mau validasi IP
            ]);

            $user = Auth::user();
            
            // Role-based redirect
            if ($user->isAdmin()) {
                // Admin always goes to admin dashboard
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            }

            // Customer: redirect to intended page or home
            // This allows "add to cart then login" flow to work
            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
