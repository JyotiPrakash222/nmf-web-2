<?php
use App\Models\Blog;
use App\Models\State;
use App\Models\Category;

$states = State::where('home_page_status', '1')->whereNotNull('sequence_id')->where('sequence_id', '!=', 0)->orderBy('sequence_id', 'asc')->get();

$defaultStateId = $states->firstWhere('is_default', 1)?->id;

$tabData = [];

$allStatesBlogs = Blog::where('status', '1')->where('state_ids', '!=', '0')->where('sequence_id', '=', '0')->limit(10)->orderByDesc('id')->get();

// Add the "All States" tab
if ($allStatesBlogs->isNotEmpty()) {
    $tabData[] = [
        'label' => 'सभी',
        'blogs' => $allStatesBlogs,
        'id' => '',
    ];
}

// Loop through each state and get relevant blogs
// Per-state tabs
foreach ($states as $state) {
    $blogs = Blog::where('status', '1')->where('state_ids', $state->id)->orderByDesc('id')->get();

    if ($blogs->isNotEmpty()) {
        $tabData[] = [
            'label' => $state->name,
            'blogs' => $blogs,
            'id' => $state->id,
            'get_url' => $state->site_url
        ];
    }
}
?>


<div class="news-tabs px-3 px-md-0 nwstb">
    <a class="newstab_title" href="{{ $cat_site_url }}">
        <h2>
            {{ $cat_name }}
        </h2>
    @php

    @endphp
    </a><a href="{{ $cat_site_url }}" id="adhikLink">अधिक<i class="fa-solid fa-arrow-right"></i>
    </a>
</div>

<div class="tabs">
    @php $tabIndex = 0; @endphp

    {{-- Tabs Radio Buttons and Labels --}}
    @foreach ($tabData as $tab)
        <input type="radio" name="tabs" id="tab{{ $tabIndex }}"
            {{ isset($defaultStateId) ? ($tab['id'] === $defaultStateId ? 'checked' : '') : ($tabIndex === 0 ? 'checked' : '') }}
            @if($tab['id']) data-state="{{ $tab['get_url'] }}" @endif>

        <label for="tab{{ $tabIndex }}" id="tablab{{ $tabIndex }}-content">{{ $tab['label'] }}</label>

        <div class="tab" id="tab{{ $tabIndex }}-content">
            <div class="tab_row">

                {{-- Left Column --}}
                <div class="col-12 col-md-4">
                    <div class="tab_card_main">
                        @php $blog0 = $tab['blogs'][0] ?? null; @endphp
                        @if ($blog0)
                            @php
                                $imageUrl0 = config('global.blog_images_everywhere')($blog0);
                                $blogUrl0 = isset($blog0->site_url)
                                    ? asset($cat_site_url . '/' . $blog0->site_url)
                                    : '';
                            @endphp
                            <a class="tcm_img" href="{{ $blogUrl0 }}">
                                <img @if (!empty($imageUrl0)) src="{{ asset($imageUrl0) }}" @endif
                                    alt="{{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}"
                                    loading="lazy">
                            </a>
                            <a class="tab_card_title"
                                href="{{ $blogUrl0 }}">{{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}</a>
                        @endif
                    </div>
                    <div class="tab_card">
                        @php $blog1 = $tab['blogs'][1] ?? null; @endphp
                        @if ($blog1)
                            @php
                                $imageUrl1 = config('global.blog_images_everywhere')($blog1);
                                $blogUrl1 = isset($blog1->site_url)
                                    ? asset($cat_site_url . '/' . $blog1->site_url)
                                    : '';
                                $categorySlug1 = isset($blog1->category->site_url) ? $blog1->category->site_url : '';
                            @endphp
                            <a class="tc_img" href="{{ $blogUrl1 }}">
                                <img @if (!empty($imageUrl1)) src="{{ asset($imageUrl1) }}" @endif
                                    alt="{{ isset($blog1->short_title) && $blog1->short_title ? $blog1->short_title : $blog1->name }}"
                                    loading="lazy">
                            </a>
                            <div class="tab_title">
                                <div class="category_strip2">
                                    <a class="category" href="{{ asset($categorySlug1) }}">
                                        @if ($tab['label'] === 'सभी')
                                            {{ $blog1->state->name ?? '' }}
                                        @else
                                            {{ $blog1->category->name ?? '' }}
                                        @endif
                                    </a>
                                </div>
                                <a
                                    href="{{ $blogUrl1 }}">{{ isset($blog1->short_title) && $blog1->short_title ? $blog1->short_title : $blog1->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Middle Column --}}
                <div class="col-12 col-md-4 d-flex flex-column gap-2">
                    @for ($i = 2; $i < min(6, count($tab['blogs'])); $i++)
                        @php
                            $blog = $tab['blogs'][$i];
                            $imageUrl = config('global.blog_images_everywhere')($blog);
                            $blogUrl = isset($blog->site_url) ? asset($cat_site_url . '/' . $blog->site_url) : '';
                            $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                        @endphp
                        <div class="tab_card">
                            <a class="tc_img" href="{{ $blogUrl }}">
                                <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                    alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}"
                                    loading="lazy">
                            </a>
                            <div class="tab_title">
                                <div class="category_strip2">
                                    <a class="category" href="{{ asset($categorySlug) }}">
                                        @if ($tab['label'] === 'सभी')
                                            {{ $blog->state->name ?? '' }}
                                        @else
                                            {{ $blog->category->name ?? '' }}
                                        @endif
                                    </a>
                                </div>
                                <a
                                    href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Right Column --}}
                <div class="col-12 col-md-4 pe-0 pe-md-3">
                    @for ($j = 6; $j < min(10, count($tab['blogs'])); $j++)
                        @php
                            $blog = $tab['blogs'][$j];
                            $blogUrl = isset($blog->site_url) ? asset($cat_site_url . '/' . $blog->site_url) : '';
                            $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                        @endphp
                        <div class="news_desc p_3">
                            <div class="category_strip3">
                                <a class="category" href="{{ asset($categorySlug) }}">
                                    @if ($tab['label'] === 'सभी')
                                        {{ $blog->state->name ?? '' }}
                                    @else
                                        {{ $blog->category->name ?? '' }}
                                    @endif
                                </a>
                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const adhikLink = document.getElementById('adhikLink');
    const tabs = document.querySelectorAll('input[name="tabs"]');
    
    function updateAdhikLink() {
        const selectedTab = document.querySelector('input[name="tabs"]:checked');
        
        if (selectedTab && selectedTab.dataset.state) {
            // For state tabs: example.com/state/bihar
            adhikLink.href = window.location.origin + '/state/' + selectedTab.dataset.state;
        } else {
            // For "All" tab or no state selected
            adhikLink.href = "{{ $cat_site_url }}";
        }
    }
    
    // Add event listeners to all tabs
    tabs.forEach(tab => {
        tab.addEventListener('change', updateAdhikLink);
    });
    
    // Initialize based on default selected tab
    updateAdhikLink();
});
</script>
