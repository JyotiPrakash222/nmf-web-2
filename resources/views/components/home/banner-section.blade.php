@if (!empty($bannerimgurl) || !empty($bannermobileimgurl))
    <section class="spl-banner">
        <div class="spl-banner-link cm-container">
            @if (!empty($bannerimgurl))
                <a href="{{ $bannerlinkurl ?? '#' }}" target="_blank">
                    <img loading="lazy" class="spl-pc d-none d-md-block" src="{{ $bannerimgurl }}" alt="Banner">
                </a>
            @else
                {{-- Show separator only on md and above --}}
                <div class="d-none d-md-block" style="height:5px;background:#f8f9fa;border-bottom:1px solid #eee;">
                </div>
            @endif

            {{-- Mobile banner --}}

            @if (!empty($bannermobileimgurl))
                <a href="{{ $bannermobilelinkurl ?? '#' }}" target="_blank">
                    <img loading="lazy" class="spl-mobile d-block d-md-none" src="{{ $bannermobileimgurl }}"
                        alt="Banner">
                </a>
            @else
                {{-- Show separator only on small screens --}}
                <div class="d-block d-md-none" style="height:5px;background:#f8f9fa;border-bottom:1px solid #eee;">
                </div>
            @endif
        </div>
    </section>
@else
    <section class="spl-banner">
        <div class="spl-banner-link cm-container">
            <div style="height:5px;background:#f8f9fa;border-bottom:1px solid #eee;">
            </div>
        </div>
    </section>
@endif
