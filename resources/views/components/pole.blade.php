<?php
    use App\Models\Blog;
    use App\Models\SubCategory;

    $allpolblogs = Blog::where('status', 1)
        ->where('categories_ids', $cat_id)
        ->whereNotNull('sub_category_id')
        ->where('sequence_id', 0)
        ->orderByDesc('id')
        ->limit(9)
        ->get();

    if ($allpolblogs->count() > 0) {
    $chunk_pols = $allpolblogs->chunk(ceil($allpolblogs->count() / 3));
?>
<div class="cm-container">
    <div class="news-tabs tbs nwstb">
        <a class="newstab_title" href="{{ asset($cat_site_url) }}">
            <h2>
                {{ $cat_name }}
            </h2>
        </a>
        <a href="{{ $cat_site_url }}">अधिक<i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="cs_row">
        @foreach ($chunk_pols as $chunk)
            <div class="custom_left">
                <div class="">
                    @foreach ($chunk as $pblog)
                        <?php
                        $imageUrl = config('global.blog_images_everywhere')($pblog);
                        $categorySlug = isset($pblog->category->site_url) ? $pblog->category->site_url : '';
                        $pblogUrl = isset($pblog->site_url) ? asset($categorySlug . '/' . $pblog->site_url) : '#';
                        ?>
                        <div class="card_small">
                            <div class="card_small_top">
                                @if ($pblog->link)
                                    <div class="video_icon">
                                        <i class="fa-solid fa-video"></i>
                                    </div>
                                @endif

                                <a href="{{ $pblogUrl }}">
                                    <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                                        alt="{{ isset($pblog->short_title) && $pblog->short_title ? $pblog->short_title : $pblog->name }}"
                                        loading="lazy">
                                </a>
                            </div>
                            <div class="card_small_title">
                                @php $subcat = $pblog->sub_category ?? null; @endphp
                                @if ($subcat)
                                    <a href="pol?subcat={{ $subcat->site_url }}" class="category_strip">
                                        <span class="category">{{ $subcat->name }}</span>
                                    </a>
                                @endif
                                <a href="{{ $pblogUrl }}">{{ isset($pblog->short_title) && $pblog->short_title ? $pblog->short_title : $pblog->name }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<?php
  }
?>
