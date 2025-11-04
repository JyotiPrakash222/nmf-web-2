// ================================================================
// IMPORTS (as per official documentation)
// ================================================================
import videojs from 'video.js';
// Core Video.js styles
import 'video.js/dist/video-js.css';
// Our chosen professional theme
import '@videojs/themes/dist/sea/index.css';
// Video.js advertising integration plugin
import 'videojs-contrib-ads';
import 'videojs-contrib-ads/dist/videojs-contrib-ads.css';
// The Google IMA SDK plugin for Video.js
import 'videojs-ima';
// The required CSS for the IMA plugin UI (e.g., countdown timer)
import 'videojs-ima/dist/videojs.ima.css';

// ================================================================
// MAIN PLAYER INITIALIZATION FUNCTION
// ================================================================
export function initializeVideoPlayer(videoElementId, videoOptions) {
    // 1. Set up the basic player options
    const playerOptions = {
        controls: true,
        fluid: true,
        responsive: true,
        playbackRates: [0.5, 1, 1.5, 2],
        poster: videoOptions.poster
        // NOTE: We do NOT set the video source here yet.
        // This is a best practice for IMA to ensure ads initialize correctly.
    };

    // 2. Initialize the Video.js player
    const player = videojs(videoElementId, playerOptions);

    // 3. Set up the IMA plugin options
    const adContainer = document.getElementById('ad-container');
    const imaOptions = {
        // A reliable Google sample ad tag for testing. Replace with your own.
        adTagUrl: 'https://pubads.g.doubleclick.net/gampad/ads?iu=/21775744923/external/single_ad_samples&sz=640x480&cust_params=sample_ct%3Dlinear&ciu_szs=300x250%2C728x90&gdfp_req=1&output=vast&unviewed_position_start=1&env=vp&correlator=2',
        // Tell the IMA plugin to use our dedicated div for ads.
        adContainerDiv: adContainer,
        // Enable debug mode for development; set to false in production.
        debug: true,
        // Additional settings for the underlying contrib-ads plugin.
        contribAdsSettings: {
            debug: true
        }
    };

    // 4. Initialize the IMA plugin on the player
    player.ima(imaOptions);

    // 5. Set the video source *after* IMA has been initialized
    player.src(videoOptions.sources);

    // 6. Handle mobile-specific requirements
    if (isMobileDevice()) {
        setupMobileAdInitialization(player);
    } else {
        // On desktop, we can initialize the ad display container immediately.
        // The IMA SDK requires this to be done before ads can be requested.
        player.ima.initializeAdDisplayContainer();
    }

    // 7. Set up all our custom event listeners for ads
    setupIMAEventListeners(player);

    // 8. Return the fully configured player instance
    return player;
}

// ================================================================
// EVENT LISTENERS
// This function handles all ad-related events.
// ================================================================
function setupIMAEventListeners(player) {
    const adContainer = document.getElementById('ad-container');

    // Helper function to show the ad container
    const showAdContainer = () => {
        if (adContainer) adContainer.style.display = 'block';
    };

    // Helper function to hide the ad container and restore player controls
    const hideAdContainer = () => {
        if (adContainer) adContainer.style.display = 'none';
        player.controls(true); // Ensure controls are re-enabled
        player.trigger('mouseover'); // Simulate mouseover to show controls
    };

    // --- Attach event listeners ---

    // Show the container right before an ad starts
    player.on('ads-ad-started', showAdContainer);
    // Hide the container when an ad ends
    player.on('ads-ad-ended', hideAdContainer);
    // Also hide the container if an ad fails to play
    player.on('adserror', hideAdContainer);
    
    // Use .one() to ensure the ad request only happens ONCE per video load.
    // This fires when the user first clicks play.
    player.one('playing', () => {
        console.log('▶️ Content playing for the first time, requesting ads...');
        // On mobile, ads are requested by the tap overlay, so we don't do it here.
        if (!isMobileDevice()) {
            player.ima.requestAds();
        }
    });
}

// ================================================================
// MOBILE-SPECIFIC HANDLING
// This creates a "Tap to Play" overlay for mobile devices.
// ================================================================
function setupMobileAdInitialization(player) {
    const overlay = document.createElement('div');
    overlay.className = 'vjs-mobile-overlay';
    overlay.innerHTML = `<div class="vjs-mobile-overlay-text">Tap to Play</div>`;
    // Basic styling for the overlay
    const style = document.createElement('style');
    style.textContent = `
        .vjs-mobile-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 20; cursor: pointer; }
        .vjs-mobile-overlay-text { color: white; font-size: 24px; font-weight: bold; }
    `;
    document.head.appendChild(style);
    player.el().appendChild(overlay);

    // The 'tap' event listener that initializes ads and plays the video
    overlay.addEventListener('click', function mobileAdInit() {
        // This listener should only ever fire once.
        overlay.removeEventListener('click', mobileAdInit);
        
        console.log('📱 Mobile user interaction - initializing ads...');
        player.ima.initializeAdDisplayContainer();
        player.ima.requestAds();
        overlay.remove();
        player.play();
    });
}

// ================================================================
// UTILITY FUNCTION
// ================================================================
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}