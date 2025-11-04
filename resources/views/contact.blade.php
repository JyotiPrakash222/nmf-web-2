@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{config('global.base_asset')}}asset/css/about.css" type="text/css" media="all" />
    <?php $setting = App\Models\Setting::where('id', 1)->first(); ?>

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
                            class="trail-item trail-end"><a href="" itemprop="item"><span itemprop="name">Contact
                                    us</span></a>
                            <meta itemprop="position" content="3">
                        </li>
                    </ul>
                </nav>
            </div>
            <h2>Contact us</h2>

            <div class="pb-3">
                <div class="contact-wrapper">
                    <div class="col-md-5">
                        <div class="contact-info h-100">
                            <h3 class="mb-4">Get in touch</h3>
                            <p class="mb-4">For any questions, comments, suggestions, feedback or advertisements, please
                                contact us.</p>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Address</h6>
                                    <p class="mb-0">D-4 1st Floor, Sector 10, Noida,<br> Uttar Pradesh 201301</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Phone</h6>
                                    <a href="tel:8076727261" class="mb-0">+91 8076727261</a>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Email</h6>
                                    <a href="mailto:info@kmcliv.com" class="mb-0">info@kmcliv.com</a>

                                </div>
                            </div>

                            <div class="social-links">
                                <h6 class="mb-3">Follow Us</h6>
                                <a href="https://{{ isset($setting->facebook) ? $setting->facebook : '' }}" target="_blank"
                                    class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://{{ isset($setting->twitterx) ? $setting->twitterx : '' }}" target="_blank"
                                    class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="https://{{ isset($setting->youtube) ? $setting->youtube : '' }}" target="_blank"
                                    class="social-icon"><i class="fab fa-youtube"></i></a>
                                <a href="https://{{ isset($setting->instagram) ? $setting->instagram : '' }}"
                                    target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
