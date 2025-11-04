@foreach ($reels as $index => $reel)
    <?php
    // Normalize image path: remove everything before "public/"
    $normalizedPath = preg_replace('/^.*public[\/\\\\]/', '', $reel->image_path);
    //$thumbPath = asset(trim($normalizedPath, '/') . '/' . ltrim($reel->thumb_image, '/'));
    $thumbPath = config('global.base_url_short_videos') . trim($normalizedPath, '/') . '/' . ltrim($reel->thumb_image, '/');
    $category_url = $reel->category->site_url ?? '';
    ?>
    <div class="web_s_all">
        <a href="{{ asset('/short-videos/' . $category_url . '/' . $reel->site_url) }}" target="_blank"
            class="story-card2">
            <div class="web-tag">{{ $reel->category->name }}</div>
            <div class="webs_img">
                <span class="story-play" aria-hidden="true"></span>
                <img src="{{ $thumbPath }}" alt="{{ $reel->title }}">
                <p class="storyTitle">{{ $reel->title }}</p>
            </div>
        </a>
    </div>

    {{-- @if (($index + 1) % 15 === 0)
        <div class="webstory-ad">
            <x-horizontal-sm-ad :ad="$reelAds['category_middle_horz_sm_ad1'] ?? null" />
        </div>
    @endif --}}
@endforeach
