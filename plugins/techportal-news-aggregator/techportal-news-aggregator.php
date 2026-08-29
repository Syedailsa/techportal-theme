<?php
/**
 * Plugin Name: Tech Portal News Aggregator
 * Description: Fetches real-time tech news from multiple REST APIs with auto-update
 * Version: 1.0.0
 * Author: Tech Portal Team
 */

if (!defined('ABSPATH')) exit;

class TechPortal_News_Aggregator {
    
    private $apis = array();
    private $cache_key = 'techportal_news_cache';
    private $cache_duration = 1800; // 30 minutes
    
    public function __construct() {
        // Free News APIs
        $this->apis = array(
            'gnews' => array(
                'name' => 'GNews',
                'base_url' => 'https://gnews.io/api/v4',
                'api_key_option' => 'techportal_gnews_api_key',
                'free_tier' => true,
            ),
            'newsdata' => array(
                'name' => 'NewsData.io',
                'base_url' => 'https://newsdata.io/api/1',
                'api_key_option' => 'techportal_newsdata_api_key',
                'free_tier' => true,
            ),
            'currents' => array(
                'name' => 'Currents API',
                'base_url' => 'https://api.currentsapi.services/v1',
                'api_key_option' => 'techportal_currents_api_key',
                'free_tier' => true,
            ),
            'thenewsapi' => array(
                'name' => 'TheNewsAPI',
                'base_url' => 'https://api.thenewsapi.com/api/v1',
                'api_key_option' => 'techportal_thenewsapi_key',
                'free_tier' => true,
            ),
            'mediastack' => array(
                'name' => 'Mediastack',
                'base_url' => 'http://api.mediastack.com/v1',
                'api_key_option' => 'techportal_mediastack_key',
                'free_tier' => true,
            ),
        );
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Shortcodes
        add_shortcode('tech_news', array($this, 'news_shortcode'));
        add_shortcode('tech_news_grid', array($this, 'news_grid_shortcode'));
        add_shortcode('startup_news', array($this, 'startup_news_shortcode'));
        
        // Widget
        add_action('widgets_init', function() {
            register_widget('TechPortal_News_Widget');
        });
        
        // REST API endpoint for AJAX
        add_action('wp_ajax_fetch_news', array($this, 'ajax_fetch_news'));
        add_action('wp_ajax_nopriv_fetch_news', array($this, 'ajax_fetch_news'));
        
        // Auto-fetch on theme init
        add_action('init', array($this, 'maybe_auto_fetch'));
        
        // Cron for auto-update
        add_filter('cron_schedules', array($this, 'add_cron_interval'));
        add_action('techportal_fetch_news_cron', array($this, 'auto_fetch_news'));
        
        if (!wp_next_scheduled('techportal_fetch_news_cron')) {
            wp_schedule_event(time(), 'twice_daily', 'techportal_fetch_news_cron');
        }
    }
    
    public function add_cron_interval($schedules) {
        $schedules['twice_daily'] = array(
            'interval' => 43200,
            'display' => 'Twice Daily'
        );
        return $schedules;
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'News Aggregator',
            'News Aggregator',
            'manage_options',
            'techportal-news',
            array($this, 'admin_page'),
            'dashicons-schedule',
            30
        );
        
        add_submenu_page(
            'techportal-news',
            'API Settings',
            'API Settings',
            'manage_options',
            'techportal-news-settings',
            array($this, 'settings_page')
        );
        
