<?php
/**
 * Front page template
 */

get_header();

// Fetch news from APIs
$news_aggregator = class_exists('TechPortal_News_Aggregator') ? new TechPortal_News_Aggregator() : null;

$it_news = $news_aggregator ? $news_aggregator->get_cached_or_fetch('it', 8) : array();
$startup_news = $news_aggregator ? $news_aggregator->get_cached_or_fetch('startups', 8) : array();
$cyber_news = $news_aggregator ? $news_aggregator->get_cached_or_fetch('cybersecurity', 8) : array();
$ai_news = $news_aggregator ? $news_aggregator->get_cached_or_fetch('ai', 8) : array();

// Get featured video
$featured_videos = get_posts(array(
    'post_type' => 'page',
    'name' => 'featured-videos',
    'numberposts' => 1,
));
$featured_page_id = $featured_videos ? $featured_videos[0]->ID : 0;

// Helper: render article card with image fallback
function render_article_card($article, $tag_class = 'tag-it') {
    $image = !empty($article['image']) ? esc_url($article['image']) : '';
    $title = esc_html($article['title'] ?? '');
    $desc = esc_html(wp_trim_words($article['description'] ?? '', 18));
    $source = esc_html($article['source'] ?? '');
    $time = esc_html($article['published'] ?? '');
    $url = esc_url($article['url'] ?? '#');

    $output = '<article class="article-card animate-on-scroll" onclick="window.open(\'' . $url . '\', \'_blank\')">';
    $output .= '<div class="card-image">';
    if ($image) {
        $output .= '<img src="' . $image . '" alt="' . $title . '" loading="lazy" onerror="this.parentElement.innerHTML=\'<svg width=40 height=40 viewBox=&quot;0 0 24 24&quot; fill=none stroke=&quot;#3B4575&quot; stroke-width=1.5><rect x=3 y=3 width=18 height=18 rx=2/><circle cx=8.5 cy=8.5 r=1.5/><path d=&quot;M21 15l-5-5L5 21&quot;/></svg><span class=card-badge>' . $source . '</span>\'">';
    } else {
        $output .= '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3B4575" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
    }
    $output .= '<span class="card-badge">' . $source . '</span>';
    $output .= '</div>';
    $output .= '<div class="card-body">';
    $output .= '<h3>' . $title . '</h3>';
    $output .= '<p>' . $desc . '</p>';
    $output .= '<div class="card-footer">';
    $output .= '<span class="card-source">' . $source . '</span>';
    $output .= '<span class="card-time">' . $time . '</span>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</article>';
    return $output;
}
?>

<!-- Breaking News Ticker -->
<?php if (!empty($it_news) || !empty($ai_news)) : ?>
<div class="breaking-ticker">
    <div class="container">
        <div class="ticker-inner">
            <span class="ticker-label">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                Breaking
            </span>
            <div class="ticker-content">
                <div class="ticker-scroll">
                    <?php
                    $all_news = array_merge(array_slice($it_news, 0, 3), array_slice($ai_news, 0, 3));
                    foreach ($all_news as $item) :
                    ?>
                    <a href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html($item['title'] ?? ''); ?>
                    </a>
                    <?php endforeach; ?>
                    <?php foreach ($all_news as $item) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html($item['title'] ?? ''); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <?php $hero_main = !empty($it_news) ? $it_news[0] : null; ?>
            <div class="hero-main animate-on-scroll" onclick="window.open('<?php echo esc_url($hero_main['url'] ?? '#'); ?>', '_blank')">
                <?php if (!empty($hero_main['image'])) : ?>
                <img class="hero-image" src="<?php echo esc_url($hero_main['image']); ?>" alt="<?php echo esc_attr($hero_main['title'] ?? ''); ?>" loading="lazy">
                <?php else : ?>
                <div style="width:100%;height:100%;background:linear-gradient(135deg, #1A1F36, #0B1120);display:flex;align-items:center;justify-content:center;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#2A3152" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                </div>
                <?php endif; ?>
                <div class="hero-overlay">
                    <span class="hero-badge"><?php echo esc_html($hero_main['source'] ?? 'Tech News'); ?></span>
                    <h2><?php echo esc_html($hero_main['title'] ?? 'Latest Tech News from Pakistan & Beyond'); ?></h2>
                    <div class="hero-meta">
                        <span><?php echo esc_html($hero_main['published'] ?? ''); ?></span>
                        <span>&middot;</span>
                        <span>IT News</span>
                    </div>
                </div>
            </div>

            <div class="hero-sidebar">
                <?php foreach (array_slice($it_news, 1, 4) as $item) : ?>
                <div class="hero-side-card animate-on-scroll" onclick="window.open('<?php echo esc_url($item['url']); ?>', '_blank')">
                    <div class="card-thumb">
                        <?php if (!empty($item['image'])) : ?>
                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-sm);">
                        <?php else : ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg, #1A1F36, #1E2440);display:flex;align-items:center;justify-content:center;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3B4575" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3><?php echo esc_html($item['title'] ?? ''); ?></h3>
                        <div class="card-meta">
                            <span class="source"><?php echo esc_html($item['source'] ?? ''); ?></span>
                            <span>&middot;</span>
                            <span><?php echo esc_html($item['published'] ?? ''); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- IT News Section -->
