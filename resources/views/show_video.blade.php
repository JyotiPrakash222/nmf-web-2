<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->title }}</title>

    <script src="//imasdk.googleapis.com/js/sdkloader/ima3.js"></script>

    @vite(['resources/js/video-player-page.js'])

    <style>
        /* Basic page layout styles */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; margin: 0; color: #18181b; }
        .page-container { display: flex; flex-direction: column; gap: 24px; max-width: 1280px; margin: auto; padding: 24px; }
        @media (min-width: 1024px) { .page-container { flex-direction: row; } }
        .main-content { flex: 1; min-width: 0; }
        .sidebar { width: 100%; }
        @media (min-width: 1024px) { .sidebar { width: 350px; } }
        
        .video-details { background: #fff; padding: 20px; border-radius: 12px; }
        .video-title { font-size: 1.875rem; font-weight: 700; margin: 0 0 12px 0; }
        .video-meta { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 16px; color: #71717a; font-size: 0.875rem; border-bottom: 1px solid #e4e4e7; padding-bottom: 16px; }
        .video-description { margin: 16px 0; color: #3f3f46; line-height: 1.6; }

        /* ================================================================
        PLAYER AND AD CONTAINER STYLES
        ================================================================ */
        .video-wrapper {
            position: relative; background: #000; border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -4px rgba(0, 0, 0, .1);
        }
        #ad-container {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 10; display: none;
        }
        
        /* ================================================================
        PROFESSIONAL VIDEO.JS CONTROL STYLES (Theme: Sea)
        ================================================================ */
        .video-js.vjs-theme-sea { --vjs-theme-color: #ff6b35; }
        .vjs-theme-sea .vjs-big-play-button { background-color: rgba(0, 0, 0, 0.6); border: 2px solid var(--vjs-theme-color); width: 3em; height: 3em; border-radius: 50%; margin-top: -1.5em; margin-left: -1.5em; }
        .vjs-theme-sea:hover .vjs-big-play-button { background-color: rgba(255, 107, 53, 0.9); border-color: #fff; }
        .vjs-theme-sea .vjs-control { transition: transform 0.2s ease-in-out; }
        .vjs-theme-sea .vjs-control:hover { transform: scale(1.2); text-shadow: 0 0 8px var(--vjs-theme-color); }
        .vjs-theme-sea .vjs-play-progress, .vjs-theme-sea .vjs-volume-level { background-color: var(--vjs-theme-color); }

        /* ================================================================
        RELATED VIDEOS SIDEBAR STYLES
        ================================================================ */
        .sidebar h2 { font-size: 1.25rem; margin: 0 0 16px 0; }
        .related-videos-list a { display: flex; gap: 12px; margin-bottom: 12px; text-decoration: none; color: inherit; background: #fff; padding: 10px; border-radius: 8px; transition: background-color 0.2s; }
        .related-videos-list a:hover { background-color: #f0f0f0; }
        .related-videos-list img { width: 120px; height: 68px; object-fit: cover; border-radius: 4px; flex-shrink: 0; }
        .related-video-info h3 { font-size: 0.9rem; font-weight: 600; margin: 0 0 4px 0; line-height: 1.3; }
        .related-video-info p { font-size: 0.75rem; color: #71717a; margin: 0; }
    </style>
</head>
<body>
    <div class="page-container">
        <main class="main-content">
            <div class="video-wrapper">
                <video id="my-video-player" class="video-js vjs-theme-sea vjs-fluid"
                    poster="{{ asset($video->thumbnail_path) }}" webkit-playsinline playsinline>
                    <p class="vjs-no-js">To view this video, please enable JavaScript.</p>
                </video>
                <div id="ad-container"></div>
            </div>

            <div class="video-details">
                <h1 class="video-title">{{ $video->title }}</h1>
                <div class="video-meta">
                    <span>{{ number_format($video->views) }} views</span>
                    <span>{{ \Carbon\Carbon::parse($video->published_at)->diffForHumans() }}</span>
                </div>
                <div class="video-description">
                    <p>{{ $video->description }}</p>
                </div>
            </div>
        </main>

        <aside class="sidebar">
            <h2>Related Videos</h2>
            <div class="related-videos-list">
                @forelse($relatedVideos as $relatedVideo)
                    <a href="{{ route('player.show', ['cat_name' => $relatedVideo->category->slug ?? 'general', 'name' => $relatedVideo->eng_name]) }}">
                        <img src="{{ asset($relatedVideo->thumbnail_path) }}" alt="{{ $relatedVideo->title }}">
                        <div class="related-video-info">
                            <h3>{{ Str::limit($relatedVideo->title, 50) }}</h3>
                            <p>{{ number_format($relatedVideo->views) }} views &bull;
                                {{ \Carbon\Carbon::parse($relatedVideo->published_at)->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <p>No related videos found.</p>
                @endforelse
            </div>
        </aside>
    </div>

    <script>
        window.videoData = {
            src: "{{ asset($video->video_path) }}",
            type: "video/mp4",
            poster: "{{ asset($video->thumbnail_path) }}"
        };
    </script>
</body>
</html>