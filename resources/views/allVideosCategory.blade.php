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
                                        class="trail-item trail-end"><a href="{{ asset('/videos') }}" itemprop="item"><span
                                                itemprop="name">Videos</span></a>
                                        <meta itemprop="position" content="2">
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
                        <x-horizontal-ad :ad="$videoCatAds['category_header_ad'] ?? null" />


                        <section class="news_main_section">
                            <div class="cm-container">
                                <div class="news_main_row">
                                    <div class="col_left">
                                        <div class="news_main_wrap">
                                            <div class="nws-left">
                                                @if (count($blogs) > 0)
                                                    <?php
                                                    $blog = $blogs->first();
                                                    $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                    $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                    $truncated = $symbol . $blog->name;
                                                    $ff = config('global.blog_images_everywhere')($blog);
                                                    ?>
                                                    <div class="nws_card">
                                                        <div class="nws_card_top dg_top">
                                                            <a
                                                                href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                                <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                                    alt="{{ $blog->name }}">
                                                            </a>
                                                            {{-- <div class="category_strip">
                                                                <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                    class="category">{{ $cat->name ?? '' }}
                                                                </a>
                                                            </div> --}}
                                                        </div>
                                                        <div class="nws_card_bottom">
                                                            <a
                                                                href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                            </a>
                                                        </div>
                                                        <div class="publish_wrap">
                                                            <div class="publish_dt">
                                                                <i class="fa-regular fa-calendar-days"></i>
                                                                <span>{{ $blog->created_at->format('d M, Y') }}</span>
                                                            </div>
                                                            <div class="publish_tm">
                                                                <i class="fa-regular fa-clock"></i>
                                                                <span>{{ $blog->created_at->format('h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="nws-right">
                                                @foreach ($blogs->skip(1)->take(4) as $blog)
                                                    <?php
                                                    $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                    $ff = config('global.blog_images_everywhere')($blog);
                                                    $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                    $truncated = $symbol . $blog->name;
                                                    ?>
                                                    <div class="custom-tab-card">
                                                        <a class="custom-img-link"
                                                            href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                            <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                                alt="{{ $blog->name }}">
                                                        </a>
                                                        <div class="custom-tab-title">
                                                            {{-- <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                class="nws_article_strip">{{ $cat->name ?? '' }}
                                                            </a> --}}
                                                            <a id="cat-t"
                                                                href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <?php
                                        $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
                                        $stateName = '';
                                        if ($state != '') {
                                            $stateObj = App\Models\State::where('site_url', $state)->first();
                                            $stateName = isset($stateObj->name) ? $stateObj->name : '';
                                            $stateName = $stateName == 'नई दिल्ली' ? 'दिल्ली' : $stateName;
                                            $stateUrl = isset($stateObj->site_url) ? $stateObj->site_url : '';
                                        }
                                        $bidhansabha_cat_name = App\Models\Category::where('name', 'विधान सभा चुनाव')->first();
                                        $bidhansabha_cat_url = isset($bidhansabha_cat_name->site_url) ? $bidhansabha_cat_name->site_url : '';
                                        
                                        $subcat = isset($_REQUEST['subcat']) ? $_REQUEST['subcat'] : '';
                                        $subcatName = '';
                                        if ($subcat != '') {
                                            $subcatObj = App\Models\SubCategory::where('site_url', $subcat)->first();
                                            $subcatName = isset($subcatObj->name) ? $subcatObj->name : '';
                                            $subcatUrl = isset($subcatObj->site_url) ? $subcatObj->site_url : '';
                                        }
                                        
                                        $categorySlug = isset($category->site_url) ? $category->site_url : '';
                                        ?>

                                        <div class="news_sub_wrap">
                                            <div class="news_tab_t">
                                                <div class="ntab">
                                                    @if ($stateName)
                                                        <a class="newstab_title"
                                                            href="{{ asset($bidhansabha_cat_url) . '?state=' . $stateUrl }}">
                                                            {{ $stateName }}
                                                            {{ isset($category->name) ? $category->name : '' }}
                                                        </a>
                                                    @elseif ($subcatName)
                                                        <a class="newstab_title"
                                                            href="{{ asset($subcatUrl) . '?subcat=' . $subcatUrl }}">
                                                            {{ isset($category->name) ? $category->name : '' }}->{{ $subcatName }}
                                                        </a>
                                                    @else
                                                        <a class="newstab_title"
                                                            href="{{ asset($categorySlug) }}">{{ isset($category->name) ? $category->name : '' }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="nline">

                                                </div>
                                            </div>
                                            <ul class="nws_list">
                                                @foreach ($blogs->skip(5)->take(15) as $blog)
                                                    <?php
                                                    $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                    $symbol = '';
                                                    if ($blog->link != '') {
                                                        $symbol = '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;';
                                                    }
                                                    $truncated = $symbol . $blog->name;
                                                    $ff = config('global.blog_images_everywhere')($blog);
                                                    ?>
                                                    <li>
                                                        <article class="nws_article">
                                                            <div class="nws_article_lt">
                                                                <a
                                                                    href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                                    <img src="{{ asset($ff) }}"
                                                                        alt="{{ $blog->name }}">
                                                                </a>
                                                            </div>
                                                            <div class="nws_article_rt">
                                                                <div class="nws_row">
                                                                    {{-- <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                        class="nws_article_strip">{{ $cat->name ?? '' }}</a> --}}
                                                                    <div class="publish_wrap">
                                                                        <div class="publish_dt">
                                                                            <i class="fa-regular fa-calendar-days"></i>
                                                                            <span>{{ $blog->created_at->format('d M, Y') }}</span>
                                                                        </div>
                                                                        <div class="publish_tm">
                                                                            <i class="fa-regular fa-clock"></i>
                                                                            <span>{{ $blog->created_at->format('h:i A') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="nws_title">
                                                                    <a class="t_main"
                                                                        href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?></a>
                                                                    <p class="t_sub">{{ $blog->sort_description }}</p>
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
                                                {{-- Previous Button --}}
                                                @if ($blogs->onFirstPage())
                                                    <span class="page-btn prev disabled">Previous</span>
                                                @else
                                                    <a href="{{ $blogs->previousPageUrl() }}"
                                                        class="page-btn prev">Previous</a>
                                                @endif

                                                {{-- Page Numbers --}}
                                                @foreach ($blogs->links()->elements as $element)
                                                    @if (is_string($element))
                                                        <span class="page-btn">...</span>
                                                    @endif

                                                    @if (is_array($element))
                                                        @foreach ($element as $page => $url)
                                                            <a href="{{ $url }}"
                                                                class="page-btn {{ $blogs->currentPage() == $page ? 'active' : '' }}">
                                                                {{ $page }}
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                                {{-- Next Button --}}
                                                @if ($blogs->hasMorePages())
                                                    <a href="{{ $blogs->nextPageUrl() }}" class="page-btn next">Next</a>
                                                @else
                                                    <span class="page-btn next disabled">Next</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col_right">
                                        {{-- - 10 latest articles displayed - --}}
                                        @include('components.latestStories')


                                        {{-- Vertical-Small-1 Category Advertise --}}
                                        <x-vertical-sm-ad :ad="$videoCatAds['category_sidebar_vaerical_ad1'] ?? null" />

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
                                        <x-vertical-sm-ad :ad="$videoCatAds['category_sidebar_vaerical_ad2'] ?? null" />

                                    </div>
                                </div>

                                {{-- Horizontal-2 Advertise --}}
                                <x-horizontal-ad :ad="$videoCatAds['category_bottom_ad'] ?? null" />

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
