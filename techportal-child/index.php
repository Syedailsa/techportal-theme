<?php
/**
 * Blog Index / Archive Template
 */
get_header(); ?>

<div class="category-header">
    <div class="container">
        <?php if (is_category()) : ?>
            <h1><?php single_cat_title(); ?></h1>
            <p><?php echo wp_strip_all_tags(category_description()); ?></p>
        <?php elseif (is_search()) : ?>
            <h1>Search Results for "<?php echo esc_html(get_search_query()); ?>"</h1>
        <?php else : ?>
            <h1>Latest Articles</h1>
            <p>Technology news from Pakistan and around the world</p>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="section-block">
        <div class="card-grid">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="article-card animate-on-scroll">
                <?php if (has_post_thumbnail()) : ?>
                <div class="card-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium_large'); ?>
                    </a>
                    <?php
                    $cats = get_the_category();
                    if (!empty($cats)) :
                        $tag_class = 'tag-it';
                        if ($cats[0]->slug === 'startups') $tag_class = 'tag-startup';
                        elseif ($cats[0]->slug === 'cybersecurity') $tag_class = 'tag-cyber';
                        elseif ($cats[0]->slug === 'ai') $tag_class = 'tag-ai';
                    ?>
                    <span class="card-badge section-tag <?php echo esc_attr($tag_class); ?>"><?php echo esc_html($cats[0]->name); ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                    <div class="card-footer">
                        <span class="card-source"><?php the_author(); ?></span>
                        <span class="card-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; else : ?>
            <p style="grid-column:1/-1;text-align:center;padding:60px 0;color:var(--text-muted);">No articles found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
