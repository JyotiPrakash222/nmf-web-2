<?php
use App\Models\Blog;
use Carbon\Carbon;

// NL1026:16Sep2025: Cache Added:Start
// cache key (today's live blogs only, unique per date)
$cacheKey = 'latest_live_blogs_' . Carbon::today()->format('Y_m_d');
$cacheTTL = 60 * 60; // cache 1 hour, you can adjust

//$latestBlogs = App\Models\Blog::with('category')->where('status', '1')->where('breaking_status', '0')->orderBy('id', 'desc')->limit(10)->get();
$liveBlogs = Cache::remember('breaking_news', now()->addHour(), function () {
    return Blog::with('category')
    ->where('status', '1')
    // ->where('isLive', 1)
    ->where('breaking_status', 1)
    // ->where(function ($query) {
    //     $query->whereDate('created_at', Carbon::today())->orWhereDate('updated_at', Carbon::today());
    // })
    ->where('sequence_id', 0)
    ->whereDate('created_at', Carbon::today())
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();
});
// NL1026:16Sep2025: Cache Added:End
?>


<div class="just_in_widget">
    <div class="just_in">
        <div class="js_title">
            <h5 class="js_t">LIVE</h5>
        </div>
        <ul class="js_block jb">
            @foreach ($liveBlogs as $blog)
                <?php
                // $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                // $blogUrl = $blog->site_url ? asset('live/' . $categorySlug . '/' . $blog->site_url) : '#';
                $blogTime = $blog->created_at->format('g:i A');
                $todayEng = str_replace(' ', '-', date('jS F Y')); // e.g., 5th-May-2025
                ?>
                <li class="js_article">
                    <div class="js_left"></div>
                    <div class="js_right">
                        <p>{{ $blogTime }}</p>
                        <a href="{{ url('/breakingnews/latest-breaking-news-in-hindi-nmfnews-') }}{{ $todayEng }}">
                            {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
