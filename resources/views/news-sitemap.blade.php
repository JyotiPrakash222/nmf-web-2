<?xml version="1.0" encoding="UTF-8"?>
<urlset 
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
>
@foreach($blogs as $blog)
    @if($blog->category)
        @php
            $imagePath = config('global.blog_images_everywhere')($blog ?? null);
            $imageUrl = asset($imagePath);
        @endphp

        <url>
            <loc>{{ url($blog->category->site_url . '/' . $blog->site_url) }}</loc>

            <news:news>
                <news:publication>
                    <news:name><![CDATA[NMF News]]></news:name>
                    <news:language>hi</news:language>
                </news:publication>
                <news:publication_date>{{ \Carbon\Carbon::parse($blog->created_at)->toAtomString() }}</news:publication_date>
                <news:title><![CDATA[{{ $blog->name }}]]></news:title>
                @if($blog->keyword)
                    <news:keywords><![CDATA[{{ $blog->keyword }}]]></news:keywords>
                @endif
            </news:news>

            <lastmod>{{ \Carbon\Carbon::parse($blog->updated_at)->toAtomString() }}</lastmod>

            @if($imageUrl)
                <image:image>
                    <image:loc>{{ $imageUrl }}</image:loc>
                </image:image>
            @endif
        </url>
    @endif
@endforeach
</urlset>
