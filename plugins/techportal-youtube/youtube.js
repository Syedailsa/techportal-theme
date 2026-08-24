/**
 * Tech Portal YouTube Integration
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check for live indicator
    const liveIndicators = document.querySelectorAll('.youtube-live-indicator');
    
    liveIndicators.forEach(function(indicator) {
        fetch('/wp-admin/admin-ajax.php?action=check_youtube_live')
            .then(response => response.json())
            .then(data => {
                if (data.is_live) {
                    indicator.classList.add('live');
                    indicator.innerHTML = '<span class="live-dot"></span> LIVE';
                } else {
                    indicator.classList.remove('live');
                    indicator.innerHTML = 'Offline';
                }
            })
            .catch(error => {
                console.error('Error checking live status:', error);
            });
    });
});
