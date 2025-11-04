@foreach ($blogs as $blog)
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
                    href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                    <img src="{{ asset($ff) }}" alt="{{ $blog->name }}">
                </a>
            </div>
            <div class="nws_article_rt">
                <div class="nws_row">
                    <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                        class="nws_article_strip">{{ $cat->name ?? '' }}</a>
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
                        href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?></a>
                    <p class="t_sub">{{ $blog->sort_description }}</p>
                </div>
            </div>
        </article>
    </li>

    @if ($loop->iteration === 5)
        @if(!empty($sateAds['category_middle_horz_sm_ad1']))
            <li>
                <x-horizontal-sm-ad :ad="$sateAds['category_middle_horz_sm_ad1'] ?? null" />
            </li>
        @endif
    @endif
@endforeach
