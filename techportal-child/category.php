<?php
/**
 * Category Template - Card Layout with API Articles + Images
 */
get_header();

$term = get_queried_object();
$category_name = single_cat_title('', false);
$category_description = category_description();
$category_slug = $term->slug;

$tag_classes = array(
    'it-news' => 'tag-it',
    'startups' => 'tag-startup',
    'cybersecurity' => 'tag-cyber',
    'ai' => 'tag-ai',
    'live-shows' => 'tag-live',
);
$tag_class = $tag_classes[$category_slug] ?? 'tag-it';

$api_categories = array(
    'it-news' => 'it',
    'startups' => 'startups',
    'cybersecurity' => 'cybersecurity',
    'ai' => 'ai',
    'live-shows' => 'technology',
);
$api_category = $api_categories[$category_slug] ?? 'technology';

$category_icons = array(
    'it-news' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
    'startups' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
    'cybersecurity' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'ai' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 2a2 2 0 0 1 2 2c0 .7-.3 1.4-.8 1.8A6 6 0 0 1 18 10a6 6 0 0 1-6 6 6 6 0 0 1-6-6 6 6 0 0 1 4.8-4.2A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/><path d="M12 16v6"/><path d="M8 22h8"/></svg>',
    'live-shows' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
);
$icon = $category_icons[$category_slug] ?? $category_icons['it-news'];

$news_aggregator = class_exists('TechPortal_News_Aggregator') ? new TechPortal_News_Aggregator() : null;
$articles = $news_aggregator ? $news_aggregator->get_cached_or_fetch($api_category, 20) : array();

function category_render_card($article, $tag_class = 'tag-it', $category_name = '') {
    $image = !empty($article['image']) ? esc_url($article['image']) : '';
    $title = esc_html($article['title'] ?? '');
    $desc = esc_html(wp_trim_words($article['description'] ?? '', 18));
    $source = esc_html($article['source'] ?? '');
    $time = esc_html($article['published'] ?? '');
    $url = esc_url($article['url'] ?? '#');

    $output = '<article class="article-card animate-on-scroll" onclick="window.open(\'' . $url . '\', \'_blank\')">';
    $output .= '<div class="card-image">';
    if ($image) {
        $output .= '<img src="' . $image . '" alt="' . $title . '" loading="lazy">';
    } else {
        $output .= '<div class="card-placeholder">';
        $output .= '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3B4575" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
        $output .= '</div>';
    }
    $output .= '<span class="card-badge section-tag ' . esc_attr($tag_class) . '">' . esc_html($category_name) . '</span>';
    $output .= '</div>';
    $output .= '<div class="card-body">';
    $output .= '<h3><a href="' . $url . '" target="_blank" rel="noopener">' . $title . '</a></h3>';
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

<!-- Category Hero -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <span class="page-icon">
                <?php echo $icon; ?>
            </span>
            <h1><?php echo esc_html($category_name); ?></h1>
            <?php if ($category_description) : ?>
                <p class="page-subtitle"><?php echo wp_strip_all_tags($category_description); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Articles Grid -->
<section class="page-content-section">
    <div class="container">
        <?php if (!empty($articles)) : ?>
        <div class="card-grid">
            <?php foreach (array_slice($articles, 0, 20) as $article) : ?>
                <?php echo category_render_card($article, $tag_class, $category_name); ?>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="no-results">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3B4575" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <h2>No articles found</h2>
            <p>Check back later for new content in this category.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
