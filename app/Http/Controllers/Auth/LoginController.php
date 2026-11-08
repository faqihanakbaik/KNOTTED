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

            // Generate unique tab token
            $tabToken = Str::random(64);

            // Store session security data
            session([
                'user_agent' => $request->userAgent(),
                'last_regeneration' => time(),
            ]);

            // Add this tab to active tabs
            $activeTabs = session('active_tabs', []);
            $activeTabs[$tabToken] = [
                'created_at' => time(),
                'last_activity' => time(),
                'user_agent' => $request->userAgent(),
            ];
            session(['active_tabs' => $activeTabs]);

            $user = Auth::user();
            
            // Role-based redirect with tab token
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!')
                    ->with('tab_token', $tabToken);
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back!')
                ->with('tab_token', $tabToken);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Remove current tab token
        $tabToken = $request->header('X-Tab-Token');
        if ($tabToken) {
            $activeTabs = session('active_tabs', []);
            unset($activeTabs[$tabToken]);
            session(['active_tabs' => $activeTabs]);
        }

        // If no more active tabs, full logout
        $activeTabs = session('active_tabs', []);
        if (empty($activeTabs)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
