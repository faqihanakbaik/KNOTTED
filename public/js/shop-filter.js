/**
 * Shop Search & Filter
 * Auto-submit form when filters change
 * Debounced search input
 */

(function() {
    'use strict';

    const filterForm = document.querySelector('form[action*="shop"]');
    if (!filterForm) return;

    const searchInput = filterForm.querySelector('input[name="search"]');
    const categorySelect = filterForm.querySelector('select[name="category"]');
    const sortSelect = filterForm.querySelector('select[name="sort"]');
    const minPriceInput = filterForm.querySelector('input[name="min_price"]');
    const maxPriceInput = filterForm.querySelector('input[name="max_price"]');
    const submitBtn = filterForm.querySelector('button[type="submit"]');

    let searchTimeout;

    // Debounced search (wait 800ms after user stops typing)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const value = this.value.trim();
            
            // Only auto-submit if user typed at least 2 characters
            if (value.length >= 2 || value.length === 0) {
                searchTimeout = setTimeout(() => {
                    console.log('Auto-submitting search for:', value);
                    filterForm.submit();
                }, 800);
            }
        });

        // Also submit on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                console.log('Manual submit via Enter');
                filterForm.submit();
            }
        });
    }

    // Auto-submit on category change
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            console.log('Category changed to:', this.value);
            filterForm.submit();
        });
    }

    // Auto-submit on sort change
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            console.log('Sort changed to:', this.value);
            filterForm.submit();
        });
    }

    // Price filter: Submit on Enter key or blur
    if (minPriceInput) {
        minPriceInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterForm.submit();
            }
        });
    }

    if (maxPriceInput) {
        maxPriceInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterForm.submit();
            }
        });
    }

    // Manual submit button
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Manual submit button clicked');
            filterForm.submit();
        });
    }

    // Show loading indicator when submitting
    filterForm.addEventListener('submit', function() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        }
    });
})();