<?php if (!empty($it_news)) : ?>
<section class="section-block">
    <div class="container">
        <div class="section-header">
            <div class="section-left">
                <h2>IT News</h2>
                <span class="section-tag tag-it">IT</span>
            </div>
            <a href="<?php echo esc_url(home_url('/category/it-news/')); ?>" class="view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="card-grid">
            <?php foreach (array_slice($it_news, 0, 6) as $article) : ?>
                <?php echo render_article_card($article, 'tag-it'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Startups Section -->
<?php if (!empty($startup_news)) : ?>
<section class="section-block">
    <div class="container">
        <div class="section-header">
            <div class="section-left">
                <h2>Startups &amp; Entrepreneurs</h2>
                <span class="section-tag tag-startup">Startups</span>
            </div>
            <a href="<?php echo esc_url(home_url('/category/startups/')); ?>" class="view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="card-grid">
            <?php foreach (array_slice($startup_news, 0, 6) as $article) : ?>
                <?php echo render_article_card($article, 'tag-startup'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Cybersecurity Section -->
<?php if (!empty($cyber_news)) : ?>
<section class="section-block">
    <div class="container">
        <div class="section-header">
            <div class="section-left">
                <h2>Cybersecurity</h2>
                <span class="section-tag tag-cyber">Cyber</span>
            </div>
            <a href="<?php echo esc_url(home_url('/category/cybersecurity/')); ?>" class="view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="card-grid">
            <?php foreach (array_slice($cyber_news, 0, 6) as $article) : ?>
                <?php echo render_article_card($article, 'tag-cyber'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- AI Section -->
<?php if (!empty($ai_news)) : ?>
<section class="section-block">
    <div class="container">
        <div class="section-header">
            <div class="section-left">
                <h2>Artificial Intelligence &amp; LLMs</h2>
                <span class="section-tag tag-ai">AI</span>
            </div>
            <a href="<?php echo esc_url(home_url('/category/ai/')); ?>" class="view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="card-grid">
            <?php foreach (array_slice($ai_news, 0, 6) as $article) : ?>
                <?php echo render_article_card($article, 'tag-ai'); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Videos Section -->
<?php if ($featured_page_id) : ?>
<section class="section-block">
    <div class="container">
        <div class="section-header">
            <div class="section-left">
                <h2>Featured Videos</h2>
                <span class="section-tag tag-live">Videos</span>
            </div>
            <a href="<?php echo esc_url(home_url('/featured-videos/')); ?>" class="view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <?php
        $video_page = get_post($featured_page_id);
        $video_content = $video_page->post_content ?? '';
        preg_match_all('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_content, $video_matches);
        $video_ids = !empty($video_matches[1]) ? array_unique($video_matches[1]) : array();
        ?>
        <div class="video-grid" id="video-grid-home">
            <?php foreach (array_slice($video_ids, 0, 4) as $vid) : ?>
            <div class="video-card animate-on-scroll" onclick="window.open('https://www.youtube.com/watch?v=<?php echo esc_attr($vid); ?>', '_blank')">
                <div class="video-thumb">
                    <img src="https://img.youtube.com/vi/<?php echo esc_attr($vid); ?>/mqdefault.jpg" alt="Video thumbnail" loading="lazy">
                    <div class="play-icon"></div>
                </div>
                <div class="video-body">
                    <h4>Watch Video</h4>
                    <div class="video-meta">YouTube</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($video_ids) > 4) : ?>
        <div class="show-more-wrap">
            <button class="btn-show-more" id="show-more-videos" onclick="
                var hidden = document.querySelectorAll('#video-grid-home .video-hidden');
                hidden.forEach(function(el) { el.classList.remove('video-hidden'); el.classList.add('video-visible'); });
                this.classList.add('expanded');
                this.textContent = 'All Videos Shown';
            ">
                Show More
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
