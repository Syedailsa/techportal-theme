<?php
/**
 * Single Post Template
 */
get_header(); ?>

<div class="container">
    <?php while (have_posts()) : the_post(); ?>
    <article class="single-post">
        <div class="post-header">
            <?php
            $cats = get_the_category();
            if (!empty($cats)) :
                $cat = $cats[0];
                $tag_class = 'tag-it';
                if ($cat->slug === 'startups') $tag_class = 'tag-startup';
                elseif ($cat->slug === 'cybersecurity') $tag_class = 'tag-cyber';
                elseif ($cat->slug === 'ai') $tag_class = 'tag-ai';
                elseif ($cat->slug === 'live-shows') $tag_class = 'tag-live';
            ?>
            <span class="post-category section-tag <?php echo esc_attr($tag_class); ?>"><?php echo esc_html($cat->name); ?></span>
            <?php endif; ?>

            <h1><?php the_title(); ?></h1>

            <div class="post-meta">
                <span>By <?php the_author(); ?></span>
                <span>&middot;</span>
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                <?php if (!empty($cats)) : ?>
                <span>&middot;</span>
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" style="color:var(--accent);"><?php echo esc_html($cat->name); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php the_post_thumbnail('full'); ?>
        </div>
        <?php endif; ?>

        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
