<?php
use App\Models\Blog;
$sliderBlog = Blog::where('status', '1')->where('categories_ids', $cat_id)->whereNull('link')->orderByDesc('id')->take(5)->get();
?>
<section class="cm-post-widget-section cm_middle_post_widget_six">
    <div class="section_inner">
        <div class="news-tabs tbs nwstb">
            <a class="newstab_title" href="{{ asset($cat_site_url) }}">
            <h2>
                {{ $cat_name }}
            </h2>
            </a> <a
                href="{{ $cat_site_url }}">अधिक<i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="owl-carousel middle_widget_six_carousel">
            @foreach ($sliderBlog as $blog)
                <?php
                //$imageUrl = config('global.blog_images_everywhere')($blog);
                  $imageUrl = cached_blog_image($blog); 
                $blogUrl = isset($blog->site_url) ? asset($cat_site_url . '/' . $blog->site_url) : '#';
                ?>
                <div class="item">
                    <div class="card post_thumb" style="background-image: url({{ $imageUrl }})"  onclick="window.location='{{ $blogUrl }}'">
                        <div class="card_content">
                            <div class="entry_cats">
                                <ul class="post-categories">
                                    <li><a href="{{ asset($cat_site_url) }}" rel="category tag">{{ $cat_name }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="post-title">
                                <a href="{{ $blogUrl }}"> {{ isset($blog->short_title) && $blog->short_title ? $blog->short_title : $blog->name }}</a>
                            </div>
                            {{-- <div class="cm-post-meta">
                                <ul class="post_meta">
                                    <li class="">
                                        <a href="#">
                                            <i class="fa fa-calendar" aria-hidden="true">&nbsp;&nbsp;<time
                                                class="entry-date published" datetime="2023-10-01">
                                                October 1, 2023</time>
                                            </i>
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
