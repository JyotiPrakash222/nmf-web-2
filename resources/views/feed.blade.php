<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>NMF News Feed</title>  <!-- in both channel and image -->
    <link>https://newsnmf.com</link>
    <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />
    <description>Latest news articles from NMF News</description>
    <language>hi-IN</language>
    <pubDate>{{ now()->toRfc2822String() }}</pubDate>
    <ttl>30</ttl>
    <image>
      <url>{{config('global.base_url_frontend')}}frontend/images/logo.png</url>
      <title>NMF News Feed</title>  <!-- in both channel and image -->
      <link>https://newsnmf.com</link>
    </image>

    @foreach($blogs as $blog)
      @if($blog->category)
        @php
          $author = \App\Models\User::find($blog->author);
        @endphp 
        <item>
          <title><![CDATA[{!! $blog->name !!}]]></title>
          <link>{{ url($blog->category->site_url . '/' . $blog->site_url) }}</link>
          <description><![CDATA[{!! \Illuminate\Support\Str::limit(strip_tags($blog->sort_description), 200) !!}]]></description>
          @if($author)
             @if($author->email)
              <author>{{ $author->email }} ({{ $author->name }})</author>
             @endif 
          @endif 
          <category><![CDATA[{!! $blog->category->name !!}]]></category>
          <pubDate>{{ \Carbon\Carbon::parse($blog->created_at)->toRfc2822String() }}</pubDate>
          <guid isPermaLink="false">blog-{{ $blog->id }}</guid>
        </item>
      @endif
    @endforeach

  </channel>
</rss>
