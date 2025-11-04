@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('asset/css/webstory.css') }}" type="text/css" media="all" />
    <div class="cm-container" style="transform: none;">
        <div class="inner-page-wrapper" style="transform: none;">
            <div id="primary" class="content-area" style="transform: none;">
                <main id="main" class="site-main" style="transform: none;">
                    <div class="cm_archive_page" style="transform: none;">
                        <div class="breadcrumb  default-breadcrumb" style="display: block;">
                            <nav role="navigation" aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs"
                                itemprop="breadcrumb">
                                <ul class="trail-items" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                    <meta name="numberOfItems" content="3">
                                    <meta name="itemListOrder" content="Ascending">
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-begin"><a href="/" rel="home"
                                            itemprop="item"><span itemprop="name">Home</span></a>
                                        <meta itemprop="position" content="1">
                                    </li>
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-end"><a href="/short-videos" itemprop="item"><span
                                                itemprop="name">शॉर्ट वीडियो</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                    <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                        class="trail-item trail-end"><a href="/short-videos/{{ $category->site_url ?? '' }}"
                                            itemprop="item"><span itemprop="name">{{ $category->name ?? '' }}</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$reelCategoryAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_buttom_wrap">
                                            <div class="cstm_container">
                                                <h2>{{ $headerTitle }}</h2>

                                                <div
                                                    class="swiper swiper2 swp_wrap swiper-initialized swiper-horizontal swiper-backface-hidden">
                                                    <div class="swiper-wrapper" aria-live="polite">
                                                        <section class="webStory">
                                                            <div class=" --newsCard-container">
                                                                <div class="--newsCard-row">
                                                                    @foreach ($reels as $reel)
                                                                        @php
                                                                            $normalizedPath = preg_replace('/^.*public[\/\\\\]/', '', $reel->image_path);
                                                                            $thumbPath = asset(trim($normalizedPath, '/') . '/' . ltrim($reel->thumb_image, '/'));
                                                                            $category_url = $category->site_url ?? '';
                                                                        @endphp

                                                                        <div class="card --newsCard">
                                                                            <a href="{{ asset('/short-videos/' . $category_url . '/' . $reel->site_url) }}"
                                                                                target="_blank" class="story-card2">
                                                                                <div class="--newsCard-imgWrapper relative">
                                                                                    <span class="story-play" aria-hidden="true"></span>
                                                                                    <img src="{{ $thumbPath }}"
                                                                                        alt="{{ $reel->title }}"
                                                                                        class="--newsCard-img">
                                                                                </div>
                                                                                <div class="card-body --newsCard-body">
                                                                                    <p class="--newsCard-title">
                                                                                        {{ $reel->title }}</p>
                                                                                </div>
                                                                            </a>
                                                                        </div>

                                                                        {{-- Show ad only once after the 4th story --}}
                                                                        {{-- @if ($loop->iteration === 4)
                                                                            <div class="card --newsCard">
                                                                                <x-horizontal-sm-ad :ad="$reelCategoryAds['category_middle_horz_sm_ad1'] ?? null" />
                                                                            </div>
                                                                        @endif --}}
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                        </section>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="nws_pagination">
                                            {{-- Previous Button --}}
                                            @if ($reels->onFirstPage())
                                                <span class="page-btn prev disabled">Previous</span>
                                            @else
                                                <a href="{{ $reels->previousPageUrl() }}"
                                                    class="page-btn prev">Previous</a>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($reels->links()->elements as $element)
                                                @if (is_string($element))
                                                    <span class="page-btn">...</span>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        <a href="{{ $url }}"
                                                            class="page-btn {{ $reels->currentPage() == $page ? 'active' : '' }}">
                                                            {{ $page }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($reels->hasMorePages())
                                                <a href="{{ $reels->nextPageUrl() }}" class="page-btn next">Next</a>
                                            @else
                                                <span class="page-btn next disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$reelCategoryAds['category_sidebar_vaerical_ad1'] ?? null" />

                                        {{-- Side Widgets --}}
                                        <?php
                                        $categories = [['name' => 'क्या कहता है कानून?', 'limit' => 3], ['name' => 'पॉडकास्ट', 'limit' => 1], ['name' => 'टेक्नोलॉजी', 'limit' => 5], ['name' => 'स्पेशल्स', 'limit' => 5]];
                                        ?>
                                        @foreach ($categories as $cat)
                                            <?php
                                            $category = App\Models\Category::where('name', $cat['name'])->first();
                                            $blogs = App\Models\Blog::where('status', '1')->where('categories_ids', $category->id)->orderBy('updated_at', 'DESC')->limit($cat['limit'])->get()->all();
                                            ?>
                                            @include('components.side-widgets', [
                                                'categoryName' => $cat['name'],
                                                'category' => $category,
                                                'blogs' => $blogs,
                                            ])
                                        @endforeach

                                        {{-- Vertical-Small-2 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$reelCategoryAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>
                                
                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$reelCategoryAds['category_bottom_ad'] ?? null" />

                            </div>
                        </section>

                    </div>
                </main>
            </div>
        </div>

    </div>
@endsection
