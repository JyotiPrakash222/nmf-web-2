@props(['ad'])

<!--  NL1020:12Sep2025:ID changed to optional -->
<div id="media_image-{{ optional($ad)->id }}" class="adBgSidebar">
    <div class="adtxt">
        Advertisement
    </div>
    <div class="ad-section side_unit2">
        @if ($ad)
            @if ($ad->is_google_ad)
                <!-- Google Ad -->
                <ins class="adsbygoogle" style="display:block" data-ad-client="{{ $ad->google_client }}"
                    data-ad-slot="{{ $ad->google_slot }}" data-ad-format="auto" data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @else
                <!-- Custom Image Ad -->
                @if (!empty($ad->file_path) || !empty($ad->custom_image))
                    @php
                        $imagePath = $ad->file_path . '/' . $ad->custom_image;
                    @endphp

                    <a href="{{ $ad->custom_link ?? '#' }}" target="_blank">
                        <img src="{{ asset($imagePath) }}" alt="Advertisement" />
                    </a>
                @endif
            @endif
        @endif
    </div>
</div>
