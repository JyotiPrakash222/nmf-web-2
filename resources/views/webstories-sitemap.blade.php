<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        <lastmod>{{ isset($url['lastmod']) ? \Carbon\Carbon::parse($url['lastmod'])->format('Y-m-d') : now()->format('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>{{ $url['priority'] ?? '0.5' }}</priority>
    </url>
@endforeach
</urlset>
