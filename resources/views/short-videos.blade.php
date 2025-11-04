@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('asset/css/allstory.css?v=1.1') }}" type="text/css" media="all" />
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
                                </ul>
                            </nav>
                        </div>

                        {{-- Horizontal-1 Advertise --}}
                        <x-horizontal-ad :ad="$reelAds['category_header_ad'] ?? null" />

                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_buttom_wrap">
                                            <div class="cstm_container">
                                                <h2>{{ $headerTitle }}</h2>

                                                <div class="webs-grid" id="short-video-list">
                                                    @include('components.category.short-video-grid', [
                                                        'reels' => $reels,
                                                        'reelAds' => $reelAds,
                                                    ])
                                                </div>

                                            </div>
                                        </div>
                                        {{-- <div class="nws_pagination">
                                            
                                            @if ($reels->onFirstPage())
                                                <span class="page-btn prev disabled">Previous</span>
                                            @else
                                                <a href="{{ $reels->previousPageUrl() }}" class="page-btn prev">Previous</a>
                                            @endif

                                            
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

                                            
                                            @if ($reels->hasMorePages())
                                                <a href="{{ $reels->nextPageUrl() }}" class="page-btn next">Next</a>
                                            @else
                                                <span class="page-btn next disabled">Next</span>
                                            @endif
                                        </div> --}}

                                        @if ($reels->count() > 0)
                                            <div class="text-center my-4">
                                                <button id="shorts-load-more-btn" class="show-more-btn"
                                                    data-offset="{{ $reels->count() }}" >
                                                    Show More <i class="fa-solid fa-angle-down"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')

                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$reelAds['category_sidebar_vaerical_ad1'] ?? null" />

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
                                        <x-vertical-sm-ad :ad="$reelAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$reelAds['category_bottom_ad'] ?? null" />

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#shorts-load-more-btn').on('click', function() {
        let button = $(this);
        let offset = parseInt(button.data('offset'));

        button.prop('disabled', true).html(
                        'Loading... <i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/short-videos/load-more',
            method: 'GET',
            data: { offset: offset },
            success: function(res) {
                if (res.count > 0) {
                    $('#short-video-list').append(res.reels);
                    button.data('offset', offset + res.count)
                          .prop('disabled', false)
                          .text('Show More');
                } else {
                    button.remove(); // no more reels
                }
            },
            error: function() {
                alert('Failed to load more videos.');
                button.prop('disabled', false)
                                .html('Show More <i class="fa-solid fa-angle-down"></i>');
            }
        });
    });
});
</script>
