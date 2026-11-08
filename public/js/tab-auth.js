/**
 * Tab-Scoped Authentication
 * 
 * Setiap tab menyimpan token unik di sessionStorage.
 * Token ini dikirim ke server pada setiap request untuk validasi.
 */

(function() {
    'use strict';

    // Check if we have a tab token in sessionStorage
    let tabToken = sessionStorage.getItem('tab_token');

    // If login just happened, save the token from flash session
    const flashToken = document.querySelector('meta[name="tab-token"]');
    if (flashToken && flashToken.content) {
        tabToken = flashToken.content;
        sessionStorage.setItem('tab_token', tabToken);
    }

    // If no token and user is logged in, redirect to login
    const isAuthPage = document.querySelector('meta[name="auth-required"]');
    if (isAuthPage && isAuthPage.content === 'true' && !tabToken) {
        window.location.href = '/login';
        return;
    }

    // Add tab token to all AJAX requests
    if (tabToken) {
        // Axios
        if (window.axios) {
            window.axios.defaults.headers.common['X-Tab-Token'] = tabToken;
        }

        // Fetch API
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            options.headers = options.headers || {};
            options.headers['X-Tab-Token'] = tabToken;
            return originalFetch(url, options);
        };

        // XMLHttpRequest
        const originalOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function() {
            originalOpen.apply(this, arguments);
            this.setRequestHeader('X-Tab-Token', tabToken);
        };

        // Add to all form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            
            // Check if form already has tab token input
            let tokenInput = form.querySelector('input[name="_tab_token"]');
            if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_tab_token';
                form.appendChild(tokenInput);
            }
            tokenInput.value = tabToken;
        });

        // Add to all links (as GET parameter for non-AJAX navigation)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (link && !link.hasAttribute('data-no-tab-token')) {
                const url = new URL(link.href, window.location.origin);
                
                // Only add to same-origin links
                if (url.origin === window.location.origin) {
                    url.searchParams.set('_tab_token', tabToken);
                    link.href = url.toString();
                }
            }
        });
    }

    // Clear token on logout
    window.addEventListener('beforeunload', function(e) {
        // Only clear if actually logging out (not just closing tab)
        if (document.activeElement.id === 'logout-button' || 
            document.activeElement.closest('form[action*="logout"]')) {
            sessionStorage.removeItem('tab_token');
        }
    });

    // Handle session expired
    window.addEventListener('storage', function(e) {
        if (e.key === 'session_expired' && e.newValue === 'true') {
            sessionStorage.removeItem('tab_token');
            window.location.href = '/login';
        }
    });
})();
