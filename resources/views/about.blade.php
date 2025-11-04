@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('asset/css/about.css') }}" type="text/css" media="all" />
    <section class="about_section">
        <div class="cm-container about-block">
            <div class="breadcrumb  default-breadcrumb px-0 mt-3" style="display: block;">
                <nav role="navigation" aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs" itemprop="breadcrumb">
                    <ul class="trail-items" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                        <meta name="numberOfItems" content="3">
                        <meta name="itemListOrder" content="Ascending">
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                            class="trail-item trail-begin"><a href="/" rel="home" itemprop="item"><span
                                    itemprop="name">Home</span></a>
                            <meta itemprop="position" content="1">
                        </li>
                        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                            class="trail-item trail-end"><a href="" itemprop="item"><span itemprop="name">About
                                    us</span></a>
                            <meta itemprop="position" content="3">
                        </li>
                    </ul>
                </nav>
            </div>
            <h2>About us</h2>

            <div class="about-img">
                <img class="d-none d-md-block " src="https://www.newsnmf.com/file/banner/about.webp" alt="About Us" class="img-fluid">
                <img class="d-block d-md-none " src="https://www.newsnmf.com/file/banner/aboutm.jpg" alt="About Us" class="img-fluid">
            </div>

            <div class="about-content">
                <strong class="abt_h">NMF NEWS: Unveiling the Truth Behind the Headlines</strong>
                <p class="abt_p">NMF NEWS breaks the mould of conventional news reporting by providing in-depth analysis
                    and the "real face" of every story, all accessible with a single click. Dive into the latest news across
                    India and the globe, encompassing politics, business, technology, entertainment, sports, science, and
                    more. We offer a comprehensive perspective on the world around you, ensuring that every angle is
                    explored, and no detail is left behind.

                    Our team of expert journalists and analysts work tirelessly to bring you not just the headlines, but the
                    context and implications behind them. Whether it's breaking news or long-form features, we go beyond the
                    surface to offer insights that matter. With NMF NEWS, you can stay informed with a deeper understanding
                    of the issues that shape our world.

                    In addition to traditional news coverage, we offer specialized segments on emerging trends,
                    investigative reports, and expert opinions that challenge the status quo. Our interactive platform
                    ensures that readers can engage with the content, ask questions, and contribute to ongoing discussions.
                    We strive to build a community of well-informed individuals who are not only aware of current events but
                    are also equipped to think critically about them.

                    Join us as we redefine news consumption for the modern age—where clarity, accuracy, and depth come
                    together to create an informed and empowered audience.</p>

                <strong class="abt_h">Personalized Content, Trusted Source</strong>
                <p class="abt_p">Our content is personalized to your interests, ensuring you receive the news that matters most to you. NMF NEWS is a copyright-protected platform, meticulously curated by Khetan Media Creation Pvt Ltd.</p>    

                <strong class="abt_h">Driven by Passion and Expertise</strong>
                <p class="abt_p">Founded in 2015 by Parmanand Khetan, a visionary entrepreneur with a deep understanding of the media landscape, Khetan Media Creations is driven by a relentless pursuit of excellence. We invite you to explore our world of creative content, meticulously crafted by our dedicated team.</p>

                <strong class="abt_h">Parmanand Khetan: The Guiding Force</strong>
                <p class="abt_p">Parmanand Khetan is a respected business leader, frequently sought after for his media expertise. He leads by example, consistently exceeding expectations and inspiring his team to do the same. While approachable and easygoing, his authority is never in question. He is known for his direct and decisive approach when needed, earning the respect of his colleagues. His ability to navigate the complexities of the media industry while maintaining a positive work environment is truly remarkable.</p>
                </div>
        </div>
    </section>

    {{-- <div class="cm-container" style="transform: none;">
                <div class="inner-page-wrapper" style="transform: none;">
                    <div id="primary" class="content-area" style="transform: none;">
                        <main id="main" class="site-main" style="transform: none;">
                            <div class="cm_post_page_lay_wrap" style="transform: none; margin-top:20px;">
                            
                            <h5>About Us</h5>
                            <h5>NMF NEWS: Unveiling the Truth Behind the Headlines</h5>
                            <p style="font-weight: 300;">NMF NEWS breaks the mould of conventional news reporting by providing in-depth analysis and the "real face" of every story, all accessible with a single click. Dive into the latest news across India and the globe, encompassing politics, business, technology, entertainment, sports, science, and more. We offer a comprehensive perspective on the world around you.</p>
                            <h5>Personalized Content, Trusted Source</h5>
                            <p style="font-weight: 300;">Our content is personalized to your interests, ensuring you receive the news that matters most to you. NMF NEWS is a copyright-protected platform, meticulously curated by Khetan Media Creation Pvt Ltd.</p>
                            <h5>Driven by Passion and Expertise</h5>
                            <p style="font-weight: 300;">Founded in 2015 by Parmanand Khetan, a visionary entrepreneur with a deep understanding of the media landscape, Khetan Media Creations is driven by a relentless pursuit of excellence. We invite you to explore our world of creative content, meticulously crafted by our dedicated team.</p>
                            <h5>Parmanand Khetan: The Guiding Force</h5>
                            <p style="font-weight: 300;">Parmanand Khetan is a respected business leader, frequently sought after for his media expertise. He leads by example, consistently exceeding expectations and inspiring his team to do the same. While approachable and easygoing, his authority is never in question. He is known for his direct and decisive approach when needed, earning the respect of his colleagues. His ability to navigate the complexities of the media industry while maintaining a positive work environment is truly remarkable.</p>

                            </div>
                        </main>
                    </div>
                </div>
</div> --}}
@endsection
