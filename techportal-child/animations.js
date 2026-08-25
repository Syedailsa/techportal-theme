/**
 * Tech Portal Pakistan - Animations v2.0
 * Dark gradient theme with lively animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add scroll animation to posts
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.post, .hentry, article').forEach(post => {
        post.style.opacity = '0';
        post.style.transform = 'translateY(30px)';
        post.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(post);
    });

    // Check YouTube live status
    function checkLiveStatus() {
        const indicator = document.getElementById('live-indicator');
        const statusText = document.getElementById('live-status');
        
        if (!indicator || !statusText) return;
        
        // Simulated live check (replace with actual API call)
        fetch('/wp-admin/admin-ajax.php?action=check_youtube_live')
            .then(response => response.json())
            .then(data => {
                if (data.is_live) {
                    indicator.classList.add('live');
                    statusText.textContent = 'LIVE NOW';
                    indicator.style.animation = 'pulse 2s ease-in-out infinite';
                } else {
                    indicator.classList.remove('live');
                    statusText.textContent = 'Offline';
                    indicator.style.animation = 'none';
                }
            })
            .catch(error => {
                console.log('Live check:', error);
                statusText.textContent = 'Offline';
            });
    }

    // Check live status every 30 seconds
    checkLiveStatus();
    setInterval(checkLiveStatus, 30000);

    // Add hover effect to social links
    document.querySelectorAll('.social-links a, .social-share a').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.1)';
        });
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add typing effect to site description
    const siteDesc = document.querySelector('.site-description');
    if (siteDesc) {
        const text = siteDesc.textContent;
        siteDesc.textContent = '';
        let i = 0;
        
        function typeWriter() {
            if (i < text.length) {
                siteDesc.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        
        // Start typing after page load
        setTimeout(typeWriter, 1000);
    }

    // Add ripple effect to buttons
    document.querySelectorAll('.read-more-btn, .more-link, .nav-links a').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add parallax effect to morphing shapes
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const shapes = document.querySelectorAll('.morphing-shape');
        
        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.1;
            shape.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Add glow effect to navigation on scroll
    const nav = document.querySelector('.main-navigation');
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                nav.style.boxShadow = '0 0 30px rgba(230, 57, 70, 0.5)';
            } else {
                nav.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.4)';
            }
        });
    }

    // Animate category colors
    const categories = document.querySelectorAll('.category-name, .cat-links a, .posted-in a');
    const colors = ['#e63946', '#6c5ce7', '#0984e3', '#00b894', '#fdcb6e', '#e17055'];
    
    categories.forEach((cat, index) => {
        const colorIndex = index % colors.length;
        cat.style.background = `linear-gradient(135deg, ${colors[colorIndex]} 0%, ${colors[(colorIndex + 1) % colors.length]} 100%)`;
    });

    // Add loading animation
    const loader = document.createElement('div');
    loader.id = 'page-loader';
    loader.innerHTML = '<div class="loader"></div>';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0a0a0f;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.5s, visibility 0.5s;
    `;
    
    const loaderStyle = document.createElement('style');
    loaderStyle.textContent = `
        .loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(230, 57, 70, 0.3);
            border-top-color: #e63946;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    
    document.head.appendChild(loaderStyle);
    document.body.appendChild(loader);
    
    window.addEventListener('load', function() {
        setTimeout(() => {
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            setTimeout(() => loader.remove(), 500);
        }, 500);
    });

    // Add dynamic background particles
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 4px;
            height: 4px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            z-index: 0;
        `;
        
        document.body.appendChild(particle);
        
        const startX = Math.random() * window.innerWidth;
        const startY = window.innerHeight;
        
        particle.style.left = startX + 'px';
        particle.style.top = startY + 'px';
        
        const animation = particle.animate([
            { opacity: 0, transform: 'translateY(0)' },
            { opacity: 1, transform: `translateY(-${window.innerHeight}px)` },
            { opacity: 0, transform: `translateY(-${window.innerHeight}px)` }
        ], {
            duration: Math.random() * 3000 + 4000,
            easing: 'linear'
        });
        
        animation.onfinish = () => particle.remove();
    }

    // Create particles periodically
    setInterval(createParticle, 500);

    console.log('🚀 Tech Portal Pakistan - Dark Gradient Theme v2.0 Loaded');
});
