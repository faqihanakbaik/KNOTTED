import './bootstrap';

// Initialize Lucide Icons on page load and dynamic content
function initLucide() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Mobile menu toggle with smooth animation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide
    initLucide();
    
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
            // Animate menu items
            if (!mobileMenu.classList.contains('hidden')) {
                const menuItems = mobileMenu.querySelectorAll('a');
                menuItems.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 50);
                });
            }
        });
    }

    // Auto-hide flash messages with fade out
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(function(alert) {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Image lazy loading with fade in effect
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                    img.style.opacity = '1';
                });
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Add to cart button feedback
    const addToCartButtons = document.querySelectorAll('form[action*="cart/add"] button[type="submit"]');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.disabled) {
                this.classList.add('animate-pulse');
                const originalText = this.textContent;
                this.textContent = 'Adding...';
                
                setTimeout(() => {
                    this.classList.remove('animate-pulse');
                }, 1000);
            }
        });
    });

    // Quantity input validation
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const min = parseInt(this.getAttribute('min')) || 1;
            const max = parseInt(this.getAttribute('max')) || 999;
            let value = parseInt(this.value);
            
            if (value < min) this.value = min;
            if (value > max) this.value = max;
        });
    });

    // Form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner mr-2"></span> Processing...';
                
                // Re-enable after timeout (in case of validation errors)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                    initLucide(); // Reinitialize icons
                }, 3000);
            }
        });
    });

    // Dropdown menu with smooth transition
    const dropdownButtons = document.querySelectorAll('.relative.group > button');
    dropdownButtons.forEach(button => {
        const dropdown = button.nextElementSibling;
        if (dropdown) {
            button.addEventListener('mouseenter', () => {
                dropdown.style.display = 'block';
                setTimeout(() => {
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'translateY(0)';
                }, 10);
            });
            
            button.parentElement.addEventListener('mouseleave', () => {
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 200);
            });
        }
    });

    // Stagger animation for product cards
    const observeStagger = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('stagger-item');
                observeStagger.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card').forEach(card => {
        observeStagger.observe(card);
    });

    // Scroll to top button
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = '<i data-lucide="arrow-up" class="w-5 h-5"></i>';
    scrollBtn.className = 'fixed bottom-8 right-8 w-12 h-12 bg-mocha text-cream rounded-full shadow-lg opacity-0 transition-all duration-300 hover:bg-espresso z-50 flex items-center justify-center';
    scrollBtn.style.pointerEvents = 'none';
    document.body.appendChild(scrollBtn);
    
    // Initialize icon in scroll button
    setTimeout(() => initLucide(), 100);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollBtn.style.opacity = '1';
            scrollBtn.style.pointerEvents = 'auto';
        } else {
            scrollBtn.style.opacity = '0';
            scrollBtn.style.pointerEvents = 'none';
        }
    });

    scrollBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Cart count animation
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const observer = new MutationObserver(() => {
            cartCount.classList.add('animate-pulse');
            setTimeout(() => {
                cartCount.classList.remove('animate-pulse');
            }, 500);
        });
        observer.observe(cartCount, { childList: true, characterData: true, subtree: true });
    }
});
