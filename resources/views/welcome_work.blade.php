@extends('layouts.app')
@section('content')
    <?php
    use App\Models\Blog;
    use App\Models\Category;
    use App\Models\User;
    use Carbon\Carbon;
    $class = '';
    $videorow = 0;
    $videobutrow = 0;
    $bannerimgurl = '';
    $bannerlinkurl = '';
    $bannermobileimgurl = '';
    $section2_cat = 0;
    $section3_cat = 0;
    $section4_cat = 0;
    $section5_cat = 0;
    $section6_cat = 0;
    $section7_cat = 0;
    $section8_cat = 0;
    $section9_cat = 0;
    $section10_cat = 0;
    $section11_cat = 0;
    $section12_cat = 0;
    $section13_cat = 0;
    $section14_cat = 0;
    $section15_cat = 0;
    $section16_cat = 0;
    $section17_cat = 0;
    $section18_cat = 0;
    $section19_cat = 0;
    $section20_cat = 0;
    $section21_cat = 0;
    $section22_cat = 0;
    
    $section2_cat_name = '';
    $section3_cat_name = '';
    $section4_cat_name = '';
    $section5_cat_name = '';
    $section6_cat_name = '';
    $section7_cat_name = '';
    $section8_cat_name = '';
    $section9_cat_name = '';
    $section10_cat_name = '';
    $section11_cat_name = '';
    $section12_cat_name = '';
    $section13_cat_name = '';
    $section14_cat_name = '';
    $section15_cat_name = '';
    $section16_cat_name = '';
    $section17_cat_name = '';
    $section18_cat_name = '';
    $section19_cat_name = '';
    $section20_cat_name = '';
    $section21_cat_name = '';
    $section22_cat_name = '';
    
    $section2_cat_siteurl = '';
    $section3_cat_siteurl = '';
    $section4_cat_siteurl = '';
    $section5_cat_siteurl = '';
    $section6_cat_siteurl = '';
    $section7_cat_siteurl = '';
    $section8_cat_siteurl = '';
    $section9_cat_siteurl = '';
    $section10_cat_siteurl = '';
    $section11_cat_siteurl = '';
    $section12_cat_siteurl = '';
    $section13_cat_siteurl = '';
    $section14_cat_siteurl = '';
    $section15_cat_siteurl = '';
    $section16_cat_siteurl = '';
    $section17_cat_siteurl = '';
    $section18_cat_siteurl = '';
    $section19_cat_siteurl = '';
    $section20_cat_siteurl = '';
    $section21_cat_siteurl = '';
    $section22_cat_siteurl = '';
    
    $setting = App\Models\Setting::where('id', '1')->first();
    // echo($data["categories"]);
    // print_r($data['homeSections']);
    foreach ($data['homeSections'] as $section) {
        if (strtolower($section->title) == 'banner') {
            $bannerimgurl = $section->image_url;
            $bannerlinkurl = $section->image_url;
        } elseif (strtolower($section->title) == 'bannermobile') {
            $bannermobileimgurl = $section->image_url;
        } elseif (strtolower($section->title) == 'section2') {
            $section2_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section3') {
            $section3_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section4') {
            $section4_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section5') {
            $section5_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section6') {
            $section6_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section7') {
            $section7_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section8') {
            $section8_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section9') {
            $section9_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section10') {
            $section10_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section11') {
            $section11_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section12') {
            $section12_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section13') {
            $section13_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section14') {
            $section14_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section15') {
            $section15_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section16') {
            $section16_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section17') {
            $section17_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section18') {
            $section18_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section19') {
            $section19_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section20') {
            $section20_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section21') {
            $section21_cat = $section->catid;
        } elseif (strtolower($section->title) == 'section22') {
            $section22_cat = $section->catid;
        }
    }
    
    // echo "Section2 Category ID: " . ($section2_cat ?? "Not Found") . PHP_EOL;
    if (!empty($section2_cat) && $data['categories']) {
        $section2_cat_object = $data['categories']->firstWhere('id', $section2_cat);
    
        if ($section2_cat_object) {
            $section2_cat_name = $section2_cat_object->name;
            $section2_cat_siteurl = $section2_cat_object->site_url;
        }
    }
    if (!empty($section3_cat) && $data['categories']) {
        $section3_cat_object = $data['categories']->firstWhere('id', $section3_cat);
    
        if ($section3_cat_object) {
            $section3_cat_name = $section3_cat_object->name;
            $section3_cat_siteurl = $section3_cat_object->site_url;
        }
    }
    if (!empty($section4_cat) && $data['categories']) {
        $section4_cat_object = $data['categories']->firstWhere('id', $section4_cat);
    
        if ($section4_cat_object) {
            $section4_cat_name = $section4_cat_object->name;
            $section4_cat_siteurl = $section4_cat_object->site_url;
        }
    }
    
    if (!empty($section5_cat) && $data['categories']) {
        $section5_cat_object = $data['categories']->firstWhere('id', $section5_cat);
    
        if ($section5_cat_object) {
            $section5_cat_name = $section5_cat_object->name;
            $section5_cat_siteurl = $section5_cat_object->site_url;
        }
    }
    if (!empty($section6_cat) && $data['categories']) {
        $section6_cat_object = $data['categories']->firstWhere('id', $section6_cat);
    
        if ($section6_cat_object) {
            $section6_cat_name = $section6_cat_object->name;
            $section6_cat_siteurl = $section6_cat_object->site_url;
        }
    }
    if (!empty($section7_cat) && $data['categories']) {
        $section7_cat_object = $data['categories']->firstWhere('id', $section7_cat);
    
        if ($section7_cat_object) {
            $section7_cat_name = $section7_cat_object->name;
            $section7_cat_siteurl = $section7_cat_object->site_url;
        }
    }
    if (!empty($section8_cat) && $data['categories']) {
        $section8_cat_object = $data['categories']->firstWhere('id', $section8_cat);
    
        if ($section8_cat_object) {
            $section8_cat_name = $section8_cat_object->name;
            $section8_cat_siteurl = $section8_cat_object->site_url;
        }
    }
    if (!empty($section9_cat) && $data['categories']) {
        $section9_cat_object = $data['categories']->firstWhere('id', $section9_cat);
    
        if ($section9_cat_object) {
            $section9_cat_name = $section9_cat_object->name;
            $section9_cat_siteurl = $section9_cat_object->site_url;
        }
    }
    if (!empty($section10_cat) && $data['categories']) {
        $section10_cat_object = $data['categories']->firstWhere('id', $section10_cat);
    
        if ($section10_cat_object) {
            $section10_cat_name = $section10_cat_object->name;
            $section10_cat_siteurl = $section10_cat_object->site_url;
        }
    }
    if (!empty($section11_cat) && $data['categories']) {
        $section11_cat_object = $data['categories']->firstWhere('id', $section11_cat);
    
        if ($section11_cat_object) {
            $section11_cat_name = $section11_cat_object->name;
            $section11_cat_siteurl = $section11_cat_object->site_url;
        }
    }
    if (!empty($section12_cat) && $data['categories']) {
        $section12_cat_object = $data['categories']->firstWhere('id', $section12_cat);
    
        if ($section12_cat_object) {
            $section12_cat_name = $section12_cat_object->name;
            $section12_cat_siteurl = $section12_cat_object->site_url;
        }
    }
    if (!empty($section13_cat) && $data['categories']) {
        $section13_cat_object = $data['categories']->firstWhere('id', $section13_cat);
    
        if ($section13_cat_object) {
            $section13_cat_name = $section13_cat_object->name;
            $section13_cat_siteurl = $section13_cat_object->site_url;
        }
    }
    if (!empty($section14_cat) && $data['categories']) {
        $section14_cat_object = $data['categories']->firstWhere('id', $section14_cat);
    
        if ($section14_cat_object) {
            $section14_cat_name = $section14_cat_object->name;
            $section14_cat_siteurl = $section14_cat_object->site_url;
        }
    }
    if (!empty($section15_cat) && $data['categories']) {
        $section15_cat_object = $data['categories']->firstWhere('id', $section15_cat);
    
        if ($section15_cat_object) {
            $section15_cat_name = $section15_cat_object->name;
            $section15_cat_siteurl = $section15_cat_object->site_url;
        }
    }
    if (!empty($section16_cat) && $data['categories']) {
        $section16_cat_object = $data['categories']->firstWhere('id', $section16_cat);
    
        if ($section16_cat_object) {
            $section16_cat_name = $section16_cat_object->name;
            $section16_cat_siteurl = $section16_cat_object->site_url;
        }
    }
    if (!empty($section17_cat) && $data['categories']) {
        $section17_cat_object = $data['categories']->firstWhere('id', $section17_cat);
    
        if ($section17_cat_object) {
            $section17_cat_name = $section17_cat_object->name;
            $section17_cat_siteurl = $section17_cat_object->site_url;
        }
    }
    if (!empty($section18_cat) && $data['categories']) {
        $section18_cat_object = $data['categories']->firstWhere('id', $section18_cat);
    
        if ($section18_cat_object) {
            $section18_cat_name = $section18_cat_object->name;
            $section18_cat_siteurl = $section18_cat_object->site_url;
        }
    }
    if (!empty($section19_cat) && $data['categories']) {
        $section19_cat_object = $data['categories']->firstWhere('id', $section19_cat);
    
        if ($section19_cat_object) {
            $section19_cat_name = $section19_cat_object->name;
            $section19_cat_siteurl = $section19_cat_object->site_url;
        }
    }
    if (!empty($section20_cat) && $data['categories']) {
        $section20_cat_object = $data['categories']->firstWhere('id', $section20_cat);
    
        if ($section20_cat_object) {
            $section20_cat_name = $section20_cat_object->name;
            $section20_cat_siteurl = $section20_cat_object->site_url;
        }
    }
    if (!empty($section21_cat) && $data['categories']) {
        $section21_cat_object = $data['categories']->firstWhere('id', $section21_cat);
    
        if ($section21_cat_object) {
            $section21_cat_name = $section21_cat_object->name;
            $section21_cat_siteurl = $section21_cat_object->site_url;
        }
    }
    if (!empty($section22_cat) && $data['categories']) {
        $section22_cat_object = $data['categories']->firstWhere('id', $section22_cat);
    
        if ($section22_cat_object) {
            $section22_cat_name = $section22_cat_object->name;
            $section22_cat_siteurl = $section22_cat_object->site_url;
        }
    }
    
    //print_r($section2_cat_object);
    
    ?>

    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<?php
  $latest_blog = Blog::with('category')
  ->where('status', 1)
  ->where('breaking_status', 1)
  ->where('sequence_id', 0)
  ->whereDate('created_at', Carbon::today()) 
  ->orderBy('created_at', 'DESC')
  ->first();
  if(isset($latest_blog)){
    $blogname= $latest_blog->name;
?>
  

    <div class="brk-m">
        <div class="cm-container">
            <div class="brk-news-wrap">
                <div class="brk-l">
                    <h4><span class="ticker_icon"><i class="fa fa-bolt" aria-hidden="true"></i></span>Breaking News</h4>
                </div>
               
                <div class="brk-r">
                    <a class="brk-link" href="{{ url('/breakingNews') }}">
                        {{ $blogname }}
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
    <div class="banner-area">
        <div class="cm_banner cm_banner-five">
            <div class="banner-inner">
                <div class="cm-container ">
                    <div class="spl-banner-link">
                        <img loading="lazy" class="spl-pc d-none d-md-block" src="{{ $bannerimgurl }}" alt="Banner">
                        <img loading="lazy" class="spl-mobile d-block d-md-none" src="{{ $bannermobileimgurl }}"
                            alt="Banner">
                    </div>
                    <div class="top-news-banner">
                        <!-- Top News Banner -->
                        <?php
                        $sequenceBlogs = Blog::with('category')->where('status', '1')->where('sequence_id', '>', 2)->orderBy('sequence_id', 'asc')->limit(2)->get();
                        // $sequenceBlogs = Blog::with('category')->where('status', '1')->where('AppHitCount', '>', 0)->orderBy('AppHitCount', 'desc')->limit(3)->get();
                        ?>
                        <div class="top-news-wrap  od2">
                            {{-- <div class="topStories">
                                <div class="inner">
                                    <span class="text">Top Stories</span>
                                </div>
                            </div>
                            <ul class="nws_list">
                                <li class="nwsItem">
                                    <a href="#" class="nwsImg"><img
                                            src="https://www.newsnmf.com/file/New_Project1724556901.jpg" alt=""></a>
                                    <a class="nwsTittle" href="#">
                                        प्रभु कृष्ण के बाल रूप लड्डू गोपाल को घर में लाने के नियम क्या हैं ? राजपुरोहित मधुर
                                        जी
                                    </a>
                                </li>
                                <li class="nwsItem">
                                    <a href="#" class="nwsImg"><img
                                            src="https://www.newsnmf.com/file/ishan_bcci1724483483.png" alt=""></a>
                                    <a class="nwsTittle" href="#">
                                        Champions Trophy 2025 से PAK के खिलाफ साजिश का हुआ पर्दाफाश ।
                                    </a>
                                </li>
                                <li class="nwsItem">
                                    <a href="#" class="nwsImg"><img
                                            src="	https://www.newsnmf.com/file/karnimata1716888917.jpg" alt=""></a>
                                    <a class="nwsTittle" href="#">
                                        Johnny Lever: पेट पालने के लिए बेचे ‘पेन’, सड़कों पर किया ‘डांस’ और फिर कैसे बन गए
                                    </a>
                                </li>
                                <li class="nwsItem">
                                    <a href="#" class="nwsImg"><img
                                            src="	https://www.newsnmf.com/file/dhari_devi1716292049.jpg" alt=""></a>
                                    <a class="nwsTittle" href="#">
                                        29 March को सूर्य ग्रहण में Shani किनका खेल बिगाड़ेंगे, किनकी जिद्दी किस्मत चमकाएंगे
                                        ?
                                    </a>
                                </li>
                                <li class="nwsItem">
                                    <a href="#" class="nwsImg"><img
                                            src="	https://www.newsnmf.com/file/jinha_house1742407242.jpg"
                                            alt=""></a>
                                    <a class="nwsTittle" href="#">
                                        मुंबई का जिन्ना हाउस, एक बंगला जिसने बदल दिया था भारत का नक्शा
                                    </a>
                                </li>
                            </ul> --}}

                            @foreach ($sequenceBlogs as $blog)
                                <?php
                                
                                $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                                $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '#';
                                $imageUrl = config('global.blog_images_everywhere')($blog);
                                
                                ?>
                                <div class="top-news-box top_n">
                                    <a href="{{ $blogUrl }}" class="news-card-bg"
                                        style="background-image: url('{{ asset($imageUrl) }}');" loading="lazy"></a>
                                    <div class="news-card-title">
                                        <a href="{{ $blogUrl }}" class="title_link">
                                            {{ $blog->name }}
                                        </a>
                                    </div>
                                </div>
                               
                            @endforeach
                            {{-- <ul class="related-nws">
                                <li class="related-nws-item" >
                                    <h3>1</h3> <a href="#" class="related-nws-link">
                                        Dhami को हटाने का एजेंडा चलाने वालों को BJP ने दिया मुंहतोड़ जवाब
                                    </a>
                                </li>
                                <li class="related-nws-item">
                                    <h3>2</h3> <a href="#" class="related-nws-link">
                                        29 March को सूर्य ग्रहण में Shani किनका खेल बिगाड़ेंगे, किनकी जिद्दी किस्मत
                                        चमकाएंगे ?
                                    </a>
                                </li>
                                <li class="related-nws-item">
                                    <h3>3</h3> <a href="#" class="related-nws-link">
                                        भरी संसद में अखिलेश ने की योगी आदित्यनाथ की तारीफ़, सुनकर सभी रह गए दंग
                                    </a>
                                </li>
                            
                            </ul> --}}
                        </div>

                        <!-- Main News Carousel -->
                        <?php
                        $banner_blog = Blog::with(['category', 'author'])
                            ->where('status', '1')
                            ->where('sequence_id', '>', 0)
                            ->orderBy('sequence_id', 'asc')
                            ->limit(2)
                            ->get();
                        ?>

                        <div class="top-news-wrap-main">
                            <div class="top-news-box-main gutter-left">
                                <div class="owl-carousel cm_banner-carousel-five">
                                    @foreach ($banner_blog as $blog)
                                        <?php
                                        $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                                        $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '#';
                                        $imageUrl = config('global.blog_images_everywhere')($blog);
                                        $authorUrl = $blog->author ? asset('/author/' . str_replace(' ', '_', optional($blog->author)->url_name ?? '-')) : '#';
                                        ?>
                                      <div class="item">
                                        <div class="post_thumb" onclick="window.open('{{ $blogUrl }}','_self');" style="cursor: pointer;">
                                           <div class="mainImg">
                                            <img class="mainImg" src="{{ asset($imageUrl) }}" alt="{{ $blog->name }}" loading="lazy" >
                                           </div>
                                        </div>
                                        <div class="post-holder">
                                            <div class="nmf-tag"> <img class="nmfImg" loading="lazy" src="{{ asset('asset/images/logo.png') }}" alt="NMF logo"> NMF News</div>
                                            <div class="post_title">
                                                <a href="{{ $blogUrl }}">
                                                    @if ($blog->link != '')
                                                        <i class="fa fa-video-camera" aria-hidden="true"></i>
                                                    @endif
                                                    {{ $blog->name }}
                                                </a>
                                            </div>
                                    
                                            <div class="cm-post-meta">
                                                <ul class="post_meta">
                                                    @if ($blog->author)
                                                        <li>
                                                            <a href="{{ $authorUrl }}">
                                                                <i class="fa fa-user" aria-hidden="true">&nbsp;&nbsp;{{ optional($blog->author)->name ?? 'NMF News' }}</i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a href="{{ $blogUrl }}">
                                                            <i class="fa fa-calendar" aria-hidden="true">&nbsp;&nbsp;<time class="entry-date published" datetime="{{ $blog->created_at }}">{{ $blog->created_at->format('F d, Y') }}</time></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Live Updates -->
                        <?php
                        $latestBlogs = Blog::with('category')->where('status', '1')->orderBy('id', 'desc')->limit(10)->get();
                        ?>
                        <div class="top-news-wrap">
                            <!-- Additional Top News Box -->
                            <?php
                               $topboxBlogs = Blog::with('category')->where('status', '1')->where('sequence_id', '>', 4)->orderBy('sequence_id', 'asc')->limit(1)->get();
                               ?>
                                 @foreach ($topboxBlogs as $blog)
                                 <?php
                                 $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                                 $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '#';
                                 $imageUrl = config('global.blog_images_everywhere')($blog);
                                 ?>
                                 <div class="top-news-box top_n">
                                     <a href="{{ $blogUrl }}" class="news-card-bg"
                                         style="background-image: url('{{ asset($imageUrl) }}');" loading="lazy"></a>
                                     <div class="news-card-title">
                                         <a href="{{ $blogUrl }}" class="title_link">
                                             {{ $blog->name }}
                                         </a>
                                     </div>
                                 </div>
                             @endforeach
                            <div class="jst">
                                <div class="just_in_main">
                                    <div class="js_title">
                                        <h5 class="js_t">लाइव अपडेट</h5>
                                    </div>
                                    <ul class="js_block">
                                        @foreach ($latestBlogs as $blog)
                                            <?php
                                            $categorySlug = isset($blog->category->site_url) ? $blog->category->site_url : '';
                                            $blogUrl = $blog->site_url ? asset($categorySlug . '/' . $blog->site_url) : '#';
                                            $blogTime = $blog->created_at->format('g:i A');
                                            ?>
                                            <li class="js_article">
                                                <div class="js_left"></div>
                                                <div class="js_right">
                                                    <p>{{ $blogTime }}</p>
                                                    <a href="{{ $blogUrl }}">
                                                        {{ $blog->name }}
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="news-panel">
        <div class="cm-container">
            <?php if ($section2_cat_object): ?>
            <div class="news-tabs nwstb">
                <a class="newstab_title" href="{{ $section2_cat_siteurl }}">{{ $section2_cat_name }}</a>
                <a href="{{ $section2_cat_siteurl }}">अधिक<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @include('components.slider-two-news-5', [
                'cat_id' => $section2_cat,
                'leftTitle' => 'ताजा खबर',
                'middleTitle' => 'शीर्ष समाचार',
                'rightTitle' => 'वीडियो',
                'site_url' => $section2_cat_siteurl,
            ])
            <?php endif; ?>
            <div class="ads-container">
                <div class="topbar">
                    <div class="adtxt">Advertisement
                    </div>
                    <div class="ad-section">
                        <!-- HeaderResponsive_728 -->
                        <!-- NMF_webnew_horz_unit1 -->
                        <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3986924419662120"
                            data-ad-slot="6597015781" data-ad-format="auto" data-full-width-responsive="true"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <div class="web-stories-section">
        <?php
        $allWebStories = App\Models\WebStories::with('category', 'webStoryFiles')->where('status', '1')->orderBy('id', 'DESC')->limit(10)->get();
        ?>
        @include('components.webstory', [
            'webStories' => $allWebStories,
        ])
        {{-- @include('components.webstory') --}}
    </div>

    <div class="news-panel">
        <div class="cm-container">
            <?php if ($section3_cat_object): ?>
            <div class="news-tabs nwstb">
                <a class="newstab_title" href="{{ $section3_cat_siteurl }}">{{ $section3_cat_name }}</a>
                <a href="{{ $section3_cat_siteurl }}">अधिक<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @include('components.slider-two-news-5', [
                'cat_id' => $section3_cat,
                'leftTitle' => 'ताजा खबर',
                'middleTitle' => 'शीर्ष समाचार',
                'rightTitle' => 'वीडियो',
                'site_url' => $section3_cat_siteurl,
            ])
            <?php endif; ?>
        </div>
    </div>

    <section class="video-section">
        @include('components.video-gallery-allcat')
    </section>
    <div class="top-news-area news-area">
        <div class="cm-container">
            <div id="media_image-5" class="addBgTop">
                <div class="adtxt">
                    Advertisement
                </div>
                <div class="ad-section">
                    <!-- HeaderResponsive_728 -->
                    <!-- NMF_webnew_horz_unit2 -->
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3986924419662120"
                        data-ad-slot="2615238860" data-ad-format="auto" data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </div>
    </div>
    {{-- <section class="photo_section">
        <div class="cm-container photo_block">
            <a href="{{ asset('photos') }}" class="vdo_title">फोटो गैलरी</a>
            @include('components.photo-gallery-12')
        </div>
    </section> --}}
    <div class="news-panel">
        <div class="cm-container">
            <?php if ($section4_cat_object): ?>
            <div class="news-tabs nwstb">
                <a class="newstab_title" href="{{ $section4_cat_siteurl }}">{{ $section4_cat_name }}</a>
                <a href="{{ $section4_cat_siteurl }}">अधिक<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @include('components.slider-two-news-5', [
                'cat_id' => $section4_cat,
                'leftTitle' => 'ताजा खबर',
                'middleTitle' => 'शीर्ष समाचार',
                'rightTitle' => 'वीडियो',
                'site_url' => $section4_cat_siteurl,
            ])
            <?php endif; ?>
        </div>
    </div>

  
    <div class="middle-news-area news-area">
        <div class="cm-container">
            <div class="left_and_right_layout_divider">
                <div class="lay_row">
                    <div class="cm-col-lg-8 cm-col-12 sticky_portion px-0">
                        <div id="primary" class="content-area">
                            <main id="main" class="site-main">
                                <?php if ($section12_cat_object): ?>
                                @include('components.slider-one-news-5', [
                                    'cat_id' => $section12_cat,
                                    'cat_name' => $section12_cat_name,
                                    'cat_site_url' => $section12_cat_siteurl,
                                ])
                                <?php endif; ?>
                                <div class="ads-container">
                                    <div class="topbar">
                                        <div class="adtxt">Advertisement
                                        </div>
                                        <div class="ad-section">
                                            <!-- NMF_webnew_horz_sm_unit2 -->
                                            <ins class="adsbygoogle" style="display:block"
                                                data-ad-client="ca-pub-3986924419662120" data-ad-slot="4278808355"
                                                data-ad-format="auto" data-full-width-responsive="true"></ins>
                                            <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($section13_cat_object): ?>
                                @include('components.slider-one-news-5', [
                                    'cat_id' => $section13_cat,
                                    'cat_name' => $section13_cat_name,
                                    'cat_site_url' => $section13_cat_siteurl,
                                ])
                                <?php endif; ?>

                                {{-- being ghumakad --}}
                                <?php if ($section14_cat_object): ?>
                                @include('components.photo-slider', [
                                    'cat_id' => $section14_cat,
                                    'cat_name' => $section14_cat_name,
                                    'cat_site_url' => $section14_cat_siteurl,
                                ])
                                <?php endif; ?>

                                <?php if ($section15_cat_object): ?>
                                @include('components.slider-one-news-5', [
                                    'cat_id' => $section15_cat,
                                    'cat_name' => $section15_cat_name,
                                    'cat_site_url' => $section15_cat_siteurl,
                                ])
                                <?php endif; ?>

                                <div id="media_image-7" class="addBgTop mx-3 mx-md-0">
                                    <div class="adtxt">
                                        Advertisement
                                    </div>
                                    <div class="ad-section">
                                        <!--  NMF_webnew_horz_sm_unit1 -->
                                        <ins class="adsbygoogle" style="display:block"
                                            data-ad-client="ca-pub-3986924419662120" data-ad-slot="2657770775"
                                            data-ad-format="auto" data-full-width-responsive="true"></ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </div>
                                </div>

                                <?php if ($section16_cat_object): ?>
                                @include('components.slider-one-news-5', [
                                    'cat_id' => $section16_cat,
                                    'cat_name' => $section16_cat_name,
                                    'cat_site_url' => $section16_cat_siteurl,
                                ])
                                <?php endif; ?>

                            </main>
                        </div>
                    </div>
                    <div class="cm-col-lg-4 cm-col-12 sticky_portion px-1">
                        <aside id="secondary" class="sidebar-widget-area">

                            <div id="media_image-2" class="adBgSidebar">
                                <div class="adtxt">
                                    Advertisement
                                </div>
                                <div class="ad-section">
                                    <!-- NMF_webnew_side_unit2 -->
                                    <ins class="adsbygoogle" style="display:block"
                                        data-ad-client="ca-pub-3986924419662120" data-ad-slot="6405444098"
                                        data-ad-format="auto" data-full-width-responsive="true"></ins>
                                    <script>
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                </div>
                            </div>

                            <?php if ($section17_cat_object): ?>
                            @include('components.sidebar-widget-3news', [
                                'cat_id' => $section17_cat,
                                'cat_name' => $section17_cat_name,
                                'cat_site_url' => $section17_cat_siteurl,
                            ])
                            <?php endif; ?>

                            <?php if ($section18_cat_object): ?>
                            @include('components.sidebar-widget-3news', [
                                'cat_id' => $section18_cat,
                                'cat_name' => $section18_cat_name,
                                'cat_site_url' => $section18_cat_siteurl,
                            ])
                            <?php endif; ?>
                            {{-- ----Static Voting Poll Percentage--- --}}
                            {{-- <div id="categories-2" class="widget widget_categories">
                            
                                <div class="et__box--wrapper">
                                    <header>अहंकार पर Virat Kohli ने Modi से ऐसा क्या कहा जो देखते ही देखते वायरल हो गया ?
                                    </header>
                                    <div class="et__poll--area">
                                        <label for="" class="et__box">
                                            <div class="et__row">
                                                <div class="et__column">
                                                    <span class="et__circle"></span>
                                                    <span class="et__title">Good</span>
                                                </div>
                                                <span class="et__percent">100%</span>
                                            </div>
                                            <div class="et__progress" style="--w:100;"></div>
                                        </label>
                                        <label for="" class="et__box">
                                            <div class="et__row">
                                                <div class="et__column">
                                                    <span class="et__circle"></span>
                                                    <span class="et__title">Bad</span>
                                                </div>
                                                <span class="et__percent">70%</span>
                                            </div>
                                            <div class="et__progress" style="--w:70;"></div>
                                        </label>
                                        <label for="" class="et__box">
                                            <div class="et__row">
                                                <div class="et__column">
                                                    <span class="et__circle"></span>
                                                    <span class="et__title">No Comment</span>
                                                </div>
                                                <span class="et__percent">60%</span>
                                            </div>
                                            <div class="et__progress" style="--w:60;"></div>
                                        </label>

                                    </div>
                                </div>

                            </div> --}}
                            <div id="categories-2" class="widget widget_categories">
                                <div class="news-tab">
                                    <?php
                                    // $data['randomBlog'];
                                    ?>
                                    @include('components.vote', ['randomBlog' => $data['randomBlog']])

                                    @if (session('votemessage'))
                                        <div class="alert alert-success mt-3">
                                            {{ session('votemessage') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <?php if ($section19_cat_object): ?>
                            @include('components.sidebar-widget-3news', [
                                'cat_id' => $section19_cat,
                                'cat_name' => $section19_cat_name,
                                'cat_site_url' => $section19_cat_siteurl,
                            ])
                            <?php endif; ?>

                            <?php if ($section20_cat_object): ?>
                            @include('components.sidebar-widget-3news', [
                                'cat_id' => $section20_cat,
                                'cat_name' => $section20_cat_name,
                                'cat_site_url' => $section20_cat_siteurl,
                            ])
                            <?php endif; ?>
                            
                            <div id="media_image-3" class="adBgSidebar">
                                <div class="adtxt">
                                    Advertisement
                                </div>
                                <div class="ad-section">
                                    <!-- NMF_webnew_side_unit3 need to get the slot this is duplicate slot -->
                                    <ins class="adsbygoogle" style="display:block"
                                        data-ad-client="ca-pub-3986924419662120" data-ad-slot="6405444098"
                                        data-ad-format="auto" data-full-width-responsive="true"></ins>
                                    <script>
                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="news-panel">
        <div class="cm-container">
            <?php if ($section21_cat_object): ?>
            <div class="news-tabs nwstb">
                <a class="newstab_title" href="{{ $section21_cat_siteurl }}">{{ $section21_cat_name }}</a>
                <a href="{{ $section21_cat_siteurl }}">अधिक<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @include('components.slider-two-news-5', [
                'cat_id' => $section21_cat,
                'leftTitle' => 'ताजा खबर',
                'middleTitle' => 'शीर्ष समाचार',
                'rightTitle' => 'वीडियो',
                'site_url' => $section21_cat_siteurl,
            ])
            <?php endif; ?>
        </div>
    </div>

    <div class="bottom-news-area news-area">
        <div class="cm-container">
            <div id="media_image-9" class="addBgTop mb-3">
                <div class="adtxt">
                    Advertisement
                </div>
                <div class="ad-section">
                    <!-- NMF_webnew_horz_unit3 -->
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3986924419662120"
                        data-ad-slot="9703391391" data-ad-format="auto" data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>

            <?php if ($section22_cat_object): ?>
            <div class="news-tabs nwstb">
                <a class="newstab_title me-3" href="{{ $section22_cat_siteurl }}">{{ $section22_cat_name }}</a>
                <a href="{{ $section22_cat_siteurl }}">अधिक<i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            @include('components.slider-two-news-5', [
                'cat_id' => $section22_cat,
                'leftTitle' => 'ताजा खबर',
                'middleTitle' => 'शीर्ष समाचार',
                'rightTitle' => 'वीडियो',
                'site_url' => $section22_cat_siteurl,
            ])
            <?php endif; ?>

        </div>
    </div>
    <!-- video js -->


    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> --}}

    <!-- Initialize Swiper -->

    <script>
        const progressCircle = document.querySelector(".autoplay-progress svg");
        const progressContent = document.querySelector(".autoplay-progress span");
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 3800,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            on: {
                autoplayTimeLeft(s, time, progress) {
                    progressCircle.style.setProperty("--progress", 1 - progress);
                    progressContent.textContent = `${Math.ceil(time / 1000)}s`;
                }
            }
        });
        var swiperr = new Swiper(".mySwiper2", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 6800,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            on: {
                autoplayTimeLeft(s, time, progress) {
                    progressCircle.style.setProperty("--progress", 1 - progress);
                    progressContent.textContent = `${Math.ceil(time / 1000)}s`;
                }
            }
        });

        // static voting poll percentage js
        // const options = document.querySelectorAll(".et__box"),
        //     etProgressBar = document.querySelector(".et__percent");

        // for (let i = 0; i < options.length; i++) {
        //     options[i].addEventListener("click", () => {
        //         for (let j = 0; j < options.length; j++) {
        //             if (options[j].classList.contains("et__selected")) {
        //                 options[j].classList.remove("et__selected");
        //             }
        //         }
        //         options[i].classList.add("et__selected");
        //         for (let k = 0; k < options.length; k++) {
        //             options[i].classList.add("et__selectedAll");
        //         }
        //     });
        // }
    </script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
@endsection
