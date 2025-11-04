<?php
use App\Models\Blog;
use App\Models\State;
use App\Models\Category;

// Fetch states with sequence_id
$states = State::where('home_page_status', '1')->whereNotNull('sequence_id')->where('sequence_id', '!=', 0)->orderBy('sequence_id', 'asc')->get();

// Get blogs for the selected category, grouped by state_id
$bidhansabhablogs = Blog::where('status', '1')->where('categories_ids', $cat_id)->whereNotNull('state_ids')->where('sequence_id', '=', '0')->take(50)->orderByDesc('id')->get()->groupBy('state_ids');

$unique_bidhan_stateIds = $bidhansabhablogs->keys();

// Add the "All States" tab
$allStatesBlogs = Blog::where('status', '1')->where('categories_ids', $cat_id)->where('state_ids', '!=', '0')->where('sequence_id', '=', '0')->limit(11)->orderByDesc('id')->get();

$tabData = [];

if ($allStatesBlogs->isNotEmpty()) {
    $tabData[] = [
        'label' => 'सभी',
        'blogs' => $allStatesBlogs,
    ];
}

// Loop through each state and get relevant blogs
foreach ($unique_bidhan_stateIds as $stateId) {
    if (!empty($stateId)) {
        $state = $states->firstWhere('id', $stateId);
        if ($state) {
            $blogs = $bidhansabhablogs[$stateId] ?? collect();
            if ($blogs->isNotEmpty()) {
                $tabData[] = [
                    'label' => $state->name,
                    'blogs' => $blogs,
                ];
            }
        }
    }
}

?>


<div class="news-tabs pb-0 mt-3 nwstb2 nwstb">
    <a class="newstab_title" href="{{ asset($cat_site_url) }}">
        {{ $cat_name }}
    </a> <a href="{{ asset($cat_site_url) }}">अधिक<i class="fa-solid fa-arrow-right"></i></a>
</div>


<div class="custom-tabs">
    @php $tabIndex = 0; @endphp
    @foreach ($tabData as $tab)
        <input type="radio" name="custom-tabs" id="custom-tab{{ $tabIndex }}" {{ $tabIndex === 0 ? 'checked' : '' }}>
        <label for="custom-tab{{ $tabIndex }}">{{ $tab['label'] }}</label>

        <div class="custom-tab">
            <div class="custom-tab-row">
                {{-- Left Column --}}
                <div class="col-12 col-md-4">
                    @php
                        $blog0 = $tab['blogs'][0] ?? null;
                        $blog1 = $tab['blogs'][1] ?? null;
                    @endphp
                    @if ($blog0)
                        <div class="custom-tab-card-main">
                            @php
                                $imageUrl0 = config('global.blog_images_everywhere')($blog0);
                                $blogUrl0 = $blog0->site_url ? asset($cat_site_url . '/' . $blog0->site_url) : '#';
                            @endphp
                            <a class="tcm_img" href="{{ $blogUrl0 }}">
                                <img @if (!empty($imageUrl0)) src="{{ asset($imageUrl0) }}" @endif
                                    alt="{{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}" loading="lazy">
                            </a>
                            <a class="custom-tab-card-title" href="{{ $blogUrl0 }}">{{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}</a>
                        </div>
                    @endif

                    @if ($blog1)
                        <div class="custom-tab-card">
                            @php
                                $imageUrl1 = config('global.blog_images_everywhere')($blog1);
                                $blogUrl1 = $blog1->site_url ? asset($cat_site_url . '/' . $blog1->site_url) : '#';
                            @endphp
                            <a class="tc_img" href="{{ $blogUrl1 }}">
                                <img @if (!empty($imageUrl1)) src="{{ asset($imageUrl1) }}" @endif
                                    alt="{{ isset($blog1->short_title) && $blog1->short_title ? $blog1->short_title : $blog1->name }}" loading="lazy">
                            </a>
                            <div class="custom-tab-title">
                                <a href="{{ $blogUrl1 }}">{{ isset($blog1->short_title) && $blog1->short_title ? $blog1->short_title : $blog1->name }}</a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Middle Column --}}
                <div class="col-12 col-md-4  d-flex flex-column gap-2">
                    @for ($i = 2; $i < min(6, count($tab['blogs'])); $i++)
                        @php
                            $blog = $tab['blogs'][$i];
                            $imageUrl = config('global.blog_images_everywhere')($blog);
                            $blogUrl = $blog->site_url ? asset($cat_site_url . '/' . $blog->site_url) : '#';
                        @endphp
                        <div class="custom-tab-card">
                            <a class="tc_img" href="{{ $blogUrl }}">
                                <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                    alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}" loading="lazy">
                            </a>
                            <div class="custom-tab-title">
                                <a href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Right Column --}}
                <div class="col-12 col-md-4 pe-0 pe-md-3">
                    @for ($j = 6; $j < min(11, count($tab['blogs'])); $j++)
                        @php
                            $blog = $tab['blogs'][$j];
                            $blogUrl = isset($blog->site_url) ? asset($cat_site_url . '/' . $blog->site_url) : '';
                        @endphp
                        <div class="news_desc p_3">
                            <a href="{{ $blogUrl }}">
                                {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
                            </a>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        @php $tabIndex++; @endphp
    @endforeach
</div>
