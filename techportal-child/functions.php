<?php
/**
 * Tech Portal Child Theme Functions
 */

// Enqueue parent and child theme styles
function techportal_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'techportal_child_enqueue_styles');

// Custom logo support
function techportal_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'techportal_custom_logo_setup');

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
    register_setting('techportal_settings', 'techportal_google_analytics_id');
}
add_action('admin_init', 'techportal_register_settings');
