<?php
/**
 * Tech Portal Child Theme Functions v2.1
 * Dark gradient theme with animations - iframe support
 */

// Enqueue parent and child theme styles
function techportal_child_enqueue_styles() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap', array(), null);
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), '2.1.0');
    wp_enqueue_script('techportal-animations', get_stylesheet_directory_uri() . '/animations.js', array(), '2.1.0', true);
}
add_action('wp_enqueue_scripts', 'techportal_child_enqueue_styles');

// Allow iframes in post content (YouTube embeds)
function techportal_allow_iframes($allowedtags) {
    if (isset($allowedtags['div'])) {
        $allowedtags['div']['style'] = true;
    }
    if (!isset($allowedtags['iframe'])) {
        $allowedtags['iframe'] = array(
            'src' => true,
            'width' => true,
            'height' => true,
            'frameborder' => true,
            'allowfullscreen' => true,
            'loading' => true,
            'title' => true,
            'allow' => true,
            'style' => true,
        );
    } else {
        $allowedtags['iframe']['src'] = true;
        $allowedtags['iframe']['width'] = true;
        $allowedtags['iframe']['height'] = true;
        $allowedtags['iframe']['frameborder'] = true;
        $allowedtags['iframe']['allowfullscreen'] = true;
        $allowedtags['iframe']['loading'] = true;
        $allowedtags['iframe']['title'] = true;
        $allowedtags['iframe']['allow'] = true;
        $allowedtags['iframe']['style'] = true;
    }
    return $allowedtags;
}
add_filter('wp_kses_allowed_html', 'techportal_allow_iframes', 10, 1);

// Custom logo support
function techportal_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'techportal_custom_logo_setup');

// Add floating characters
function techportal_floating_elements() {
    if (is_front_page() || is_home()) {
        ?>
        <div class="morphing-shape"></div>
        <div class="morphing-shape"></div>
        <div class="emoji-float">🚀</div>
        <div class="emoji-float">💡</div>
        <div class="emoji-float">⚡</div>
        <div class="emoji-float">🔥</div>
        <?php
    }
}
add_action('wp_body_open', 'techportal_floating_elements');

// Add theme settings page
function techportal_admin_menu() {
    add_theme_page('Tech Portal Settings', 'Tech Portal Settings', 'manage_options', 'techportal-settings', 'techportal_settings_page');
}
add_action('admin_menu', 'techportal_admin_menu');

function techportal_settings_page() {
    ?>
    <div class="wrap">
        <h1>Tech Portal Settings</h1>
        <p>Configure your Tech Portal settings here.</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('techportal_settings');
            do_settings_sections('techportal-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function techportal_register_settings() {
    register_setting('techportal_settings', 'techportal_youtube_api_key');
    register_setting('techportal_settings', 'techportal_youtube_channel_id');
    register_setting('techportal_settings', 'techportal_google_analytics_id');
}
add_action('admin_init', 'techportal_register_settings');

// Add animated read more button
function techportal_read_more_link() {
    return '<a class="read-more-btn" href="' . get_permalink() . '">Read More →</a>';
}
add_filter('the_content_more_link', 'techportal_read_more_link');

// Add social share buttons
function techportal_social_share() {
    if (is_single()) {
        $url = urlencode(get_permalink());
        $title = urlencode(get_the_title());
        ?>
        <div class="social-share">
            <span>Share:</span>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener">🐦</a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener">📘</a>
            <a href="https://linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener">💼</a>
            <a href="https://wa.me/?text=<?php echo $title . ' ' . $url; ?>" target="_blank" rel="noopener">📱</a>
        </div>
        <?php
    }
}
add_action('wp_footer', 'techportal_social_share');

// Add newsletter box
function techportal_newsletter_box() {
    if (is_front_page() || is_home()) {
        ?>
        <div class="newsletter-box">
            <h3>🚀 Stay Updated!</h3>
            <p>Get the latest tech news from Pakistan delivered to your inbox.</p>
            <form>
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
        <?php
    }
}
add_action('wp_footer', 'techportal_newsletter_box');

// Add live indicator
function techportal_live_indicator() {
    $api_key = get_option('techportal_youtube_api_key', '');
    $channel_id = get_option('techportal_youtube_channel_id', '');
    
    if (!empty($api_key) && !empty($channel_id)) {
        ?>
        <div class="youtube-live-indicator" id="live-indicator">
            <span class="live-dot"></span>
            <span id="live-status">Checking...</span>
        </div>
        <?php
    }
}
add_action('wp_body_open', 'techportal_live_indicator');

// Fix wpautop for iframes - prevent <p> tags around iframes
function techportal_fix_iframe_wpautop($content) {
    $pattern = '/<p>\s*(<iframe[^>]*>.*?<\/iframe>)\s*<\/p>/i';
    $replacement = '$1';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('the_content', 'techportal_fix_iframe_wpautop', 99);
add_filter('the_excerpt', 'techportal_fix_iframe_wpautop', 99);
