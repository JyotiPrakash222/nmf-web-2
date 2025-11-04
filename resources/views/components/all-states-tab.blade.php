@php
    use App\Models\Blog;
    use App\Models\State;

    $states = State::where('home_page_status', '1')
        ->whereNotNull('sequence_id')
        ->where('sequence_id', '!=', 0)
        ->orderBy('sequence_id', 'asc')
        ->get();

    $defaultStateId = $states->firstWhere('is_default', 1)?->id;

    $tabData = [];

    $allStatesBlogs = Blog::where('status', '1')
        ->where('state_ids', '!=', '0')
        ->where('sequence_id', '=', '0')
        ->limit(10)
        ->orderByDesc('id')
        ->get();

    if ($allStatesBlogs->isNotEmpty()) {
        $tabData[] = [
            'label' => 'सभी',
            'blogs' => $allStatesBlogs,
            'id' => '',
        ];
    }

    foreach ($states as $state) {
        $blogs = Blog::where('status', '1')->where('state_ids', $state->id)->orderByDesc('id')->get();

        if ($blogs->isNotEmpty()) {
            $tabData[] = [
                'label' => $state->name,
                'blogs' => $blogs,
                'id' => $state->id,
                'get_url' => $state->site_url,
            ];
        }
    }
@endphp

<div class="news-tabs px-3 px-md-0 nwstb">
    <a class="newstab_title" href="{{ $cat_site_url }}">
        <h2>
            {{ $cat_name }}
        </h2>
    </a><a href="{{ $cat_site_url }}" id="adhikLink">अधिक<i class="fa-solid fa-arrow-right"></i>
    </a>
</div>

<div class="cm-container mb-4">
    <div class="js-tabs-container">
        <div class="js-tabs-nav" id="jsTabsNav">
            @foreach ($tabData as $index => $tab)
                <div class="js-tab-button {{ $index === 0 ? 'active' : '' }}" data-tab="{{ $index + 1 }}"
                    @if ($tab['id']) data-state="{{ $tab['get_url'] }}" @endif>
                    {{ $tab['label'] }}
                </div>
            @endforeach
        </div>

        <div class="js-tabs-content-wrapper">
            @foreach ($tabData as $index => $tab)
                <div class="js-tab-content {{ $index === 0 ? 'active' : '' }}" data-content="{{ $index + 1 }}">


                    <div class="tab_row">
                        {{-- Right Column --}}
                        {{-- Left Column --}}
                        <div class="col-12 col-md-4">
                            @php $blog0 = $tab['blogs'][0] ?? null; @endphp
                            @if ($blog0)
                                @php
                                   // $imageUrl0 = config('global.blog_images_everywhere')($blog0);
                                    $imageUrl0 = cached_blog_image($blog0); 
                                    $blogUrl0 = asset($cat_site_url . '/' . $blog0->site_url);
                                @endphp
                                <div class="tab_card_main">
                                    <a class="tcm_img" href="{{ $blogUrl0 }}">
                                        <img src="{{ asset($imageUrl0) }}"
                                            alt="{{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}"
                                            loading="lazy">
                                    </a>
                                </div>
                                <a class="tab_card_title mt-2.5" href="{{ $blogUrl0 }}">
                                    {{ isset($blog0->short_title) && $blog0->short_title ? $blog0->short_title : $blog0->name }}
                                </a>
                            @endif
                        </div>

                        {{-- Middle Column --}}
                        <div class="col-12 col-md-4 d-flex flex-column gap-2">
                            @for ($i = 1; $i < min(5, count($tab['blogs'])); $i++)
                                @php
                                    $blog = $tab['blogs'][$i];
                                    //$imageUrl = config('global.blog_images_everywhere')($blog); 
                                    $imageUrl = cached_blog_image($blog); 
                                    $blogUrl = isset($blog->site_url)
                                        ? asset($cat_site_url . '/' . $blog->site_url)
                                        : '';
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
                                                {{ $tab['label'] === 'सभी' ? $blog->state->name ?? '' : $blog->category->name ?? '' }}
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
                            @for ($j = 5; $j < min(9, count($tab['blogs'])); $j++)
                                @php
                                    $blog = $tab['blogs'][$j];
                                    $blogUrl = isset($blog->site_url)
                                        ? asset($cat_site_url . '/' . $blog->site_url)
                                        : '';
                                    $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                                @endphp
                                <div class="news_desc p_3">
                                    <div class="category_strip3">
                                        <a class="category" href="{{ asset($categorySlug) }}">
                                            {{ $tab['label'] === 'सभी' ? $blog->state->name ?? '' : $blog->category->name ?? '' }}
                                        </a>
                                    </div>
                                    <a class="n_title" href="{{ $blogUrl }}">
                                        {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}
                                    </a>
                                </div>
                            @endfor
                        </div>
                    </div>
                    @if ($tab['label'] !== 'सभी')
                        <div class="d-flex justify-content-end"> 
                            <a class="see-more-btn3"
                                href="{{ $tab['id'] ? config('global.base_url') .('state/' . $tab['get_url']) : $cat_site_url }}">अधिक</a>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll(".js-tab-button");
        const contents = document.querySelectorAll(".js-tab-content");

        tabs.forEach(tab => {
            tab.addEventListener("click", () => {
                const tabId = tab.getAttribute("data-tab");

                tabs.forEach(t => t.classList.remove("active"));
                contents.forEach(c => c.classList.remove("active"));

                tab.classList.add("active");
                document.querySelector(`.js-tab-content[data-content="${tabId}"]`)?.classList
                    .add("active");
            });
        });
    });
</script>
