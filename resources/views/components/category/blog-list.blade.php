@foreach ($blogs as $blog)
    <?php
    //$cat = App\Models\Category::where('id', $blog->categories_ids)->first();
    $symbol = '';
    if ($blog->link != '') {
        $symbol = '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;';
    }
    $truncated = $symbol . $blog->name;
    //$ff = config('global.blog_images_everywhere')($blog);
    $ff = cached_blog_image($blog);
    ?>
    <li>
        <article class="nws_article">
            <div class="nws_article_lt">
                <a href="{{ asset('/') }}@if(isset($blog->isLive) && $blog->isLive != 0)live/@endif{{ isset($blog->category->site_url) ? $blog->category->site_url : '' }}/{{ $blog->site_url ?? '' }}">
                    <img src="{{ asset($ff) }}" alt="{{ $blog->name }}" loading="lazy">
                </a>
            </div>
            <div class="nws_article_rt">
                <div class="nws_row">
                    @if (isset($blog->category->name) && $blog->category->name == 'राज्य' && isset($blog->state->name))
                        <a href="{{ asset('/') }}state/{{ $blog->state->site_url }}" class="nws_article_strip">
                            {{ $blog->state->name }}
                        </a>
                    @endif
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
                       href="{{ asset('/') }}@if(isset($blog->isLive) && $blog->isLive != 0)live/@endif{{ isset($category->site_url) ? $category->site_url : '-' }}/{{ $blog->site_url ?? '' }}">
                        {!! $truncated !!}
                    </a>
                    <p class="t_sub">{{ $blog->sort_description }}</p>
                </div>
            </div>
        </article>
    </li>

    @if ($loop->iteration === 5)
        {{-- <li>
            <x-horizontal-sm-ad :ad="$categoryAds['category_middle_horz_sm_ad1'] ?? null" />
        </li> --}}
        @if(!empty($categoryAds['category_middle_horz_sm_ad1']))
        <li>
            <x-horizontal-sm-ad :ad="$categoryAds['category_middle_horz_sm_ad1']" />
        </li>
@endif
    @endif
@endforeach
