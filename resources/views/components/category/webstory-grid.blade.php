@foreach ($webStories as $story)
    @php
        $story_file = optional($story->webStoryFiles->first());
        $get_baseUrl = config('global.base_url_web_stories');
        $story_file_path = '';

        if ($story_file && strpos($story_file->filepath, 'file') !== false) {
            $findfilepos = strpos($story_file->filepath, 'file');
            $story_file_path = substr($story_file->filepath, $findfilepos);
            $story_file_path = $get_baseUrl . $story_file_path . '/' . $story_file->filename;
        }

        $category_url = $category->site_url ?? '';
        $story_file->filename;

        $category_url = $category->site_url ?? '';
    @endphp

    <div class="card --newsCard">
        <a href="{{ asset('/web-stories/' . $category_url . '/' . $story->siteurl) }}" target="_blank" class="story-card2">
            <div class="--newsCard-imgWrapper">
                <img src="{{ asset($story_file_path) }}" alt="{{ $story->name }}" class="--newsCard-img">
                <span class="reals-icon"></span>
            </div>
            <div class="card-body --newsCard-body">
                <p class="--newsCard-title">
                    {{ $story->name }}</p>
            </div>
        </a>
    </div>

    {{-- Show ad only once after the 4th story --}}
    {{-- @if ($loop->iteration === 4)
        <div class="card --newsCard">
            <x-horizontal-sm-ad :ad="$webStoryCategoryAds['category_middle_horz_sm_ad1'] ?? null" />
        </div>
    @endif --}}
@endforeach
