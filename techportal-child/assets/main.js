/**
 * Tech Portal Pakistan - Main JavaScript
 * Version: 3.0.0
 */
(function() {
    'use strict';

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    var menuToggle = document.getElementById('menu-toggle');
    var primaryNav = document.getElementById('primary-nav');

    if (menuToggle && primaryNav) {
        menuToggle.addEventListener('click', function() {
            var isOpen = primaryNav.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', isOpen);
            menuToggle.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!primaryNav.contains(e.target) && !menuToggle.contains(e.target)) {
                primaryNav.classList.remove('open');
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.classList.remove('active');
            }
        });
    }

    // ============================================
    // SCROLL ANIMATIONS (IntersectionObserver)
    // ============================================
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -40px 0px'
        });

        document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
            observer.observe(el);
        });
    } else {
        // Fallback: show all immediately
        document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
            el.classList.add('visible');
        });
    }

    // ============================================
    // KEYBOARD SHORTCUT: / to search
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey) {
            var activeTag = document.activeElement.tagName;
            if (activeTag !== 'INPUT' && activeTag !== 'TEXTAREA' && activeTag !== 'SELECT') {
                e.preventDefault();
                var searchInput = document.querySelector('.search-overlay input, .btn-search');
                if (searchInput) searchInput.click();
            }
        }
    });

    // ============================================
    // HEADER SCROLL EFFECT
    // ============================================
    var header = document.querySelector('.site-header');
    var lastScroll = 0;

    if (header) {
        window.addEventListener('scroll', function() {
            var currentScroll = window.pageYOffset;
            if (currentScroll > 100) {
                header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
            } else {
                header.style.boxShadow = 'none';
            }
            lastScroll = currentScroll;
        }, { passive: true });
    }

    // ============================================
    // NEWSLETTER FORM
    // ============================================
    var newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var email = this.querySelector('input[type="email"]').value;
            if (email) {
                var btn = this.querySelector('button');
                btn.textContent = 'Subscribed!';
                btn.style.background = '#10B981';
                this.querySelector('input[type="email"]').value = '';
                setTimeout(function() {
                    btn.textContent = 'Subscribe';
                    btn.style.background = '';
                }, 3000);
            }
        });
    }

})();
