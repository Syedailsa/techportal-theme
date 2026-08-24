<?php
/**
 * Plugin Name: Tech Portal YouTube Integration
 * Description: YouTube live stream detection and video embedding for Tech Portal
 * Version: 1.0.0
 * Author: Tech Portal Team
 */

if (!defined('ABSPATH')) {
    exit;
}

class TechPortal_YouTube {
    private $api_key;
    private $channel_id;
    
    public function __construct() {
        $this->api_key = get_option('techportal_youtube_api_key', '');
        $this->channel_id = get_option('techportal_youtube_channel_id', '');
        
        add_shortcode('youtube_live', array($this, 'youtube_live_shortcode'));
        add_shortcode('youtube_video', array($this, 'youtube_video_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('techportal-youtube', plugin_dir_url(__FILE__) . 'youtube.js', array(), '1.0.0', true);
    }
    
    public function add_admin_menu() {
        add_options_page('YouTube Settings', 'YouTube Integration', 'manage_options', 'techportal-youtube', array($this, 'settings_page'));
    }
    
    public function register_settings() {
        register_setting('techportal_youtube_settings', 'techportal_youtube_api_key');
        register_setting('techportal_youtube_settings', 'techportal_youtube_channel_id');
    }
    
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>YouTube Integration Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('techportal_youtube_settings');
                do_settings_sections('techportal-youtube');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function is_live() {
        if (empty($this->api_key) || empty($this->channel_id)) {
            return false;
        }
        
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId={$this->channel_id}&type=video&eventType=live&key={$this->api_key}";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($body['items']);
    }
    
    public function get_live_video_id() {
        if (empty($this->api_key) || empty($this->channel_id)) {
            return false;
        }
        
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId={$this->channel_id}&type=video&eventType=live&key={$this->api_key}";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!empty($body['items'][0]['id']['videoId'])) {
            return $body['items'][0]['id']['videoId'];
        }
        
        return false;
    }
    
    public function youtube_live_shortcode($atts) {
        $atts = shortcode_atts(array(
            'width' => '100%',
            'height' => '500',
        ), $atts);
        
        $video_id = $this->get_live_video_id();
        
        if (!$video_id) {
            return '<div class="youtube-live-offline">No live stream currently available. Check back later!</div>';
        }
        
        return '<div class="youtube-live-container"><iframe width="' . esc_attr($atts['width']) . '" height="' . esc_attr($atts['height']) . '" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
    }
    
    public function youtube_video_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
            'width' => '100%',
            'height' => '500',
        ), $atts);
        
        if (empty($atts['id'])) {
            return '<p>Please provide a video ID: [youtube_video id="VIDEO_ID"]</p>';
        }
        
        return '<div class="youtube-video-container"><iframe width="' . esc_attr($atts['width']) . '" height="' . esc_attr($atts['height']) . '" src="https://www.youtube.com/embed/' . esc_attr($atts['id']) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
    }
}

new TechPortal_YouTube();