        add_submenu_page(
            'techportal-news',
            'Manual Fetch',
            'Manual Fetch',
            'manage_options',
            'techportal-news-fetch',
            array($this, 'fetch_page')
        );
    }
    
    public function register_settings() {
        register_setting('techportal_news_settings', 'techportal_gnews_api_key');
        register_setting('techportal_news_settings', 'techportal_newsdata_api_key');
        register_setting('techportal_news_settings', 'techportal_currents_api_key');
        register_setting('techportal_news_settings', 'techportal_thenewsapi_key');
        register_setting('techportal_news_settings', 'techportal_mediastack_key');
        register_setting('techportal_news_settings', 'techportal_news_auto_fetch');
        register_setting('techportal_news_settings', 'techportal_news_categories');
    }
    
    public function admin_page() {
        $this->render_admin_page('dashboard');
    }
    
    public function settings_page() {
        $this->render_admin_page('settings');
    }
    
    public function fetch_page() {
        $this->render_admin_page('fetch');
    }
    
    private function render_admin_page($tab) {
        ?>
        <div class="wrap">
            <h1>Tech Portal News Aggregator</h1>
            <nav class="nav-tab-wrapper">
                <a href="?page=techportal-news" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
                <a href="?page=techportal-news-settings" class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>">API Settings</a>
                <a href="?page=techportal-news-fetch" class="nav-tab <?php echo $tab === 'fetch' ? 'nav-tab-active' : ''; ?>">Manual Fetch</a>
            </nav>
            
            <?php if ($tab === 'dashboard'): ?>
                <div class="card" style="max-width:800px;padding:20px;margin-top:20px;">
                    <h2>News Feed Status</h2>
                    <p><strong>Last Fetch:</strong> <?php echo get_option('techportal_last_fetch', 'Never'); ?></p>
                    <p><strong>Articles Fetched:</strong> <?php echo get_option('techportal_total_fetched', 0); ?></p>
                    <p><strong>Active APIs:</strong> <?php echo count($this->get_active_apis()); ?></p>
                    <p><strong>Cache Status:</strong> <?php echo $this->get_cache_status(); ?></p>
                    
                    <h3>Active News Sources</h3>
                    <ul>
                    <?php foreach ($this->get_active_apis() as $key => $api): ?>
                        <li><?php echo $api['name']; ?> - Active</li>
                    <?php endforeach; ?>
                    </ul>
                    
                    <h3>Quick Actions</h3>
                    <a href="<?php echo admin_url('admin.php?page=techportal-news-fetch&action=fetch_now'); ?>" class="button button-primary">Fetch News Now</a>
                    <a href="<?php echo admin_url('admin.php?page=techportal-news-settings'); ?>" class="button">Configure APIs</a>
                </div>
            <?php endif; ?>
            
            <?php if ($tab === 'settings'): ?>
                <form method="post" action="options.php" style="max-width:800px;margin-top:20px;">
                    <?php settings_fields('techportal_news_settings'); ?>
                    
                    <h2>API Keys (Get free keys from each provider)</h2>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="techportal_gnews_api_key">GNews API Key</label></th>
                            <td>
                                <input type="text" name="techportal_gnews_api_key" value="<?php echo get_option('techportal_gnews_api_key', ''); ?>" class="regular-text" />
                                <p class="description">Get free key at <a href="https://gnews.io" target="_blank">gnews.io</a> (100 requests/day)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="techportal_newsdata_api_key">NewsData.io API Key</label></th>
                            <td>
                                <input type="text" name="techportal_newsdata_api_key" value="<?php echo get_option('techportal_newsdata_api_key', ''); ?>" class="regular-text" />
                                <p class="description">Get free key at <a href="https://newsdata.io" target="_blank">newsdata.io</a> (200 requests/day)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="techportal_currents_api_key">Currents API Key</label></th>
                            <td>
                                <input type="text" name="techportal_currents_api_key" value="<?php echo get_option('techportal_currents_api_key', ''); ?>" class="regular-text" />
                                <p class="description">Get free key at <a href="https://currentsapi.services" target="_blank">currentsapi.services</a> (600 requests/day)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="techportal_thenewsapi_key">TheNewsAPI Key</label></th>
                            <td>
                                <input type="text" name="techportal_thenewsapi_key" value="<?php echo get_option('techportal_thenewsapi_key', ''); ?>" class="regular-text" />
                                <p class="description">Get free key at <a href="https://thenewsapi.com" target="_blank">thenewsapi.com</a> (100 requests/day)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="techportal_mediastack_key">Mediastack Key</label></th>
                            <td>
                                <input type="text" name="techportal_mediastack_key" value="<?php echo get_option('techportal_mediastack_key', ''); ?>" class="regular-text" />
                                <p class="description">Get free key at <a href="https://mediastack.com" target="_blank">mediastack.com</a> (100 requests/month)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="techportal_news_auto_fetch">Auto-Fetch</label></th>
                            <td>
                                <select name="techportal_news_auto_fetch">
                                    <option value="1" <?php selected(get_option('techportal_news_auto_fetch'), '1'); ?>>Enabled</option>
                                    <option value="0" <?php selected(get_option('techportal_news_auto_fetch'), '0'); ?>>Disabled</option>
                                </select>
                                <p class="description">Automatically fetch news twice daily</p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save Settings'); ?>
                </form>
            <?php endif; ?>
            
            <?php if ($tab === 'fetch'): ?>
                <div style="max-width:800px;margin-top:20px;">
                    <h2>Manual News Fetch</h2>
                    <p>Select a category and fetch the latest news:</p>
                    
                    <form method="post" id="fetch-news-form">
                        <?php wp_nonce_field('fetch_news_nonce', 'fetch_nonce'); ?>
                        <table class="form-table">
                            <tr>
                                <th>Category</th>
                                <td>
                                    <select name="category" id="news-category">
                                        <option value="technology">Technology</option>
                                        <option value="tech">Tech</option>
                                        <option value="artificial-intelligence">AI</option>
                                        <option value="cybersecurity">Cybersecurity</option>
                                        <option value="startups">Startups</option>
                                        <option value="cloud">Cloud Computing</option>
                                        <option value="programming">Programming</option>
                                        <option value="gadgets">Gadgets</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Sort By</th>
                                <td>
                                    <select name="sort_by" id="sort-by">
                                        <option value="publishedAt">Latest First</option>
                                        <option value="popularity">Most Popular</option>
                                        <option value="relevance">Most Relevant</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Number of Articles</th>
                                <td>
                                    <select name="count" id="article-count">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <button type="submit" class="button button-primary" id="fetch-btn">Fetch News Now</button>
                    </form>
                    
                    <div id="fetch-results" style="margin-top:20px;"></div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    public function get_active_apis() {
        $active = array();
        foreach ($this->apis as $key => $api) {
            $api_key = get_option($api['api_key_option'], '');
            if (!empty($api_key)) {
                $active[$key] = $api;
            }
        }
        return $active;
    }
    
    private function get_cache_status() {
        $cached = get_transient($this->cache_key);
        if ($cached !== false) {
            return 'Cached (' . count($cached) . ' articles)';
        }
        return 'No cache';
    }
    
    public function maybe_auto_fetch() {
        if (get_option('techportal_news_auto_fetch') === '1' && !is_admin()) {
            $last_fetch = get_option('techportal_last_fetch_time', 0);
            if (time() - $last_fetch > 43200) { // 12 hours
                $this->fetch_all_news();
            }
        }
    }
    
    public function auto_fetch_news() {
        $this->fetch_all_news();
    }
    
    public function fetch_all_news($category = 'technology', $sort = 'publishedAt', $count = 20) {
        $active_apis = $this->get_active_apis();
        
        if (empty($active_apis)) {
            return array('error' => 'No API keys configured');
        }
        
        $all_articles = array();
        
        foreach ($active_apis as $key => $api) {
            $articles = $this->fetch_from_api($key, $api, $category, $sort, $count);
            if (!empty($articles)) {
                $all_articles = array_merge($all_articles, $articles);
            }
        }
        
        // Deduplicate by title similarity
        $all_articles = $this->deduplicate_articles($all_articles);
        
        // Sort
        $all_articles = $this->sort_articles($all_articles, $sort);
        
        // Limit
        $all_articles = array_slice($all_articles, 0, $count);
        
        // Cache
        set_transient($this->cache_key, $all_articles, $this->cache_duration);
        
        // Update stats
        update_option('techportal_last_fetch', current_time('mysql'));
        update_option('techportal_last_fetch_time', time());
        update_option('techportal_total_fetched', count($all_articles));
        
        return $all_articles;
    }
    
    private function fetch_from_api($provider, $api, $category, $sort, $count) {
        $api_key = get_option($api['api_key_option'], '');
        if (empty($api_key)) return array();
        
        $articles = array();
        
        switch ($provider) {
            case 'gnews':
                $articles = $this->fetch_gnews($api_key, $category, $sort, $count);
                break;
            case 'newsdata':
                $articles = $this->fetch_newsdata($api_key, $category, $sort, $count);
                break;
            case 'currents':
                $articles = $this->fetch_currents($api_key, $category, $sort, $count);
                break;
            case 'thenewsapi':
                $articles = $this->fetch_thenewsapi($api_key, $category, $sort, $count);
                break;
            case 'mediastack':
                $articles = $this->fetch_mediastack($api_key, $category, $sort, $count);
                break;
        }
        
        return $articles;
    }
    
    private function fetch_gnews($api_key, $category, $sort, $count) {
        $query = $this->get_category_query($category);
        $url = "https://gnews.io/api/v4/search?q={$query}&lang=en&max={$count}&apikey={$api_key}";
        
        $response = wp_remote_get($url, array('timeout' => 15));
        
        if (is_wp_error($response)) return array();
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $articles = array();
        
        if (!empty($body['articles'])) {
            foreach ($body['articles'] as $article) {
                $articles[] = array(
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['content'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image' => $article['image'] ?? '',
                    'source' => $article['source']['name'] ?? 'GNews',
                    'published' => $article['publishedAt'] ?? '',
                    'provider' => 'GNews',
                );
            }
        }
        
        return $articles;
    }
    
    private function fetch_newsdata($api_key, $category, $sort, $count) {
        $query = $this->get_category_query($category);
        $url = "https://newsdata.io/api/1/news?apikey={$api_key}&q={$query}&language=en&size={$count}";
        
        if ($sort === 'popularity') {
            $url .= "&sortby=popularity";
        } else {
            $url .= "&sortby=publishedAt";
        }
        
        $response = wp_remote_get($url, array('timeout' => 15));
        
        if (is_wp_error($response)) return array();
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $articles = array();
        
        if (!empty($body['results'])) {
            foreach ($body['results'] as $article) {
                $articles[] = array(
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['content'] ?? '',
                    'url' => $article['link'] ?? '',
                    'image' => $article['image_url'] ?? '',
                    'source' => $article['source_name'] ?? 'NewsData',
                    'published' => $article['pubDate'] ?? '',
                    'provider' => 'NewsData.io',
                );
            }
        }
        
        return $articles;
    }
    
    private function fetch_currents($api_key, $category, $sort, $count) {
        $query = $this->get_category_query($category);
        $url = "https://api.currentsapi.services/v1/search?keywords={$query}&apiKey={$api_key}&pageSize={$count}";
        
        $response = wp_remote_get($url, array('timeout' => 15));
        
        if (is_wp_error($response)) return array();
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $articles = array();
        
        if (!empty($body['news'])) {
            foreach ($body['news'] as $article) {
                $articles[] = array(
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image' => $article['image'] ?? '',
                    'source' => $article['author'] ?? 'Currents',
                    'published' => $article['published'] ?? '',
                    'provider' => 'Currents API',
                );
            }
        }
        
        return $articles;
    }
    
    private function fetch_thenewsapi($api_key, $category, $sort, $count) {
        $query = $this->get_category_query($category);
        $url = "https://api.thenewsapi.com/api/v1/news/top?api_token={$api_key}&search={$query}&locale=us&limit={$count}";
        
        $response = wp_remote_get($url, array('timeout' => 15));
        
        if (is_wp_error($response)) return array();
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $articles = array();
        
        if (!empty($body['data'])) {
            foreach ($body['data'] as $article) {
                $articles[] = array(
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image' => $article['image_url'] ?? '',
                    'source' => $article['source'] ?? 'TheNewsAPI',
                    'published' => $article['published_at'] ?? '',
                    'provider' => 'TheNewsAPI',
                );
            }
        }
        
        return $articles;
    }
    
    private function fetch_mediastack($api_key, $category, $sort, $count) {
        $query = $this->get_category_query($category);
        $url = "http://api.mediastack.com/v1/news?access_key={$api_key}&keywords={$query}&languages=en&limit={$count}";
        
        $response = wp_remote_get($url, array('timeout' => 15));
        
        if (is_wp_error($response)) return array();
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $articles = array();
        
        if (!empty($body['data'])) {
            foreach ($body['data'] as $article) {
                $articles[] = array(
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image' => $article['image'] ?? '',
                    'source' => $article['source'] ?? 'Mediastack',
                    'published' => $article['published_at'] ?? '',
                    'provider' => 'Mediastack',
                );
            }
        }
        
        return $articles;
    }
    
    private function get_category_query($category) {
        $queries = array(
            'technology' => 'technology OR tech OR software OR hardware OR AI OR artificial intelligence',
            'tech' => 'technology OR tech OR software OR hardware',
            'artificial-intelligence' => 'artificial intelligence OR AI OR machine learning OR deep learning OR neural network',
            'cybersecurity' => 'cybersecurity OR cyber security OR hacking OR data breach OR malware OR ransomware',
            'startups' => 'startup OR venture capital OR funding OR seed round OR series A OR entrepreneurship',
            'cloud' => 'cloud computing OR AWS OR Azure OR Google Cloud OR SaaS OR DevOps',
            'programming' => 'programming OR coding OR developer OR JavaScript OR Python OR React',
            'gadgets' => 'gadgets OR smartphone OR iPhone OR Android OR wearable OR IoT',
        );
        
        return urlencode($queries[$category] ?? $queries['technology']);
    }
    
    private function deduplicate_articles($articles) {
        $unique = array();
        $seen_titles = array();
        
        foreach ($articles as $article) {
            $normalized = strtolower(trim($article['title']));
            $normalized = preg_replace('/[^a-z0-9]/', '', $normalized);
            
            if (!in_array($normalized, $seen_titles) && !empty($article['title'])) {
                $seen_titles[] = $normalized;
                $unique[] = $article;
            }
        }
        
        return $unique;
    }
    
    private function sort_articles($articles, $sort) {
        switch ($sort) {
            case 'popularity':
                usort($articles, function($a, $b) {
                    return strtotime($b['published']) - strtotime($a['published']);
                });
                break;
            case 'relevance':
                usort($articles, function($a, $b) {
                    return strlen($b['description']) - strlen($a['description']);
                });
                break;
            case 'publishedAt':
            default:
                usort($articles, function($a, $b) {
                    return strtotime($b['published']) - strtotime($a['published']);
                });
                break;
        }
        
        return $articles;
    }
    
    public function ajax_fetch_news() {
        check_ajax_referer('fetch_news_nonce', 'nonce');
        
        $category = sanitize_text_field($_POST['category'] ?? 'technology');
        $sort = sanitize_text_field($_POST['sort_by'] ?? 'publishedAt');
        $count = intval($_POST['count'] ?? 10);
        
        $articles = $this->fetch_all_news($category, $sort, $count);
        
        wp_send_json_success(array(
            'articles' => $articles,
            'count' => count($articles),
            'fetched_at' => current_time('mysql'),
        ));
    }
    
    // Shortcode: [tech_news count="10" category="technology"]
    public function news_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 10,
            'category' => 'technology',
            'sort' => 'publishedAt',
        ), $atts);
        
        $cached = get_transient($this->cache_key);
        $articles = $cached ?: $this->fetch_all_news($atts['category'], $atts['sort'], $atts['count']);
        
        if (empty($articles)) {
            return '<p>No news articles available.</p>';
        }
        
        $output = '<div class="tech-news-feed">';
        foreach (array_slice($articles, 0, $atts['count']) as $article) {
            $output .= $this->render_news_card($article);
        }
        $output .= '</div>';
        
        return $output;
    }
    
    // Shortcode: [tech_news_grid count="6"]
    public function news_grid_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 6,
            'category' => 'technology',
        ), $atts);
        
        $cached = get_transient($this->cache_key);
        $articles = $cached ?: $this->fetch_all_news($atts['category']);
        
        if (empty($articles)) {
            return '<p>No news available.</p>';
        }
        
        $output = '<div class="tech-news-grid">';
        foreach (array_slice($articles, 0, $atts['count']) as $article) {
            $output .= $this->render_news_card_grid($article);
        }
        $output .= '</div>';
        
        return $output;
    }
    
    // Shortcode: [startup_news count="5"]
    public function startup_news_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 5,
        ), $atts);
        
        $cached = get_transient($this->cache_key . '_startups');
        $articles = $cached ?: $this->fetch_all_news('startups', 'publishedAt', $atts['count']);
        
        if (empty($articles)) {
            return '<p>No startup news available.</p>';
        }
        
        $output = '<div class="startup-news-feed">';
        foreach (array_slice($articles, 0, $atts['count']) as $article) {
            $output .= $this->render_news_card($article);
        }
        $output .= '</div>';
        
        return $output;
    }
    
    private function render_news_card($article) {
        if (!is_array($article)) return '';
        
        $image = !empty($article['image']) 
            ? '<img src="' . esc_url($article['image']) . '" alt="' . esc_attr($article['title'] ?? '') . '" loading="lazy" />' 
            : '';
        
        $source = esc_html($article['source'] ?? '');
        $published = $this->time_ago($article['published'] ?? '');
        
        return '<div class="news-card">
            ' . ($image ? '<div class="news-card-image">' . $image . '</div>' : '') . '
            <div class="news-card-content">
                <h3><a href="' . esc_url($article['url']) . '" target="_blank" rel="noopener">' . esc_html($article['title']) . '</a></h3>
                <p>' . esc_html(wp_trim_words($article['description'] ?? '', 30)) . '</p>
                <div class="news-card-meta">
                    <span class="news-source">' . $source . '</span>
                    <span class="news-time">' . $published . '</span>
                </div>
            </div>
        </div>';
    }
    
    private function render_news_card_grid($article) {
        if (!is_array($article)) return '';
        
        $image = !empty($article['image']) 
            ? '<img src="' . esc_url($article['image']) . '" alt="' . esc_attr($article['title'] ?? '') . '" loading="lazy" />' 
            : '<div class="news-placeholder">📰</div>';
        
        return '<div class="news-card-grid">
            <div class="news-card-grid-image">' . $image . '</div>
            <h4><a href="' . esc_url($article['url']) . '" target="_blank" rel="noopener">' . esc_html($article['title']) . '</a></h4>
            <p>' . esc_html(wp_trim_words($article['description'] ?? '', 20)) . '</p>
            <span class="news-source-small">' . esc_html($article['source'] ?? '') . '</span>
        </div>';
    }
    
    private function time_ago($datetime) {
        if (empty($datetime)) return '';
        
        try {
            $now = new DateTime();
            $past = new DateTime($datetime);
            $diff = $now->diff($past);
        } catch (Exception $e) {
            return '';
        }
        
        if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        
        return 'Just now';
    }
}

