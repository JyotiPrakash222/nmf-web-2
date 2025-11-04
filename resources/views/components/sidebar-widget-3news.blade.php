<?php
use App\Models\Blog;

$sidebarblogs = Blog::where('status', '1')
    ->where('categories_ids', $cat_id)
    ->orderByDesc('id')
    ->take(3) // Also retrieves up to 3 records
    ->get();

?>



<div id="categories-2" class="widget widget_categories">
    <div class="news-tabs widget_tab"><a class="newstab_title" href="{{ asset($cat_site_url) }}"
            >
            <h2 style="margin-right: 14px">
                {{ $cat_name }}
            </h2>
        </a> </div>
    <ul class="side_widgets">
        @foreach ($sidebarblogs as $blog)
            <?php
            $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
            //$imageUrl = config('global.blog_images_everywhere')($blog);

            $imageUrl = cached_blog_image($blog); 

            $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '#';
            ?>
            <li class="card_small">
                <div class="card_small_top">
                    <a href="{{ $blogUrl }}">
                        <img @if (!empty($imageUrl)) src="{{ asset($imageUrl) }}" @endif
                            alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}" loading="lazy">
                    </a>
                </div>
                <div class="card_small_title">

                    <a href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }} </a>
                </div>
            </li>
        @endforeach
    </ul>

    <a href="{{ $categorySlug }}" class="more_btn mt-3">

        अधिक <i class="fa-solid fa-caret-right"></i></a></a>
</div>
