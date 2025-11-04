@extends('layouts.app')
{{-- Video.js CSS --}}
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
<link href="https://unpkg.com/videojs-theme-city@latest/dist/videojs-theme-city.css" rel="stylesheet" />

@section('content')
    <style>
        .breadcrumb {
            background: rgba(0, 0, 0, .03);
            margin-top: 30px;
            padding: 7px 20px;
            position: relative;
        }

        .section-title span {
            line-height: 36px !important;
        }

        .vjs-theme-city .vjs-control-bar {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .vjs-button.skip-btn {
            font-size: 16px;
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            cursor: pointer;
            margin: 0 5px;
            padding: 6px 12px;
        }
    </style>

    <div class="cm-container">
        <div class="inner-page-wrapper">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <div class="cm_archive_page">

                        {{-- Breadcrumb --}}
                        <div class="breadcrumb default-breadcrumb" style="display: block;">
                            <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs" itemprop="breadcrumb">
                                <ul class="trail-items" itemscope itemtype="http://schema.org/BreadcrumbList">
                                    <meta name="numberOfItems" content="3">
                                    <meta name="itemListOrder" content="Ascending">
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin">
                                        <a href="{{ url('/') }}" itemprop="item"><span itemprop="name">Home</span></a>
                                        <meta itemprop="position" content="1">
                                    </li>
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin">
                                        <a href="{{ url('/videos') }}" itemprop="item"><span
                                                itemprop="name">Video</span></a>
                                        <meta itemprop="position" content="2">
                                    </li>
                                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
                                        class="trail-item">
                                        <a href="{{ url('/videos/' . ($video->category->site_url ?? '')) }}"
                                            itemprop="item">
                                            <span itemprop="name">{{ $video->category->name }}</span>
                                        </a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Ad --}}
                        <x-horizontal-ad :ad="$detailsAds['detail_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    {{-- Left Column --}}
                                    <div class="col_left">
                                        <div class="main_article_wrap">
                                            <div class="main_article">
                                                <h1 class="rt_main">{{ $video->title }}</h1>

                                                {{-- Video / Thumbnail --}}
                                                <div class="at_img">
                                                    <figure>
                                                        @if ($video->video_path)
                                                            {{-- <video id="custom_video"
                                                                class="video-js vjs-theme-city vjs-big-play-centered"
                                                                controls preload="auto" width="100%" height="auto"
                                                                poster="{{ asset($video->thumbnail_path) }}"
                                                                data-setup='{"playbackRates": [0.75, 1, 1.25, 1.5, 2]}'>
                                                                <source src="{{ asset($video->video_path) }}"
                                                                    type="video/mp4">
                                                                Your browser does not support HTML5 video.
                                                            </video> --}}

                                                                <video controls autoplay muted class="--video-detail respnsive_iframe">
                                                                <source src="{{ asset($video->video_path) }}" type="video/mp4">
                                                                Your browser does not support the video tag.

                                                            </video>
                                                        @elseif ($video->video_thumb)
                                                            <img src="{{ asset($video->video_thumb) }}"
                                                                alt="{{ $video->title }}">
                                                        @endif
                                                    </figure>
                                                </div>

                                                {{-- Metadata --}}
                                                <div class="artcle_tab mt-2">
                                                    <div class="at_left">
                                                        <div class="editedby">
                                                            Created By:
                                                            <a
                                                                href="{{ url('/author/' . str_replace(' ', '_', $video->author->url_name ?? '-')) }}">
                                                                {{ $video->author->name ?? 'NMF News' }}
                                                            </a>
                                                        </div>
                                                        <div class="category_tag">
                                                            <i class="fa-solid fa-tag"></i>
                                                            <a
                                                                href="{{ url('/videos/' . $video->category->site_url ?? '') }}">
                                                                {{ $video->category->name }}
                                                            </a>
                                                        </div>
                                                        <div class="publish_wrap">
                                                            <div class="publish_dt">
                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                <span>{{ $video->created_at->format('d M, Y') }}</span>
                                                            </div>

                                                            @if ($video->updated_at != $video->created_at)
                                                                <div class="publish_dt">
                                                                    (<i class="fa-regular fa-calendar-days"></i>
                                                                    <span>Updated:
                                                                        {{ $video->updated_at->format('d M, Y') }}</span>
                                                                </div>
                                                                <div class="publish_tm">
                                                                    <i class="fa-regular fa-clock"></i>
                                                                    <span>{{ $video->updated_at->format('h:i A') }})</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Share Buttons --}}
                                                    <div class="at_right">
                                                        @php
                                                            $videoUrl =
                                                                'https://www.newsnmf.com/event/video/' .
                                                                ($video->category->site_url ?? '-') .
                                                                '/' .
                                                                $video->site_url;
                                                        @endphp
                                                        <div class="shr_dropdown">
                                                            <button class="shr_btn"><i
                                                                    class="fa-solid fa-share-nodes"></i></button>
                                                            <div class="shr_content">
                                                                <ul class="social_lnk">
                                                                    <li><a href="http://www.facebook.com/sharer.php?u={{ $videoUrl }}"
                                                                            target="_blank"><i
                                                                                class="fab fa-facebook"></i></a></li>
                                                                    <li><a href="https://twitter.com/intent/tweet?url={{ $videoUrl }}"
                                                                            target="_blank"><i
                                                                                class="fa-brands fa-x-twitter"></i></a></li>
                                                                    <li><a href="https://web.whatsapp.com/send?text={{ $videoUrl }}"
                                                                            target="_blank"><i
                                                                                class="fa-brands fa-whatsapp"></i></a></li>
                                                                    <li class="d-block d-md-none"><a
                                                                            href="https://api.whatsapp.com/send?text={{ $videoUrl }}"
                                                                            target="_blank"><i
                                                                                class="fa-brands fa-whatsapp"></i></a></li>
                                                                    <li><a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $videoUrl }}"
                                                                            target="_blank"><i
                                                                                class="fab fa-linkedin"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Description --}}
                                                <div class="at_content">
                                                    {!! $video->description !!}

                                                    {{-- Google Ad --}}
                                                    <section class="cm_related_post_container">
                                                        <div class="section_inner">

                                                            {{-- Horizontal-Small-1 Advertise --}}
                                                            <x-horizontal-sm-ad :ad="$detailsAds['detail_middle_horz_sm_ad1'] ?? null" />

                                                            {{-- Related Videos --}}
                                                            {{-- <div class="rel_artcle_wrap">
                                                                <ul class="rel_content">
                                                                    @foreach ($latests as $latest)
                                                                        <li>
                                                                            <article class="rel_article">
                                                                                <div class="rel_top">
                                                                                    <a
                                                                                        href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}">
                                                                                        <img src="{{ asset($latest->thumbnail_path) }}"
                                                                                            alt="{{ $latest->title }}">
                                                                                    </a>
                                                                                    <a href="{{ url('/videos/' . ($latest->category->site_url ?? '')) }}"
                                                                                        class="nws_article_strip">
                                                                                        {{ $latest->category->name }}
                                                                                    </a>
                                                                                </div>
                                                                                <div class="rel_bottom">
                                                                                    <a href="{{ url('/video/' . ($latest->category->site_url ?? '-') . '/' . $latest->site_url) }}"
                                                                                        class="rel_link">
                                                                                        {{ $latest->title }}
                                                                                    </a>
                                                                                </div>
                                                                            </article>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div> --}}
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column --}}
                                    <div class="col_right">
                                        @include('components.latestStories')
                                        <x-vertical-sm-ad :ad="$detailsAds['detail_sidebar_vertical_ad1'] ?? null" />

                                        {{-- Optimized Side Widgets --}}
                                        @foreach ($sideWidgets as $widget)
                                            @include('components.side-widgets', [
                                                'categoryName' => $widget['categoryName'],
                                                'category' => $widget['category'],
                                                'blogs' => $widget['blogs'],
                                            ])
                                        @endforeach

                                        <x-vertical-sm-ad :ad="$detailsAds['detail_sidebar_vertical_ad2'] ?? null" />
                                    </div>
                                </div>

                                {{-- Bottom Ad --}}
                                <x-horizontal-ad :ad="$detailsAds['detail_bottom_ad'] ?? null" />
                            </div>
                        </section>

                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

