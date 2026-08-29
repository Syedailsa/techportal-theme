<?php
/**
 * Template Name: Featured Videos
 * Featured Videos Page Template
 */
get_header();

// Get all YouTube video IDs from page content
the_post();
$content = get_the_content();
preg_match_all('/https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $content, $matches);
$all_video_ids = !empty($matches[1]) ? array_unique($matches[1]) : array();
?>

<div class="category-header">
    <div class="container">
        <h1>Featured Videos</h1>
        <p>Curated tech talks, interviews, and documentary features</p>
    </div>
</div>

<div class="container">
    <section class="section-block">
        <div class="video-grid video-grid-3" id="video-grid-all">
            <?php foreach ($all_video_ids as $i => $vid) : ?>
            <div class="video-card animate-on-scroll <?php echo $i >= 8 ? 'video-hidden' : 'video-visible'; ?>" onclick="window.open('https://www.youtube.com/watch?v=<?php echo esc_attr($vid); ?>', '_blank')">
                <div class="video-thumb">
                    <img src="https://img.youtube.com/vi/<?php echo esc_attr($vid); ?>/mqdefault.jpg" alt="Video thumbnail" loading="lazy">
                    <div class="play-icon"></div>
                </div>
                <div class="video-body">
                    <h4>Video <?php echo $i + 1; ?></h4>
                    <div class="video-meta">YouTube</div>
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
    </section>
</div>

<?php get_footer(); ?>
