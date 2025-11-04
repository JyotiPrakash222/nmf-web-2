<?php
use App\Models\Blog;

$galleryblogs = Blog::where('status', '1')
    ->whereNull('link')
    ->orderByDesc('id')
    ->take(12) // Also retrieves up to 3 records
    ->get();

?>


<div class="photo_grid">
    @foreach ($galleryblogs as $blog)
        <?php
        $imageUrl = config('global.blog_images_everywhere')($blog);
        $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
        $blogUrl = isset($blog->site_url) ? asset($categorySlug . '/' . $blog->site_url) : '#';
        ?>
        <div class="photo_item">
            <div class="photo_top">
                <a href="{{ $blogUrl }}">
                    <img src="{{ $imageUrl }}" alt="{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}" loading="lazy">
                </a>
                <div class="category_strip">
                    {{-- <span class="category">{{ $categoryName }}</span> --}}
                    <a href="{{ asset($categorySlug) }}" class="category">{{ $blog->category->name ?? '' }}</a>
                </div>
            </div>
            <div class="photo_bottom">
                <a href="{{ $blogUrl }}">{{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
            </div>
        </div>
    @endforeach
</div>