{{-- Video.js JS --}}
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
<!-- Google IMA SDK -->
<script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
<!-- Video.js Ads Plugin -->
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-ads@6.9.0/dist/videojs-contrib-ads.min.js"></script>
<!-- Video.js IMA Plugin -->
<script src="https://cdn.jsdelivr.net/npm/videojs-ima@1.9.1/dist/videojs.ima.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const player = videojs('custom_video');
        const videoKey = 'video_time_{{ $video->id }}';

        // Resume saved time
        const savedTime = localStorage.getItem(videoKey);
        if (savedTime) {
            player.currentTime(savedTime);
        }

        // Save time periodically
        player.on('timeupdate', function() {
            localStorage.setItem(videoKey, player.currentTime());
        });

        // Initialize IMA plugin with test ad (or replace with your own)
        player.ima({
            //adTagUrl: 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/YOUR_NETWORK/YOUR_UNIT&cust_params=video_id%3D{{ $video->id }}&env=vp&gdfp_req=1&output=vast&unviewed_position_start=1&ad_rule=1&correlator=',
            adTagUrl: 'https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/single_ad_samples&ciu_szs=300x250&impl=s&gdfp_req=1&env=vp&output=vast&unviewed_position_start=1&ad_rule=1&cust_params=deployment%3Ddevsite%26sample_ct%3Dlinear&correlator=',
            debug: true
        });

        // Load and request ads
        player.ready(function() {
            player.ima.initializeAdDisplayContainer();
            player.ima.requestAds();
        });
    });
</script>
