@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('/asset/css/allgallery.css') }}" type="text/css" media="all" />
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
                                        class="trail-item trail-end"><a href="" itemprop="item"><span
                                                itemprop="name">All Videos</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        
                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$videoAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="custom_ct">
                                            <h2>वीडियो</h2>
                                            {{-- <div class="ct_nav">
                                                <a class="n_link active" href="">सभी</a>
                                                <a class="n_link" href="">राजनीति</a>
                                                <a class="n_link" href="">मनोरंजन</a>
                                                <a class="n_link" href="">खेल</a>
                                                <a class="n_link" href="">तकनीक</a>
                                                <a class="n_link" href="">स्वास्थ्य</a>
                                            </div> --}}
                                            <div class="custom_bl">
                                                @php
                                                    $firstBlog = $allVideoBlogs->first();
                                                    $remainingBlogs = $allVideoBlogs->slice(1, 4); // Get next 4 blogs after the first
                                                @endphp
                                                <div class="at_main">
                                                    <!-- Big Swiper for FIRST blog only -->
                                                    <div class="swiper mySwiper3">
                                                        <div class="swiper-wrapper nswiper">
                                                            @if ($firstBlog)
                                                                <div class="swiper-slide custom_slide">
                                                                    <iframe class="attachment-full size-full wp-post-image"
                                                                        src="{{ $firstBlog->link }}" frameborder="0"
                                                                        allowfullscreen>
                                                                    </iframe>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Optional Navigation -->
                                                        <div class="swiper-button-next"></div>
                                                        <div class="swiper-button-prev"></div>

                                                        <!-- Optional Pagination -->
                                                        <div class="swiper-pagination"></div>
                                                    </div>
                                                    <div class="sub-video-items">
                                                        @foreach ($remainingBlogs as $blog)
                                                            @php
                                                                $categorySlug = $blog->category->site_url ?? '';
                                                                $categoryName = optional($blog->category)->name ?? '';
                                                                $imageUrl = config('global.blog_images_everywhere')(
                                                                    $blog,
                                                                );
                                                                $blogUrl = $blog->site_url
                                                                    ? asset($categorySlug . '/' . $blog->site_url)
                                                                    : '';
                                                            @endphp
                                                            <div class="vdo_card">
                                                                <div class="vdo_card_top m-0">
                                                                    <a href="{{ $blogUrl }}" class="vdoCard_img">
                                                                        <img class="thumbnail" loading="lazy"
                                                                            @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                                                            alt="{{ $blog->name }}">
                                                                        {{-- <img class="hover-gif"
                                                                        src="https://www.nmfnewsonline.com/file/video_gifs/2025/03/td217432028.gif"
                                                                        alt="GIF Animation"> --}}
                                                                    </a>
                                                                    <a href="{{ $blogUrl }}" class="play-btn">
                                                                        <i class="fa-solid fa-play"></i>
                                                                    </a>

                                                                </div>
                                                                <div class="vdo_card_bottom">
                                                                    <a href="{{ $blogUrl }}">{{ $blog->name }}</a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                @foreach ($categoryWiseBlogs as $group)
                                                    <div class="sub">
                                                        <div class="news-tabs nwstb">
                                                            <a class="newstab_title"
                                                                href="{{ url('videos/' . $group['category']->site_url) }}">{{ $group['category']->name }}</a>
                                                            <a href="{{ url('videos/' . $group['category']->site_url) }}">अधिक<i
                                                                    class="fa-solid fa-arrow-right"></i>
                                                            </a>
                                                        </div>
                                                        {{-- <div class="news_tab_t">
                                                            <div class="ntab">
                                                                <div class="newstab_title">
                                                                    <a href="{{ url('videos/' . $group['category']->site_url) }}">{{ $group['category']->name }}</a>
                                                                </div>
                                                            </div>
                                                            <div class="nline">

                                                            </div>
                                                        </div> --}}
                                                        <div class="video-items-left">
                                                            @foreach ($group['blogs'] as $blog)
                                                                @php
                                                                    $categorySlug = $group['category']->site_url ?? '';
                                                                    $imageUrl = config('global.blog_images_everywhere')(
                                                                        $blog,
                                                                    );
                                                                    $blogUrl = $blog->site_url
                                                                        ? asset($categorySlug . '/' . $blog->site_url)
                                                                        : '';
                                                                @endphp
                                                                <div class="vdo_card">
                                                                    <div class="vdo_card_top m-0">
                                                                        <a href="{{ $blogUrl }}"
                                                                            class="vdo__card__img">
                                                                            <img class="thumbnail" loading="lazy"
                                                                                @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                                                                alt="{{ $blog->name }}">
                                                                            {{-- <img class="hover-gif"
                                                                                src="https://www.nmfnewsonline.com/file/video_gifs/2025/03/td217432028.gif"
                                                                                alt="GIF Animation"> --}}
                                                                        </a>
                                                                        <a href="{{ $blogUrl }}" class="play-btn">
                                                                            <i class="fa-solid fa-play"></i>
                                                                        </a>
                                                                        {{-- <a href="{{ $categorySlug }}" class="category_strip">
                                                                            <span class="category">{{ $group['category']->name }}</span>
                                                                        </a> --}}
                                                                    </div>
                                                                    <div class="vdo_card_bottom">
                                                                        <a
                                                                            href="{{ $blogUrl }}">{{ $blog->name }}</a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                    </div>

                                                    {{-- Insert ad after every 2 categories --}}
                                                    @if ($loop->iteration === 2)
                                                        <div class="video-ad">
                                                            <x-horizontal-sm-ad :ad="$videoAds['category_middle_horz_sm_ad1'] ?? null" />
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                        </div>
                                        <div class="nws_pagination">
                                            {{-- Previous Button --}}
                                            @if ($paginatedCategories->onFirstPage())
                                                <span class="page-btn prev disabled">Previous</span>
                                            @else
                                                <a href="{{ $paginatedCategories->previousPageUrl() }}"
                                                    class="page-btn prev">Previous</a>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($paginatedCategories->links()->elements as $element)
                                                @if (is_string($element))
                                                    <span class="page-btn">...</span>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        <a href="{{ $url }}"
                                                            class="page-btn {{ $paginatedCategories->currentPage() == $page ? 'active' : '' }}">
                                                            {{ $page }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($paginatedCategories->hasMorePages())
                                                <a href="{{ $paginatedCategories->nextPageUrl() }}"
                                                    class="page-btn next">Next</a>
                                            @else
                                                <span class="page-btn next disabled">Next</span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$videoAds['category_sidebar_vaerical_ad1'] ?? null" />

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
                                        <x-vertical-sm-ad :ad="$videoAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>
                                
                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$videoAds['category_bottom_ad'] ?? null" />

                            </div>
                        </section>

                    </div>
                </main>
            </div>
        </div>

    </div>
    <script>
        const swiper = new Swiper(".mySwiper3", {
            loop: true,
            spaceBetween: 20,
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

        });
    </script>
@endsection
