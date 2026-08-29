</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-main">
            <!-- Brand -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/logo.svg'); ?>" alt="Tech Portal Pakistan">
                </div>
                <p>Pakistan's premier technology news portal covering IT, startups, cybersecurity, and artificial intelligence. Delivering accurate, timely tech journalism.</p>
                <div class="footer-social">
                    <a href="#" aria-label="X (Twitter)" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/feed/')); ?>" aria-label="RSS Feed">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6.18 15.64a2.18 2.18 0 0 1 2.18 2.18C8.36 19.01 7.37 20 6.18 20 5 20 4 19.01 4 17.82a2.18 2.18 0 0 1 2.18-2.18zM4 4.44A15.56 15.56 0 0 1 19.56 20h-2.83A12.73 12.73 0 0 0 4 7.27V4.44zm0 5.66a9.9 9.9 0 0 1 9.9 9.9h-2.83A7.07 7.07 0 0 0 4 12.93v-2.83z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Sections</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/category/it-news/')); ?>">IT News</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/startups/')); ?>">Startups</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/cybersecurity/')); ?>">Cybersecurity</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/ai/')); ?>">AI &amp; LLMs</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/live-shows/')); ?>">Live Shows</a></li>
                    <li><a href="<?php echo esc_url(home_url('/featured-videos/')); ?>">Videos</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>">About Us</a></li>
                    <li><a href="<?php echo esc_url(home_url('/editorial-policy/')); ?>">Editorial Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/takedown-policy/')); ?>">Takedown Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">Contact Us</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-col footer-newsletter">
                <h4>Newsletter</h4>
                <p>Get the latest tech news delivered to your inbox. No spam, unsubscribe anytime.</p>
                <form class="newsletter-form" onsubmit="return false;">
                    <input type="email" placeholder="your@email.com" aria-label="Email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Tech Portal Pakistan. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a>
                <a href="<?php echo esc_url(home_url('/takedown-policy/')); ?>">Takedown</a>
                <a href="<?php echo esc_url(home_url('/editorial-policy/')); ?>">Editorial</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
