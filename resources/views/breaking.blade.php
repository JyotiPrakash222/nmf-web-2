@extends('layouts.app')

@section('content')
    <style>
        .breadcrumb {
            background: rgba(0, 0, 0, .03);
            margin-top: 30px;
            padding: 7px 20px;
            position: relative;
        }

        .section-title span {
            line-height: 36px;
            !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('/asset/css/breaking.css') }}" type="text/css" media="all" />
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
                                        class="trail-item trail-end"><a
                                            href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}"
                                            itemprop="item"><span
                                                itemprop="name">{{ isset($category->name) ? $category->name : '' }}</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$breakingAds['category_header_ad'] ?? null" />
                        
                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="breaking_main">
                                            <h2>Breaking News</h2>
                                            @if (isset($breakingBlogs) && $breakingBlogs->isNotEmpty())
                                                <div class="brk-tag">
                                                    <?php
                                                    $topbreakingblog = $breakingBlogs->first();
                                                    //$breakingBlogs=$breakingBlogs->whereDate('created_at', $topbreakingblog->created_at);
                                                    ?>
                                                    <h5>{{ $topbreakingblog->created_at->format('d F Y') }}</h5> <button
                                                        class="ref-btn"><i
                                                            class="fa-solid fa-arrow-rotate-right"></i></button>
                                                </div>

                                                <div class="breakingNews">
                                                    <h3>{{ $topbreakingblog->name }}</h3>
                                                </div>
                                                <div class="breaking_sub">
                                                    @foreach ($breakingBlogs as $blog)
                                                        @if ($loop->index > 0)
                                                            <div class="breaking_content">
                                                                <div class="brk_l">
                                                                    <h5>{{ $blog->created_at->format('h:i A') }}</h5>
                                                                    <h5> {{ $blog->name }}
                                                                    </h5>
                                                                </div>
                                                                
                                                                <div class="brk_r">
                                                                    <?php
                                                                    $get_baseUrl = config('global.base_url_web_stories');
                                                                   
                                                                    
                                                                    $todayEng = str_replace(' ', '-', date('jS F Y')); // e.g., 5th-May-2025
                                                                    ?>
                    

                                                                    <a href="http://www.facebook.com/sharer.php?u={{ $get_baseUrl }}breakingnews/latest-breaking-news-in-hindi-nmfnews{{$todayEng}}"
                                                                        target="_blank" class="sl-item"><span><i
                                                                                class="fa-brands fa-facebook-f"></i></span></a>
                                                                    <a href="https://twitter.com/intent/tweet?url={{ $get_baseUrl }}breakingnews/latest-breaking-news-in-hindi-nmfnews{{$todayEng}}"
                                                                        target="_blank" class="sl-item"><span><i
                                                                                class="fa-brands fa-twitter"></i></span></a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col_right">
                                     
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
                                        <x-vertical-sm-ad :ad="$breakingAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$breakingAds['category_bottom_ad'] ?? null" />

                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>

    </div>
    <script>
        const swipernew = new Swiper('.swiper2', {
            direction: 'horizontal',
            loop: true,
            slidesPerView: 5,
            spaceBetween: 10,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 10,
                },
            },
        });
    </script>
@endsection
