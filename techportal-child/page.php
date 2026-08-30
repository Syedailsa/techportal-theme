<?php
/**
 * Page Template - Card Layout for all pages
 */
get_header();

while (have_posts()) : the_post();
    $page_slug = get_post_field('post_name', get_the_ID());
    $page_title = get_the_title();
    $page_content = get_the_content();

    // Extract h2/h3 sections from content for card rendering
    preg_match_all('/<h2>(.*?)<\/h2>(.*?)(?=<h2>|$)/s', $page_content, $h2_sections, PREG_SET_ORDER);
    preg_match_all('/<h3>(.*?)<\/h3>(.*?)(?=<h3>|<h2>|$)/s', $page_content, $h3_sections, PREG_SET_ORDER);

    // Featured Videos has its own template - WordPress routes it directly
    if ($page_slug === 'featured-videos') {
        return;
    }
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <?php if ($page_slug === 'about-us') : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                </span>
            <?php elseif ($page_slug === 'contact-us') : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
            <?php elseif ($page_slug === 'editorial-policy') : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                </span>
            <?php elseif ($page_slug === 'takedown-policy') : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </span>
            <?php elseif ($page_slug === 'privacy-policy-2' || $page_slug === 'privacy-policy') : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </span>
            <?php else : ?>
                <span class="page-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </span>
            <?php endif; ?>
            <h1><?php echo esc_html($page_title); ?></h1>
            <p class="page-subtitle">
                <?php
                $subtitles = array(
                    'about-us' => 'Pakistan\'s premier technology news platform',
                    'contact-us' => 'Get in touch with our team',
                    'editorial-policy' => 'Our commitment to journalistic integrity',
                    'takedown-policy' => 'PECA 2025 & SMPRA compliance',
                    'privacy-policy-2' => 'How we protect your data',
                    'privacy-policy' => 'How we protect your data',
                );
                echo esc_html($subtitles[$page_slug] ?? '');
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="page-content-section">
    <div class="container">
        <?php if ($page_slug === 'about-us') : ?>
            <!-- ABOUT US - Card Grid Layout -->
            <div class="info-card-grid">
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,212,170,0.12);color:var(--accent);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide accurate, timely, and comprehensive coverage of Pakistan's rapidly growing tech ecosystem, empowering readers with insights that matter.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,163,255,0.12);color:var(--accent-blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3>Our Team</h3>
                    <p>We are a team of experienced journalists and tech enthusiasts dedicated to bringing you the latest news and insights from Pakistan and beyond.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(139,92,246,0.12);color:var(--purple);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Editorial Standards</h3>
                    <p>All content is fact-checked before publication. We maintain independence from political and commercial influence, ensuring unbiased reporting.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(245,158,11,0.12);color:var(--orange);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <h3>Global Coverage</h3>
                    <p>From Karachi to Silicon Valley, we cover the stories that shape Pakistan's tech future and connect it to the global innovation landscape.</p>
                </div>
            </div>

            <!-- Contact Cards -->
            <div class="contact-card-grid" style="margin-top:var(--space-10);">
                <div class="info-card info-card-contact animate-on-scroll">
                    <h3>General Inquiries</h3>
                    <a href="mailto:info@techportal.27.jugaar.ai">info@techportal.27.jugaar.ai</a>
                </div>
                <div class="info-card info-card-contact animate-on-scroll">
                    <h3>Press Inquiries</h3>
                    <a href="mailto:press@techportal.27.jugaar.ai">press@techportal.27.jugaar.ai</a>
                </div>
            </div>

        <?php elseif ($page_slug === 'contact-us') : ?>
            <!-- CONTACT US - Card Grid Layout -->
            <div class="info-card-grid">
                <div class="info-card info-card-contact animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,212,170,0.12);color:var(--accent);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <h3>General Inquiries</h3>
                    <p>For general questions and feedback about Tech Portal Pakistan.</p>
                    <a href="mailto:info@techportal.27.jugaar.ai" class="contact-email">info@techportal.27.jugaar.ai</a>
                </div>
                <div class="info-card info-card-contact animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,163,255,0.12);color:var(--accent-blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <h3>Press Releases</h3>
                    <p>Submit press releases and media kits for review by our editorial team.</p>
                    <a href="mailto:press@techportal.27.jugaar.ai" class="contact-email">press@techportal.27.jugaar.ai</a>
                </div>
                <div class="info-card info-card-contact animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(139,92,246,0.12);color:var(--purple);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </div>
                    <h3>Technical Support</h3>
                    <p>Report bugs or get help with technical issues on our platform.</p>
                    <a href="mailto:support@techportal.27.jugaar.ai" class="contact-email">support@techportal.27.jugaar.ai</a>
                </div>
                <div class="info-card info-card-contact animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(239,68,68,0.12);color:var(--red);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <h3>Report a Bug</h3>
                    <p>Found something broken? Let us know and we'll fix it promptly.</p>
                    <a href="mailto:bugs@techportal.27.jugaar.ai" class="contact-email">bugs@techportal.27.jugaar.ai</a>
                </div>
            </div>

        <?php elseif ($page_slug === 'editorial-policy') : ?>
            <!-- EDITORIAL POLICY - Card Grid Layout -->
            <div class="info-card-grid">
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,212,170,0.12);color:var(--accent);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <h3>Content Standards</h3>
                    <ul class="info-card-list">
                        <li>All content is fact-checked before publication</li>
                        <li>Sources are verified and attributed</li>
                        <li>Corrections are published promptly</li>
                        <li>No paid content without disclosure</li>
                    </ul>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,163,255,0.12);color:var(--accent-blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Objectivity</h3>
                    <p>Our editorial team maintains independence from political and commercial influence. We disclose all conflicts of interest.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(245,158,11,0.12);color:var(--orange);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    <h3>Corrections</h3>
                    <p>If you find an error, please contact us at <a href="mailto:corrections@techportal.27.jugaar.ai">corrections@techportal.27.jugaar.ai</a>. We correct errors promptly and transparently.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(139,92,246,0.12);color:var(--purple);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <h3>PECA Compliance</h3>
                    <p>We operate in full compliance with Pakistan's Prevention of Electronic Crimes Act (PECA) 2025 and SMPRA regulations.</p>
                </div>
            </div>

        <?php elseif ($page_slug === 'takedown-policy') : ?>
            <!-- TAKEDOWN POLICY - Card Grid Layout -->
            <div class="info-card-grid">
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,163,255,0.12);color:var(--accent-blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <h3>Takedown Procedure</h3>
                    <ol class="info-card-list info-card-list-ordered">
                        <li>Complaints must be submitted in writing</li>
                        <li>Include specific URL and reason for takedown</li>
                        <li>Our team reviews within 24 hours</li>
                        <li>Decision communicated to complainant</li>
                    </ol>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(239,68,68,0.12);color:var(--red);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <h3>Grounds for Takedown</h3>
                    <ul class="info-card-list">
                        <li>Defamatory content</li>
                        <li>Fake news as defined under Section 26A</li>
                        <li>Copyright infringement</li>
                        <li>Privacy violations</li>
                    </ul>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(245,158,11,0.12);color:var(--orange);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3>Appeals</h3>
                    <p>Decisions can be appealed within 7 days to <a href="mailto:appeals@techportal.27.jugaar.ai">appeals@techportal.27.jugaar.ai</a>. All appeals are reviewed by a senior editor.</p>
                </div>
            </div>

        <?php elseif ($page_slug === 'privacy-policy-2' || $page_slug === 'privacy-policy') : ?>
            <!-- PRIVACY POLICY - Card Grid Layout -->
            <div class="info-card-grid">
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,212,170,0.12);color:var(--accent);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <h3>Data Collection</h3>
                    <p>We collect minimal personal data necessary for our services. This includes analytics data and optional newsletter subscriptions.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(0,163,255,0.12);color:var(--accent-blue);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Cookies</h3>
                    <p>Our site uses essential cookies for functionality and analytics cookies (with consent). You can manage cookie preferences in your browser.</p>
                </div>
                <div class="info-card animate-on-scroll">
                    <div class="info-card-icon" style="background:rgba(139,92,246,0.12);color:var(--purple);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Data Protection</h3>
                    <p>Your data is protected under Pakistan's data protection laws. We do not sell or share personal data with third parties.</p>
                </div>
                <div class="info-card info-card-contact animate-on-scroll">
                    <h3>Privacy Concerns</h3>
                    <p>For privacy-related inquiries, contact our Data Protection Officer.</p>
                    <a href="mailto:privacy@techportal.27.jugaar.ai" class="contact-email">privacy@techportal.27.jugaar.ai</a>
                </div>
            </div>

        <?php else : ?>
            <!-- GENERIC PAGE - Default card layout -->
            <div class="page-card">
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