// News Widget
class TechPortal_News_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct('techportal_news_widget', 'Tech News Widget');
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $args['before_title'] . ($instance['title'] ?? 'Latest Tech News') . $args['after_title'];
        
        $aggregator = new TechPortal_News_Aggregator();
        $cached = get_transient('techportal_news_cache');
        $articles = $cached ?: $aggregator->fetch_all_news(
            $instance['category'] ?? 'technology',
            'publishedAt',
            $instance['count'] ?? 5
        );
        
        echo '<ul class="tech-news-widget">';
        foreach (array_slice($articles, 0, $instance['count'] ?? 5) as $article) {
            echo '<li>
                <a href="' . esc_url($article['url']) . '" target="_blank" rel="noopener">' . esc_html($article['title']) . '</a>
                <span class="news-source-small">' . esc_html($article['source'] ?? '') . '</span>
            </li>';
        }
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title'] ?? 'Latest Tech News'); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>">Category:</label>
            <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat">
                <option value="technology" <?php selected($instance['category'] ?? '', 'technology'); ?>>Technology</option>
                <option value="startups" <?php selected($instance['category'] ?? '', 'startups'); ?>>Startups</option>
                <option value="cybersecurity" <?php selected($instance['category'] ?? '', 'cybersecurity'); ?>>Cybersecurity</option>
                <option value="artificial-intelligence" <?php selected($instance['category'] ?? '', 'artificial-intelligence'); ?>>AI</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of items:</label>
            <input type="number" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo esc_attr($instance['count'] ?? 5); ?>" min="1" max="20" class="small-text" />
        </p>
        <?php
    }
}

new TechPortal_News_Aggregator();
