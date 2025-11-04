@php
    $contentService = app(\App\Services\ContentAggregatorService::class);

    $allItems = $contentService->getAllItems($cat_id, [
        'blogs' => 5,
        'videos' => 5,
        'clips' => 5,
    ])->sortByDesc('created_at')->values()->take(15);

    // Sort and take top 20 first (to ensure we have enough blogs to choose from)
    //$allItems = $allItems->sortByDesc('created_at')->values()->take(15);

    // Left section — prefer blog, fallback to first item if none
    // $leftSection = $allItems->where('type', 'blog')->take(1);
    // if ($leftSection->isEmpty()) {
    //     $leftSection = $allItems->take(1);
    // }
    $leftSection = $allItems->take(1);

    // Exclude left section from the rest
    $remainingItems = $allItems
        ->reject(function ($item) use ($leftSection) {
            return $leftSection->contains('id', $item['id']);
        })
        ->values();

    // Middle section (first 4 remaining)
    $middleSection = $remainingItems->slice(0, 4);

@endphp


<div class="news-panel">
    <div class="news-tabs nwstb">
        <a class="newstab_title" href="/{{ $cat_site_url }}">
            <h2>
                {{ $cat_name }}
            </h2>
        </a>
        <a href="{{ $cat_site_url }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="news-inner">
        {{-- Left Section --}}
        @if ($leftSection && $leftSection->count() > 0)
            @php $left = $leftSection->first(); @endphp

            <div class="news-block col-6">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="swiper_card">
                                <div class="swiper_card_top">
                                    <a href="{{ $left['url'] }}">
                                        <img src="{{ asset($left['image']) }}" alt="{{ $left['title'] }}"
                                            loading="lazy">
                                    </a>
                                    <div class="category_strip">
                                        <a href="{{ $left['category_url'] }}"
                                            class="category">{{ $left['category'] }}</a>
                                    </div>
                                </div>
                                <div class="swiper_card_bottom">

                                    <a href="{{ $left['url'] }}">
                                        @if ($left['type'] !== 'blog')
                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        @elseif(!empty($left['link']))
                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        @endif
                                        {{ $left['title'] }}</a>
                                </div>
                            </div>
                        </div>
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
        @endif

        {{-- Middle Section --}}
        @if ($middleSection && $middleSection->count() > 0)
            <div class="news-block col-6">
                <div class="row ">
                    @foreach ($middleSection as $index => $item)
                        @if ($index === 0)
                            <div class="news_lft2">
                                <div class="thumb_card">
                                    <div class="thumb_card_top">
                                        <a href="{{ $item['url'] }}">
                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}"
                                                loading="lazy">
                                        </a>
                                    </div>
                                    <div class="thumb_card_bottom">

                                        <a href="{{ $item['url'] }}">
                                            @if ($left['type'] !== 'blog')
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @elseif(!empty($left['link']))
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @endif
                                            {{ $item['title'] }}</a>
                                    </div>
                                </div>
                                @if ($middleSection->count() > 1)
                                    <div class="news_desc" id="line2">

                                        <a href="{{ $middleSection[1]['url'] }}">
                                            @if ($middleSection[1]['type'] !== 'blog')
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @elseif(!empty($middleSection[1]['link']))
                                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                            @endif
                                            {{ $middleSection[1]['title'] }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="news_rht2">
                        @elseif($index > 1)
                            <div class="news_desc" id="line2">

                                <a href="{{ $item['url'] }}">
                                    @if ($left['type'] !== 'blog')
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    @elseif(!empty($left['link']))
                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    @endif
                                    {{ $item['title'] }}</a>
                            </div>
                        @endif
                    @endforeach
                            </div> {{-- Close .news_rht2 --}}
                </div>
            </div>
        @endif
    </div>
</div>
