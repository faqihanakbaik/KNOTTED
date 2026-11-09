/**
 * Admin Tab-Scoped Authentication
 * STRICT MODE: Each admin tab requires independent authentication
 * Copy-paste URL to new tab = must re-login
 */

(function() {
    'use strict';

    // Check if user is admin (meta tag set in admin layout)
    const isAdmin = document.querySelector('meta[name="admin-mode"]');
    if (!isAdmin) return;

    // Get tab token from server (passed via meta tag after login)
    const serverTabToken = document.querySelector('meta[name="tab-token"]');
    const tabToken = serverTabToken ? serverTabToken.getAttribute('content') : null;

    // CRITICAL: Use sessionStorage (tab-specific, NOT shared between tabs)
    const STORAGE_KEY = 'admin_tab_token';
    const storedToken = sessionStorage.getItem(STORAGE_KEY);

    // Initialize tab token
    if (tabToken && !storedToken) {
        // Fresh login: store token in this tab's sessionStorage
        sessionStorage.setItem(STORAGE_KEY, tabToken);
        console.log('[Admin Tab Auth] Tab token initialized');
    } else if (!storedToken) {
        // No token in sessionStorage = user copy-pasted URL
        console.warn('[Admin Tab Auth] No tab token found - redirecting to login');
        window.location.href = '/login';
        return;
    }

    const activeToken = storedToken || tabToken;

    // Add tab token to all AJAX requests
    const originalFetch = window.fetch;
    window.fetch = function(url, options = {}) {
        options.headers = options.headers || {};
        options.headers['X-Tab-Token'] = activeToken;
        options.credentials = 'same-origin';
        
        return originalFetch(url, options).then(response => {
            // Check if server rejected tab token (401)
            if (response.status === 401) {
                console.error('[Admin Tab Auth] Tab token rejected - redirecting to login');
                sessionStorage.removeItem(STORAGE_KEY);
                window.location.href = '/login';
            }
            return response;
        });
    };

    // Add tab token to all XMLHttpRequest
    const originalOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function() {
        originalOpen.apply(this, arguments);
        this.setRequestHeader('X-Tab-Token', activeToken);
    };

    // Add tab token to form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target;
        
        // Skip if already has token
        if (form.querySelector('input[name="_tab_token"]')) {
            return;
        }
        
        // Add hidden input with tab token
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_tab_token';
        input.value = activeToken;
        form.appendChild(input);
    });

    // Heartbeat: Verify tab token every 5 minutes
    setInterval(function() {
        fetch('/admin/dashboard', {
            method: 'HEAD',
            headers: {
                'X-Tab-Token': activeToken
            },
            credentials: 'same-origin'
        }).then(response => {
            if (response.status === 401 || response.status === 403) {
                console.error('[Admin Tab Auth] Session expired - redirecting to login');
                sessionStorage.removeItem(STORAGE_KEY);
                window.location.href = '/login';
            } else {
                console.log('[Admin Tab Auth] Heartbeat OK');
            }
        }).catch(err => {
            console.warn('[Admin Tab Auth] Heartbeat failed:', err);
        });
    }, 300000); // 5 minutes

    // Clear token on logout
    document.addEventListener('DOMContentLoaded', function() {
        const logoutForms = document.querySelectorAll('form[action*="logout"]');
        logoutForms.forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.removeItem(STORAGE_KEY);
                console.log('[Admin Tab Auth] Token cleared on logout');
            });
        });
    });

    // Warn on page unload (optional - can be removed if annoying)
    // window.addEventListener('beforeunload', function(e) {
    //     // Only warn if there are unsaved changes
    //     // This is just a placeholder - implement actual check
    // });

    console.log('[Admin Tab Auth] Admin tab security initialized');
})();
