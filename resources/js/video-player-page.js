import { initializeVideoPlayer } from './videoplayer-ima.js';

// Wait until the HTML document is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Page loaded. Initializing video player...');

    // Check if the video element and the data from Laravel exist
    const videoElement = document.getElementById('my-video-player');
    if (videoElement && window.videoData) {
        // Prepare the video options from the data passed by the Blade file
        const videoOptions = {
            sources: [{
                src: window.videoData.src,
                type: window.videoData.type,
            }],
            poster: window.videoData.poster
        };

        // Call the main initialization function from our dedicated module
        const player = initializeVideoPlayer(
            'my-video-player',
            videoOptions,
            window.adTagUrl // Pass it as an argument
        );

        player.ready(() => {
            console.log('✅ Video player with IMA ads is fully initialized and ready!');
        });

    } else {
        console.error('❌ Video element or video data not found. Cannot initialize player.');
    }
});