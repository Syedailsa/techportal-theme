<?php
/**
 * Tech Portal Pakistan - Theme Functions
 * Version: 3.0.0
 */

if (!defined('ABSPATH')) exit;

// ============================================
// ENQUEUE
// ============================================
function techportal_enqueue_assets() {
    // Google Fonts - Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);

    // Theme CSS
    wp_enqueue_style('techportal-style', get_stylesheet_uri(), array('google-fonts'), '3.0.0');

    // Theme JS
    wp_enqueue_script('techportal-main', get_stylesheet_directory_uri() . '/assets/main.js', array(), '3.0.0', true);

    // Localize for AJAX
    wp_localize_script('techportal-main', 'techportal', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fetch_news_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'techportal_enqueue_assets');

// ============================================
// ALLOW IFRAME IN KSES (for YouTube embeds)
// ============================================
function techportal_allow_iframes($allowed) {
    $allowed['iframe'] = array(
        'src' => array(),
        'width' => array(),
        'height' => array(),
        'frameborder' => array(),
        'allowfullscreen' => array(),
        'allow' => array(),
        'loading' => array(),
        'title' => array(),
    );
    return $allowed;
}
add_filter('wp_kses_allowed_html', 'techportal_allow_iframes', 10, 2);

// ============================================
// SOCIAL SHARE BUTTONS
// ============================================
function techportal_social_share($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $url = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));

    ob_start();
    ?>
    <div class="social-share">
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" aria-label="Share on X">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
    </div>
    <?php
    return ob_get_clean();
}

// ============================================
// YOUTUBE OEmbed for thumbnails
// ============================================
function techportal_youtube_thumbnail($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
    if (isset($matches[1])) {
        return 'https://img.youtube.com/vi/' . $matches[1] . '/mqdefault.jpg';
    }
    return '';
}

function techportal_youtube_id($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
    return isset($matches[1]) ? $matches[1] : '';
}

// Widget registered by Tech Portal News Aggregator plugin

// ============================================
// REMOVE COLOR MAG DEFAULT STYLES
// ============================================
function techportal_remove_colormag_styles() {
    // Remove parent theme action hooks that add default styles
    remove_action('wp_enqueue_scripts', array('ColorMag_Enqueue_Scripts', 'colormag_scripts_styles_method'), 5);
}
add_action('after_setup_theme', 'techportal_remove_colormag_styles');

// ============================================
// CUSTOM EXCERPT LENGTH
// ============================================
function techportal_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'techportal_excerpt_length');

function techportal_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'techportal_excerpt_more');
