<link rel="stylesheet" href="{{ asset('asset/css/allstory.css') }}" type="text/css" media="all" />
<section class="web-stories-block">
        <div class="cm-container">
            <div class="web-stories">
                <div class="section-title story-title">
                    <h2>वेब स्टोरीज़</h2>
                    <a href="" class="section-btn see-more-btn">और देखें</a>
                </div>
                <?php
                
                $webStories = App\Models\WebStories::where('status', '1')->orderBy('id', 'DESC')->limit(6)->get();
                ?>
                <div class="stories-container">
                    <div class="swiper swiper2">
                        <div class="swiper-wrapper">
                            <!-- You can replace these slides with actual card content -->
                           
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/sports/6-indian-players-to-play-most-innings-in-international-cricket-kohli-set-to-break-dravids-record"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (1).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>अंतरराष्ट्रीय क्रिकेट में सबसे ज्यादा पारियां खेलने वाले 6 भारतीय खिलाड़ी, कोहली का
                                        </h3>
                                </a></div>
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/lifestyle/diwali-2024-5-must-visit-places-in-india-to-celebrate-the-festival-of-lights"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (6).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>दिवाली 2024: रोशनी का त्योहार मनाने के लिए भारत में 5 जरूरी जगहें</h3>
                                </a></div>
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/entertainment/money-heist-to-elite-popular-spanish-shows-and-movies"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (3).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>दिवाली 2024: रोशनी का त्योहार मनाने के लिए भारत में 5 जरूरी जगहें</h3>
                                </a></div>
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/sports/teams-with-longest-winning-streak-in-club-football"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (4).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>दिवाली 2024: रोशनी का त्योहार मनाने के लिए भारत में 5 जरूरी जगहें</h3>
                                </a></div>
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/lifestyle/diwali-2024-5-must-visit-places-in-india-to-celebrate-the-festival-of-lights"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (2).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>दिवाली 2024: रोशनी का त्योहार मनाने के लिए भारत में 5 जरूरी जगहें</h3>
                                </a></div>
                            <div class="swiper-slide web_s"><a
                                    href="http://127.0.0.1:8000/web-stories/entertainment/money-heist-to-elite-popular-spanish-shows-and-movies"
                                    target="_blank" class="category-thumbnail">
                                    <img src="{{ asset('file/webstories/wb (5).webp') }}" alt="अंतरराष्ट्रीय क्रिकेट में">
                                    <span class="reals-icon"></span>
                                    <h3>मनी हीस्ट टू एलीट: लोकप्रिय स्पेनिश शो और फिल्में</h3>
                                </a></div>

                        </div>
                        {{-- <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

            <script>
        const swipernew = new Swiper(".swiper2", {
  direction: "horizontal",
  loop: true,
  slidesPerView: 2.07, 
  spaceBetween: 10, 

  pagination: {
    el: ".swiper-pagination",
  },

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },

  scrollbar: {
    el: ".swiper-scrollbar",
  },

  breakpoints: {
    600: {
      slidesPerView: 5, 
      spaceBetween: 5, 
    }
  }
});

              </script>
            {{-- <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> --}}
