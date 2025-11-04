<div class="news-inner">

    <!-- Left Section -->
    <div class="news-block">
        <div class="thumbnail-header">
            <p>{{ $leftTitle }}</p><a href="/photos">अधिक <i class="fa-solid fa-caret-right"></i></a>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($leftSection as $blog)
                    <?php
                    $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                    $imageUrl = config('global.blog_images_everywhere')($blog);
                    $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '#';
                    ?>
                    <div class="swiper-slide">
                        <div class="swiper_card">
                            <div class="swiper_card_top">
                                <a href="{{ $blogUrl }}">
                                    <img src="{{ asset($imageUrl) }}" alt="{{ $blog->name }}" loading="lazy">
                                </a>
                                <div class="category_strip">
                                    <a href="{{ asset($categorySlug) }}"
                                        class="category">{{ $blog->category->name ?? '' }}</a>
                                </div>
                            </div>
                            <div class="swiper_card_bottom">
                                <a href="{{ $blogUrl }}">{{ $blog->name }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="autoplay-progress">
                <svg viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="20"></circle>
                </svg>
                <span></span>
            </div>
        </div>
    </div>

    <!-- Middle Section -->
    @if ($middleSection && $middleSection->count() > 0)
        <?php $firstTwoNews = $middleSection->splice(0, 2); ?>
        <div class="news-block">
            <div class="thumbnail-header">
                <p>{{ $middleTitle }}</p><a href="/photos">अधिक <i class="fa-solid fa-caret-right"></i></a>
            </div>
            <div class="row">
                <div class="news_lft col-6">
                    <?php $blog = $firstTwoNews->first();
                    $imageUrl = config('global.blog_images_everywhere')($blog);
                    $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                    $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '#';
                    ?>
                    <div class="thumb_card">
                        <div class="thumb_card_top">
                            <a href="{{ $blogUrl }}">
                                <img src="{{ asset($imageUrl) }}" alt="{{ $blog->name }}" loading="lazy">
                            </a>
                        </div>
                        <div class="thumb_card_bottom">
                            <a href="{{ $blogUrl }}">
                                {{ $blog->name }}
                            </a>
                        </div>
                    </div>
                    @if (isset($firstTwoNews[1]))
                        <div class="news_desc">
                            <a
                                href="{{ url($firstTwoNews[1]->category->site_url . '/' . $firstTwoNews[1]->site_url) }}">
                                {{ $firstTwoNews[1]->name }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="news_rht col-6">
                    @foreach ($middleSection as $blog)
                        <div class="news_desc">
                            <a href="{{ url($blog->category->site_url . '/' . $blog->site_url) }}">
                                {{ $blog->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Right Section -->
    <div class="news-block">
        <div class="thumbnail-header">
            <p>{{ $rightTitle }}</p><a href="/photos">अधिक <i class="fa-solid fa-caret-right"></i></a>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($rightSection as $blog)
                    <?php
                    $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                    $imageUrl = config('global.blog_images_everywhere')($blog);
                    $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '#';
                    ?>
                    <div class="swiper-slide">
                        <div class="swiper_card">
                            <div class="swiper_card_top">
                                <a href="{{ $blogUrl }}">
                                    <img src="{{ asset($imageUrl) }}" alt="{{ $blog->name }}" loading="lazy">
                                </a>
                                <div class="category_strip">
                                    <a href="{{ asset($categorySlug) }}"
                                        class="category">{{ $blog->category->name ?? '' }}</a>
                                </div>
                                <div class="video_icon">
                                    <i class="fa-solid fa-video"></i>
                                </div>
                            </div>
                            <div class="swiper_card_bottom">
                                <a href="{{ $blogUrl }}">{{ $blog->name }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="autoplay-progress">
                <svg viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="20"></circle>
                </svg>
                <span></span>
            </div>
        </div>
    </div>
</div>
