<?php
/**
 * Category Template - Card Layout with Images
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

$category_icons = array(
    'it-news' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
    'startups' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
    'cybersecurity' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'ai' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 2a2 2 0 0 1 2 2c0 .7-.3 1.4-.8 1.8A6 6 0 0 1 18 10a6 6 0 0 1-6 6 6 6 0 0 1-6-6 6 6 0 0 1 4.8-4.2A2 2 0 0 1 10 4a2 2 0 0 1 2-2z"/><path d="M12 16v6"/><path d="M8 22h8"/></svg>',
    'live-shows' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
);
$icon = $category_icons[$category_slug] ?? $category_icons['it-news'];
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

<!-- Posts Grid -->
<section class="page-content-section">
    <div class="container">
        <?php if (have_posts()) : ?>
        <div class="card-grid">
            <?php while (have_posts()) : the_post(); ?>
            <article class="article-card animate-on-scroll">
                <div class="card-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                        </a>
                    <?php else : ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg, #1E2440, #1A1F36);display:flex;align-items:center;justify-content:center;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#3B4575" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                    <?php endif; ?>
                    <span class="card-badge section-tag <?php echo esc_attr($tag_class); ?>"><?php echo esc_html($category_name); ?></span>
                </div>
                <div class="card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                    <div class="card-footer">
                        <span class="card-source"><?php the_author(); ?></span>
                        <span class="card-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <div class="pagination-wrap">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg> Previous',
                'next_text' => 'Next <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>',
            ));
            ?>
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
