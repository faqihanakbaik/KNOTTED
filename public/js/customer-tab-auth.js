/**
 * Customer Multi-Tab Authentication
 * SHARED MODE: Customer can open same URL in multiple tabs
 * BUT: Logout from one tab logs out ALL tabs
 */

(function() {
    'use strict';

    // Check if user is customer (not admin)
    const isAdmin = document.querySelector('meta[name="admin-mode"]');
    const isAuthenticated = document.querySelector('meta[name="auth-required"]');
    
    if (!isAuthenticated || isAdmin) return;

    const STORAGE_KEY = 'customer_session_active';
    const CHANNEL_NAME = 'customer_auth_channel';

    // Use localStorage (shared between tabs) for customer
    // BroadcastChannel for cross-tab communication
    const authChannel = new BroadcastChannel(CHANNEL_NAME);

    // Mark this tab as having active session
    localStorage.setItem(STORAGE_KEY, 'true');

    // Listen for logout events from other tabs
    authChannel.addEventListener('message', function(event) {
        if (event.data.type === 'logout') {
            console.log('[Customer Auth] Logout detected from another tab');
            localStorage.removeItem(STORAGE_KEY);
            window.location.href = '/';
        }
    });

    // Also listen via storage event (fallback for older browsers)
    window.addEventListener('storage', function(e) {
        if (e.key === STORAGE_KEY && e.newValue === null) {
            console.log('[Customer Auth] Session cleared - redirecting');
            window.location.href = '/';
        }
    });

    // Intercept logout submissions
    document.addEventListener('DOMContentLoaded', function() {
        const logoutForms = document.querySelectorAll('form[action*="logout"]');
        
        logoutForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Notify all other tabs about logout
                authChannel.postMessage({ type: 'logout' });
                localStorage.removeItem(STORAGE_KEY);
                console.log('[Customer Auth] Logout broadcasted to all tabs');
            });
        });
    });

    // Periodic session check (every 30 seconds)
    setInterval(function() {
        // Verify session is still active
        if (!localStorage.getItem(STORAGE_KEY)) {
            console.warn('[Customer Auth] Session inactive - redirecting');
            window.location.href = '/';
        }
    }, 30000);

    console.log('[Customer Auth] Multi-tab authentication initialized');
})();
