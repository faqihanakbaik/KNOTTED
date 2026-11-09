/**
 * Admin Filter Auto-Submit
 * Auto-submit form when select filters change
 * Debounced search input
 */

(function() {
    'use strict';

    // Find all admin filter forms
    const filterForms = document.querySelectorAll('form[action*="admin"]');

    filterForms.forEach(form => {
        const searchInput = form.querySelector('input[name="search"]');
        const selectInputs = form.querySelectorAll('select');
        let searchTimeout;

        // Debounced search (wait 500ms after user stops typing)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    form.submit();
                }, 500);
            });
        }

        // Auto-submit on select change
        selectInputs.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });

        // Auto-submit date inputs on change
        const dateInputs = form.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                form.submit();
            });
        });

        // Show loading on submit
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Loading...';
            }
        });
    });
})();
