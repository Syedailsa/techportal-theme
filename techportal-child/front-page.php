<?php
/**
 * Front page template - renders the static page content with shortcodes
 */
global $wp_query;

// Force the main query to load the static front page
$page_id = get_option('page_on_front');
if ($page_id) {
    $wp_query = new WP_Query(array(
        'page_id' => $page_id,
        'post_type' => 'page',
    ));
}

get_header();

echo '<div id="primary" class="content-area">';
echo '<main id="main" class="site-main">';

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
endif;

echo '</main>';
echo '</div>';

get_footer();
