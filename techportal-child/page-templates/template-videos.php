<?php
/**
 * Template Name: Featured Videos
 * Featured Videos Page Template
 */
get_header();

// Get all YouTube video IDs from page content
the_post();
$content = get_the_content();
preg_match_all('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $content, $matches);
$all_video_ids = !empty($matches[1]) ? array_unique($matches[1]) : array();
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <span class="page-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </span>
            <h1>Featured Videos</h1>
            <p class="page-subtitle">Curated tech talks, interviews, and documentary features</p>
        </div>
    </div>
</section>

<!-- Video Grid -->
<section class="page-content-section">
    <div class="container">
        <div class="video-grid video-grid-3" id="video-grid-all">
            <?php foreach ($all_video_ids as $i => $vid) : ?>
            <div class="video-card animate-on-scroll <?php echo $i >= 8 ? 'video-hidden' : 'video-visible'; ?>" onclick="window.open('https://www.youtube.com/watch?v=<?php echo esc_attr($vid); ?>', '_blank')">
                <div class="video-thumb">
                    <img src="https://img.youtube.com/vi/<?php echo esc_attr($vid); ?>/mqdefault.jpg" alt="Video thumbnail" loading="lazy">
                    <div class="play-icon"></div>
                </div>
                <div class="video-body">
                    <h4><?php
                        // Extract video title from h3 tag before this embed
                        $vid_pos = strpos($content, $vid);
                        $before = substr($content, 0, $vid_pos);
                        preg_match_all('/<h3>(.*?)<\/h3>/', $before, $all_h3);
                        $title = !empty($all_h3[1]) ? end($all_h3[1]) : 'Video ' . ($i + 1);
                        echo esc_html(strip_tags($title));
                    ?></h4>
                    <div class="video-meta">YouTube &middot; Cleo Abram</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($all_video_ids) > 8) : ?>
        <div class="show-more-wrap">
            <button class="btn-show-more" id="show-more-all-videos" onclick="
                var hidden = document.querySelectorAll('#video-grid-all .video-hidden');
                hidden.forEach(function(el) { el.classList.remove('video-hidden'); el.classList.add('video-visible'); });
                this.classList.add('expanded');
                this.textContent = 'All Videos Shown';
            ">
                Show More (<?php echo count($all_video_ids) - 8; ?> more)
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
