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

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Generate tab token for session isolation
        $tabToken = bin2hex(random_bytes(32));
        
        if ($user->isAdmin()) {
            // ADMIN: Strict tab-scoped authentication
            // Each admin tab MUST have its own token
            $adminTabs = session('admin_tabs', []);
            $adminTabs[$tabToken] = [
                'created_at' => time(),
                'last_activity' => time(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ];
            session(['admin_tabs' => $adminTabs]);
            
            // Store tab token to be sent to client
            session(['current_tab_token' => $tabToken]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, Admin!')
                ->with('tab_token', $tabToken);
        } else {
            // CUSTOMER: Shared session across tabs (but can still track tabs)
            $customerTabs = session('customer_tabs', []);
            $customerTabs[$tabToken] = [
                'created_at' => time(),
                'last_activity' => time(),
            ];
            session(['customer_tabs' => $customerTabs]);
            session(['current_tab_token' => $tabToken]);
            
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back!')
                ->with('tab_token', $tabToken);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user ? $user->isAdmin() : false;
        
        Auth::logout();

        // Clear all tab tokens
        if ($isAdmin) {
            session()->forget('admin_tabs');
        } else {
            session()->forget('customer_tabs');
        }
        
        session()->forget('current_tab_token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with(
            'success',
            'Logged out successfully.'
        );
    }
}
