<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" aria-label="Tech Portal Pakistan Home">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/logo.svg'); ?>" alt="Tech Portal Pakistan" class="logo-desktop">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/logo-mobile.svg'); ?>" alt="TP" class="logo-mobile">
        </a>

        <!-- Navigation -->
        <nav class="primary-nav" id="primary-nav" aria-label="Main navigation">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'fallback_cb' => false,
                    'depth' => 1,
                ));
            } else {
                // Default menu
                ?>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/it-news/')); ?>" <?php echo is_category('it-news') ? 'class="active"' : ''; ?>>IT News</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/startups/')); ?>" <?php echo is_category('startups') ? 'class="active"' : ''; ?>>Startups</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/cybersecurity/')); ?>" <?php echo is_category('cybersecurity') ? 'class="active"' : ''; ?>>Cybersecurity</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/ai/')); ?>" <?php echo is_category('ai') ? 'class="active"' : ''; ?>>AI</a></li>
                    <li><a href="<?php echo esc_url(home_url('/category/live-shows/')); ?>" <?php echo is_category('live-shows') ? 'class="active"' : ''; ?>>Live Shows</a></li>
                    <li><a href="<?php echo esc_url(home_url('/featured-videos/')); ?>" <?php echo is_page('featured-videos') ? 'class="active"' : ''; ?>>Videos</a></li>
                </ul>
                <?php
            }
            ?>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <button class="btn-search" aria-label="Search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <span>Search</span>
                <kbd>/</kbd>
            </button>
            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<main id="main-content">
