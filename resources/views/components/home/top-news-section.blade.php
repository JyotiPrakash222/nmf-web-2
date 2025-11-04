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
    $webstories = WebStories::with('category','webStoryFiles')
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

// 5) Assign layout slices using SAME variable names
$centerTopBlog    = $topItems->get(0);            // slot #1
$leftTopBlogs     = collect([$topItems->get(1)]); // slot #2 (single item)
$leftBottomItems  = $topItems->slice(2, 4);       // slots #3-6
$centerGridItems  = $topItems->slice(6, 4);       // slots #7-10

@endphp

<div class="cm-container layout--container">
    <!-- Left Column -->
    <div class="column--left">
        @if ($leftTopBlogs->count() > 0)
            {{-- LeftFirst Blog in special card --}}
            @php
                $leftFirstBlog = $leftTopBlogs->first();
                $leftFirstUrl = $leftFirstBlog->type === 'webstory'
                    ? asset('web-stories/' . optional($leftFirstBlog->category)->site_url . '/' . $leftFirstBlog->siteurl)
                    : $leftFirstBlog->full_url;
                $leftFirstImage = $leftFirstBlog->type === 'webstory'
                    ? $leftFirstBlog->thumb_path
                    : config('global.blog_images_everywhere')($leftFirstBlog);
            @endphp

            <div class="cstm--card">
                <a class="cstm--card-top" href="{{ $leftFirstUrl }}">
                    <img @if (!empty($leftFirstImage)) src="{{ asset($leftFirstImage) }}" @endif
                        alt="{{ isset($leftFirstBlog->short_title) && $leftFirstBlog->short_title ? $leftFirstBlog->short_title : $leftFirstBlog->name }}"
                        loading="lazy" />
                    @if ($leftFirstBlog->type === 'blog' && $leftFirstBlog->link != '')
                        <div class="video_icon">
                            <i class="fa-solid fa-video"></i>
                        </div>
                    @endif
                </a>
                <div class=" cstm--card-bottom">
                    <a href="{{ $leftFirstUrl }}">
                        <h3>
                            @if ($leftFirstBlog->type === 'blog' && $leftFirstBlog->isLive == 1)
                                <span class="live_tag_s">LIVE <span> </span> </span>
                            @endif
                            {{ isset($leftFirstBlog->short_title) && $leftFirstBlog->short_title ? $leftFirstBlog->short_title : $leftFirstBlog->name }}
                        </h3>
                    </a>
                </div>
            </div>

            {{-- Next 4 Left Blogs in tab card format --}}
            {{-- Bottom Blogs (depending on web story count) --}}
            @foreach ($leftBottomItems  as $item)
                @php
                    $itemUrl = $item->type === 'webstory'
                        ? asset('web-stories/' . optional($item->category)->site_url . '/' . $item->siteurl)
                        : $item->full_url;
                    $itemImage = $item->type === 'webstory'
                        ? $item->thumb_path
                        : config('global.blog_images_everywhere')($item);
                @endphp

                <div class="custom-tab-card ctn">
                    <a class="--ct_img" href="{{ $itemUrl }}">
                        <img @if (!empty($itemImage)) src="{{ asset($itemImage) }}" @endif
                            alt="{{ isset($item->short_title) && $item->short_title ? $item->short_title : $item->name }}"
                            loading="lazy" />
                    </a>
                    <div class="custom--card-title">
                        <a href="{{ $itemUrl }}">
                            @if ($item->type === 'blog' && $item->isLive == 1)
                                <span class="live_tag_s">LIVE <span> </span> </span>
                            @endif

                            @if ($item->type === 'blog' && $item->link != '')
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                            @elseif ($item->type === 'webstory')
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
                $centerTopUrl = $centerTopBlog->type === 'webstory'
                    ? asset('web-stories/' . optional($centerTopBlog->category)->site_url . '/' . $centerTopBlog->siteurl)
                    : $centerTopBlog->full_url;
                $centerTopImage = $centerTopBlog->type === 'webstory'
                    ? $centerTopBlog->thumb_path
                    : config('global.blog_images_everywhere')($centerTopBlog);
            @endphp
            <div class="cstm--card-m">
                <a class="cstm--card-top-m " href="{{ $centerTopUrl }}">
                    <img src="{{ $centerTopImage }}"
                        alt="{{ isset($centerTopBlog->short_title) && $centerTopBlog->short_title ? $centerTopBlog->short_title : $centerTopBlog->name }}"
                        loading="lazy" />
                </a>
                <div class=" cstm--card-bottom-m">
                    <a href="{{ $centerTopUrl }}">
                        <h2>
                            @if ($centerTopBlog->type === 'blog' && $centerTopBlog->isLive == 1)
                                <span class="live_tag">LIVE</span>
                            @endif

                            @if ($centerTopBlog->type === 'blog' && $centerTopBlog->link != '')
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                            @elseif ($centerTopBlog->type === 'webstory')
                                <span class="top-reals-icon"><x-icons.webstory-icon /></span>
                            @endif

                            {{ isset($centerTopBlog->short_title) && $centerTopBlog->short_title ? $centerTopBlog->short_title : $centerTopBlog->name }}
                        </h2>
                    </a>

                    @if ($centerTopBlog->type === 'blog')
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($centerTopBlog->sort_description), 250, '...') }}</p>
                    @endif
                    
                </div>
            </div>
        @endif

        {{-- Center Grid: Mixed Blogs + WebStories --}}

        @if ($centerGridItems->isNotEmpty())
            {{-- Mixed Grid: Web Stories + Blogs --}}
            <div class="news--grid">
                @foreach ($centerGridItems->chunk(2) as $chunk)
                    <div class="news--col">
                        @foreach ($chunk as $item)

                                @php
                                    $itemUrl = $item->type === 'webstory'
                                        ? asset('web-stories/' . optional($item->category)->site_url . '/' . $item->siteurl)
                                        : $item->full_url;
                                @endphp
                                <div class="news_desc">
                                    <a href="{{ $itemUrl }}">
                                        @if ($item->type === 'blog' && $item->isLive == 1)
                                            <span class="live_tag_s">LIVE <span> </span> </span>
                                        @endif
                                        
                                        @if ($item->type === 'blog' && $item->link != '')
                                            <i class="fa-solid fa-video"></i>
                                        @elseif ($item->type === 'webstory')
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
        @php $topSidebar = $data['homeAds']['home_top_news_sidebar_ad'] ?? null; @endphp
        <div class="ads_t mt-0">
            <div class="topbar">
                <div class="adtxt">Advertisement
                </div>
                <div class="ad-section side_unit1">
                    @if ($topSidebar)
                        @if ($topSidebar->is_google_ad)
                            <!-- Google Ad -->
                            <!-- FixedAdd300x300_sidebartop -->
                            <ins class="adsbygoogle" style="display:inline-block;width:300px;height:300px"
                                data-ad-client="{{ $topSidebar->google_client }}"
                                data-ad-slot="{{ $topSidebar->google_slot }}"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        @else
                            <!-- Custom Image Ad -->
                            @if (!empty($topSidebar->file_path) || !empty($topSidebar->custom_image))
                                @php
                                    $imagePath = $topSidebar->file_path . '/' . $topSidebar->custom_image;
                                @endphp

                                <a href="{{ $topSidebar->custom_link ?? '#' }}" target="_blank">
                                    <img src="{{ asset($imagePath) }}" alt="Advertisement" />
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="podcast_card pod">
            @if (!$showVoteInTopNews)
                @include('components.podcast')
            @else
                <div class="news-tabs pb-0 mb-3 mt-3">
                    <a class="newstab_title" href="#">
                        आपका वोट
                    </a>
                </div>
                <div id="categories-2" class="widget widget_categories">
                    <div class="news-tab">
                        @include('components.vote')
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
