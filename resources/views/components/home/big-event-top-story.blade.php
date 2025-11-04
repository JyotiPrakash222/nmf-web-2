@php
    use Illuminate\Support\Facades\DB;
    use App\Models\Blog;
    use App\Models\WebStories;
    use App\Models\HomeSection;

    // Get toggle for showing webstories in top news
    $homeSectionStatus = HomeSection::where('title', 'ShowTopNewsWithWebStory')->value('status');

    // 1) Collect blogs normalized
    $blogs = Blog::with('category')
        ->where('status', 1)
        ->where('sequence_id', '>', 0)
        ->orderBy('sequence_id', 'asc')
        ->get()
        ->map(function ($b) {
            $b->type = 'blog';
            $b->topnews_sequence = $b->sequence_id;
            return $b;
        });

    // 2) Collect webstories normalized (only if enabled)
    $webstories = collect();
    if ((int) $homeSectionStatus === 1) {
        $webstories = WebStories::with('category', 'webStoryFiles')
            ->where('status', 1)
            ->where('show_in_topnews', 1)
            ->where('topnews_sequence', '>', 0)
            ->orderBy('topnews_sequence', 'asc')
            ->get()
            ->map(function ($ws) {
                $ws->type = 'webstory';
                return $ws;
            });
    }

    // 3) Merge all into one collection (treat blogs+webstories as $allBlogs)
    $allBlogs = $blogs->concat($webstories);

    // 4) Merge blogs + webstories sorted by sequence_id (topnews_sequence)
    $topItems = $allBlogs
        ->where('topnews_sequence', '>', 0)
        ->sortBy('topnews_sequence')
        ->take(10) // strictly limit to 10 slots
        ->values();

    // 5) Assign layout slices using updated mapping
    $centerTopBlog = $topItems->get(0); // slot #1
    $leftTopBlogs = $topItems->slice(1, 4); // slots #2-5
    $centerGridItems = $topItems->slice(5, 2); // slots #6-7

@endphp


<section class="top--news">
    <div class="cm-container layout--container">

        <!-- Left Column -->
        <div class="column--left">
            @if ($leftTopBlogs->isNotEmpty())
                @foreach ($leftTopBlogs as $item)
                    @php
                        $itemUrl =
                            $item->type === 'webstory'
                                ? asset('web-stories/' . optional($item->category)->site_url . '/' . $item->siteurl)
                                : $item->full_url;
                       $itemImage = $item->type === 'webstory'
                            ? $item->thumb_path
                            : cached_blog_image($item);
                    @endphp

                    <div class="custom-tab-card ctn">
                        <a class="--ct_img" href="{{ $itemUrl }}">
                            <img @if (!empty($itemImage)) src="{{ asset($itemImage) }}" @endif
                                alt="{{ isset($item->short_title) && $item->short_title ? $item->short_title : $item->name }}"
                                loading="lazy">
                        </a>
                        <div class="custom--card-title">
                            <a href="{{ $itemUrl }}">
                                @if ($item->type === 'blog' && $item->isLive == 1)
                                    <span class="live_tag_s">LIVE <span></span></span>
                                @endif
                                @if ($item->type === 'blog' && $item->link != '')
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                @elseif($item->type === 'webstory')
                                    <span class="top-reals-icon"><x-icons.webstory-icon /></span>
                                @endif
                                {{ isset($item->short_title) && $item->short_title ? $item->short_title : $item->name }}
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Center Column -->
        <div class="column--center">
            @if ($centerTopBlog)
                @php
                    $centerTopUrl =
                        $centerTopBlog->type === 'webstory'
                            ? asset(
                                'web-stories/' .
                                    optional($centerTopBlog->category)->site_url .
                                    '/' .
                                    $centerTopBlog->siteurl,
                            )
                            : $centerTopBlog->full_url;
                            $centerTopImage = $centerTopBlog->type === 'webstory'
                            ? $centerTopBlog->thumb_path
                            : cached_blog_image($centerTopBlog);

                @endphp
                <div class="bb-card-m">
                    <a class="bb--card-top-m" href="{{ $centerTopUrl }}">
                        <img src="{{ asset($centerTopImage) }}"
                            alt="{{ isset($centerTopBlog->short_title) && $centerTopBlog->short_title ? $centerTopBlog->short_title : $centerTopBlog->name }}"
                            loading="lazy">
                    </a>
                    <div class=" bb-bottom-m">
                        <a class="bb--card-top-title" href="{{ $centerTopUrl }}">
                            <h2>
                                @if ($centerTopBlog->type === 'blog' && $centerTopBlog->isLive == 1)
                                    <span class="live_tag">LIVE</span>
                                @endif
                                @if ($centerTopBlog->type === 'blog' && $centerTopBlog->link != '')
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                @elseif($centerTopBlog->type === 'webstory')
                                    <span class="top-reals-icon"><x-icons.webstory-icon /></span>
                                @endif
                                {{ isset($centerTopBlog->short_title) && $centerTopBlog->short_title ? $centerTopBlog->short_title : $centerTopBlog->name }}
                            </h2>
                        </a>
                        @if ($centerTopBlog->type === 'blog')
                            <p class="bb--card-top-p">
                                {{ \Illuminate\Support\Str::limit(strip_tags($centerTopBlog->sort_description), 250, '...') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            @if($centerGridItems->isNotEmpty())
                <div class="news--grid">
                    @foreach($centerGridItems->chunk(1) as $chunk)
                        <div class="news--col">
                            @foreach($chunk as $item)
                                @php
                                    $itemUrl = $item->type === 'webstory'
                                        ? asset('web-stories/' . optional($item->category)->site_url . '/' . $item->siteurl)
                                        : $item->full_url;
                                @endphp
                                <div class="news_desc">
                                    <a href="{{ $itemUrl }}">
                                        @if($item->type === 'blog' && $item->isLive == 1)
                                            <span class="live_tag_s">LIVE <span></span></span>
                                        @endif
                                        @if($item->type === 'blog' && $item->link != '')
                                            <i class="fa fa-video-camera" aria-hidden="true"></i>
                                        @elseif($item->type === 'webstory')
                                            <span class="top-reals-icon"><x-icons.webstory-icon /></span>
                                        @endif
                                        {{ isset($item->short_title) && $item->short_title ? $item->short_title : $item->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="column--right">
            <div class="podcast_card">
                @include('components.podcast')
            </div>
        </div>
</section>
