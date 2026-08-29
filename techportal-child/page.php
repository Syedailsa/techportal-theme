<?php
/**
 * Page Template
 */
get_header(); ?>

<div class="container">
    <?php while (have_posts()) : the_post(); ?>
    <article class="single-post">
        <div class="post-header">
            <h1><?php the_title(); ?></h1>
        </div>
        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
