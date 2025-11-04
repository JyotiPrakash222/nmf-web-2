@extends('layouts.app')

@section('content')
    <div class="cm-container">
        <div class="inner-page-wrapper">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <div class="cm_archive_page">
                        <div class="breadcrumb default-breadcrumb">
                            <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs">
                                <ul class="trail-items">
                                    <li><a href="/">Home</a></li>
                                    <li><a href="{{ url('/videos') }}">Videos</a></li>
                                    <li>
                                        <a href="{{ url('/videos/' . $category->site_url) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal Top Ad --}}
                        <x-horizontal-ad :ad="$videoCatAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_main_wrap">
                                            <div class="nws-left">
                                                @if ($videos->count() > 0)
                                                    @php
                                                        $video = $videos->first();
                                                        $cat = App\Models\Category::find($video->category_id);
                                                    @endphp
                                                    <div class="nws_card">
                                                        <div class="nws_card_top dg_top">
                                                            <a href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                                <img src="{{ config('global.base_url_videos') . $video->thumbnail_path }}"
                                                                    alt="{{ $video->title }}">
                                                            </a>
                                                        </div>
                                                        <div class="nws_card_bottom">
                                                            <a href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                                {{ $video->title }}
                                                            </a>
                                                        </div>
                                                        <div class="publish_wrap">
                                                            <div class="publish_dt">
                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                <span>{{ $video->created_at->format('d M, Y') }}</span>
                                                            </div>
                                                            <div class="publish_tm">
                                                                <i class="fa-regular fa-clock"></i>
                                                                <span>{{ $video->created_at->format('h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="nws-right">
                                                @foreach ($videos->skip(1)->take(4) as $video)
                                                    @php
                                                        $cat = App\Models\Category::find($video->category_id);
                                                    @endphp
                                                    <div class="custom-tab-card">
                                                        <a class="custom-img-link" href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                            <img src="{{ config('global.base_url_videos') . $video->thumbnail_path }}"
                                                                alt="{{ $video->title }}">
                                                        </a>
                                                        <div class="custom-tab-title">
                                                             @if ($video->state)
                                                                <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                class="nws_article_strip">{{ $video->state->name ?? '' }}
                                                               </a> 
                                                            @endif
                                                            <a id="cat-t" href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                                {{ $video->title }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="news_sub_wrap">
                                            <div class="news_tab_t">
                                                <div class="ntab">
                                                    <a class="newstab_title" href="{{ url('/videos/' . $category->site_url) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </div>
                                                <div class="nline"></div>
                                            </div>

                                            <ul class="nws_list">
                                                @foreach ($videos->skip(5) as $video)
                                                    @php
                                                        $cat = App\Models\Category::find($video->category_id);
                                                    @endphp
                                                    <li>
                                                        <article class="nws_article">
                                                            <div class="nws_article_lt">
                                                                <a href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                                    <img src="{{ config('global.base_url_videos') . $video->thumbnail_path }}"
                                                                        alt="{{ $video->title }}">
                                                                </a>
                                                            </div>
                                                            <div class="nws_article_rt">
                                                                <div class="nws_row">
                                                                    <div class="publish_wrap">
                                                                        <div class="publish_dt">
                                                                            <i class="fa-regular fa-calendar-days"></i>
                                                                            <span>{{ $video->created_at->format('d M, Y') }}</span>
                                                                        </div>
                                                                        <div class="publish_tm">
                                                                            <i class="fa-regular fa-clock"></i>
                                                                            <span>{{ $video->created_at->format('h:i A') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="nws_title">
                                                                    <a class="t_main"
                                                                        href="{{ url('/video/' . $video->category->site_url . '/' . $video->site_url) }}">
                                                                        {{ $video->title }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    </li>

                                                    @if ($loop->iteration === 5)
                                                        <li>
                                                            <x-horizontal-sm-ad :ad="$videoCatAds['category_middle_horz_sm_ad1'] ?? null" />
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>

                                            <div class="nws_pagination">
                                                {{ $videos->links() }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col_right">
                                        @include('components.latestStories')
                                        <x-vertical-sm-ad :ad="$videoCatAds['category_sidebar_vaerical_ad1'] ?? null" />
                                        <x-vertical-sm-ad :ad="$videoCatAds['category_sidebar_vaerical_ad2'] ?? null" />
                                    </div>
                                </div>

                                {{-- Horizontal Bottom Ad --}}
                                <x-horizontal-ad :ad="$videoCatAds['category_bottom_ad'] ?? null" />
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
