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

    let searchTimeout;

    // Debounced search (wait 500ms after user stops typing)
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }

    // Auto-submit on category change
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }

    // Auto-submit on sort change
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
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
        minPriceInput.addEventListener('blur', function() {
            if (this.value && this.value !== filterForm.dataset.lastMinPrice) {
                filterForm.dataset.lastMinPrice = this.value;
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
        maxPriceInput.addEventListener('blur', function() {
            if (this.value && this.value !== filterForm.dataset.lastMaxPrice) {
                filterForm.dataset.lastMaxPrice = this.value;
                filterForm.submit();
            }
        });
    }

    // Show loading indicator when submitting
    filterForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        }
    });

    // Clear filters confirmation
    const clearBtn = filterForm.querySelector('a[href*="shop.index"]:not([class*="text-sage"])');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            const hasFilters = filterForm.querySelector('input[value]:not([value=""])') ||
                             filterForm.querySelector('select option:checked:not([value=""])');
            
            if (hasFilters) {
                const confirmed = confirm('Clear all filters?');
                if (!confirmed) {
                    e.preventDefault();
                }
            }
        });
    }
})();
