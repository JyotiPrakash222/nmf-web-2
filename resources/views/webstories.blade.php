@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{config('global.base_url_asset')}}asset/css/allstory.css" type="text/css" media="all" />
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
                                        class="trail-item trail-end"><a href="/web-stories" itemprop="item"><span
                                                itemprop="name">वेब स्टोरीज</span></a>
                                        <meta itemprop="position" content="3">
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        
                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$webStoryAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_buttom_wrap">
                                            <div class="cstm_container">
                                                <h2>{{ $headerTitle }}</h2>

                                                @foreach ($categories as $category)
                                                    <div class="_tabs nwstb">
                                                        <a class="newstab_title" href="{{ asset('/web-stories/' . $category->site_url) }}">{{ $category->name }}</a>
                                                        <a href="{{ url('web-stories/' . $category->site_url) }}">अधिक<i
                                                                    class="fa-solid fa-arrow-right"></i>
                                                            </a>
                                                    </div>
                                                    <div
                                                        class="swiper swiper2 swp_wrap swiper-initialized swiper-horizontal swiper-backface-hidden">
                                                        <div class="swiper-wrapper gap-1" aria-live="polite">
                                                            @foreach ($category->webStories as $story)
                                                                <?php
                                                                $story_file = optional($story->webStoryFiles->first());
                                                                $get_baseUrl = config('global.base_url_web_stories');
                                                                $story_file_path = '';
                                                                if ($story_file && strpos($story_file->filepath, 'file') !== false) {
                                                                    $findfilepos = strpos($story_file->filepath, 'file');
                                                                    $story_file_path = substr($story_file->filepath, $findfilepos);
                                                                    $story_file_path = $get_baseUrl . $story_file_path . '/' . $story_file->filename;
                                                                }
                                                                $category_url = $story->category->site_url ?? '';
                                                                ?>
                                                                <div class="swiper-slide web_s_all"
                                                                    style="width: 169.8px; margin-right: 10px;">
                                                                    <a href="{{ asset('/web-stories/' . $category_url . '/' . $story->siteurl) }}"
                                                                        target="_blank" class="story-card2">
                                                                        <div class="story_img">
                                                                            <img src="{{$story_file_path}}"
                                                                                alt="{{ $story->name }}">
                                                                            <span class="reals-icon"></span>
                                                                        </div>
                                                                        <p class="story_p">{{ $story->name }}</p>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    @if ($loop->iteration === 3)
                                                        <div class="webstory-ad">
                                                            <x-horizontal-sm-ad :ad="$webStoryAds['category_middle_horz_sm_ad1'] ?? null" />
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="nws_pagination">
                                            {{-- Previous Button --}}
                                            @if ($categories->onFirstPage())
                                                <span class="page-btn prev disabled">Previous</span>
                                            @else
                                                <a href="{{ $categories->previousPageUrl() }}"
                                                    class="page-btn prev">Previous</a>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($categories->links()->elements as $element)
                                                @if (is_string($element))
                                                    <span class="page-btn">...</span>
                                                @endif

                                                @if (is_array($element))
                                                    @foreach ($element as $page => $url)
                                                        <a href="{{ $url }}"
                                                            class="page-btn {{ $categories->currentPage() == $page ? 'active' : '' }}">
                                                            {{ $page }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($categories->hasMorePages())
                                                <a href="{{ $categories->nextPageUrl() }}" class="page-btn next">Next</a>
                                            @else
                                                <span class="page-btn next disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$webStoryAds['category_sidebar_vaerical_ad1'] ?? null" />

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
                                        <x-vertical-sm-ad :ad="$webStoryAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$webStoryAds['category_bottom_ad'] ?? null" />

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
            slidesPerView: 2,
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
