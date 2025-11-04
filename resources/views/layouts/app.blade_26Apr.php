<!doctype html>
<html lang="en-US">

<head>
    <?php $setting = App\Models\Setting::where('id', 1)->first(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('/frontend/images/logo.png') }}" rel="shortcut icon" type="image/x-icon">
    <?php
    
    function DisplayTitleDescription($title)
    {
        $URL = strtolower($_SERVER['REQUEST_URI']);
        $statepos = strrpos($URL, '?');
        $statename = '';
        $DisplayTitle = '';
        $DisplayDescription = '';
        if (str_contains($URL, 'dharma-gyan')) {
            $DisplayTitle = 'धर्म ज्ञान: Spiritual Insights, Teachings, and Religious News | NMF News';
            $DisplayDescription = 'NMF News पर पाएं धर्म, आध्यात्मिकता और जीवन से जुड़ी गहरी शिक्षाएँ। वेद, उपनिषद, धार्मिक अनुष्ठान, और संतों के उपदेशों के साथ आध्यात्मिक यात्रा के बारे में जानें। धर्म ज्ञान के हर पहलू पर विशेषज्ञ विश्लेषण।';
        } elseif (str_contains($URL, 'entertainment')) {
            $DisplayTitle = 'मनोरंजन समाचार: Latest Entertainment News, Movies, Celebrities & More | NMF News';
            $DisplayDescription = 'NMF News पर पाएं ताजगी से भरपूर मनोरंजन की खबरें, फिल्म रिव्यू, सेलिब्रिटी गॉसिप, टीवी शो और म्यूजिक इंडस्ट्री की सभी अपडेट्स। मनोरंजन की दुनिया की हर प्रमुख खबर, सिर्फ NMF News पर।';
        } elseif (str_contains($URL, 'exclusive')) {
            $DisplayTitle = 'ताज़ा खबरें, स्पेशल रिपोर्ट्स और ब्रेकिंग स्टोरीज़ | NMF News';
            $DisplayDescription = 'NMF News पर पढ़ें एक्सक्लूसिव रिपोर्ट्स, ब्रेकिंग न्यूज़ और विशेष जांच। हर दिन की ताज़ा खबरों के साथ जानें वो ख़बरें, जो आपको कहीं और नहीं मिलेंगी। देश-दुनिया की खास खबरों के लिए जुड़ें NMF News के साथ।';
        } elseif (str_contains($URL, 'world')) {
            $DisplayTitle = 'दुनिया न्यूज़: Latest World News, Global Updates, Breaking Headlines | NMF News';
            $DisplayDescription = 'दुनिया भर की ताज़ा खबरें, ब्रेकिंग न्यूज़ और वैश्विक घटनाएँ पढ़ें। राजनीति, समाज, व्यापार, विज्ञान, खेल और अन्य महत्वपूर्ण ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'utility')) {
            $DisplayTitle = 'Utility News: Useful Tips, Guides, Resources & Updates | NMF News';
            $DisplayDescription = "NMF News के 'यूटिलिटी' सेक्शन में पाएं उपयोगी टिप्स, गाइड्स और संसाधन जो आपकी ज़िंदगी को आसान बनाएं। स्वास्थ्य, वित्त, टेक्नोलॉजी और अन्य महत्वपूर्ण जानकारी के लिए जुड़े रहें।";
        } elseif (str_contains($URL, 'technology')) {
            $DisplayTitle = 'टेक्नोलॉजी समाचार: Latest Tech Updates, Gadgets, Innovations & Analysis | NMF News';
            $DisplayDescription = 'NMF News पर पाएं टेक्नोलॉजी से जुड़ी ताज़ा खबरें, गैजेट्स, इनोवेशन, और डिजिटल दुनिया की प्रमुख घटनाओं का विश्लेषण। स्मार्टफोन, AI, सॉफ़्टवेयर, और नई तकनीकों के बारे में जानें हर अपडेट।';
        } elseif (str_contains($URL, 'sports')) {
            $DisplayTitle = 'खेल समाचार: ताजा खेल अपडेट्स, मैच रिजल्ट्स, विश्लेषण और ब्रेकिंग न्यूज़ | NMF News';
            $DisplayDescription = 'NMF News पर पाएं खेल से जुड़ी ताज़ा खबरें, मैच के लाइव रिजल्ट्स, प्रमुख खेल आयोजनों का विश्लेषण और खिलाड़ियों की खबरें। क्रिकेट, फुटबॉल, बैडमिंटन और अन्य खेलों के बारे में सभी अपडेट यहाँ पाएँ।';
        } elseif (str_contains($URL, 'lifestyle')) {
            $DisplayTitle = 'लाइफस्टाइल समाचार: Health, Fashion, Wellness, Tips & Trends | NMF News';
            $DisplayDescription = 'NMF News पर पाएं लाइफस्टाइल से जुड़ी ताज़ा खबरें, स्वास्थ्य, फैशन, फिटनेस, और वेलनेस टिप्स। जीवनशैली के नए ट्रेंड्स, शॉपिंग गाइड और मानसिक स्वास्थ्य पर विशेषज्ञ सलाह के लिए जुड़े रहें।';
        } elseif (str_contains($URL, 'podcast')) {
            $DisplayTitle = 'पॉडकास्ट: Latest Episodes, Talks, Interviews & Discussions | NMF News';
            $DisplayDescription = 'NMF News के पॉडकास्ट सेक्शन में पाएं ताज़ा एपिसोड्स, दिलचस्प इंटरव्यूज़ और विशेषज्ञों से की गई चर्चा। राजनीति, समाज, बिजनेस, और अन्य विषयों पर गहन बातें सिर्फ NMF News पॉडकास्ट में।';
        } elseif (str_contains($URL, 'state/maharashtra')) {
            $DisplayTitle = 'महाराष्ट्र न्यूज़: Latest Maharashtra News, Breaking Headlines | NMF News';
            $DisplayDescription = 'महाराष्ट्र से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, समाज, उद्योग और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state/bihar')) {
            $DisplayTitle = 'बिहार न्यूज़: Latest Bihar News, Breaking Headlines | NMF News';
            $DisplayDescription = 'बिहार से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, अर्थव्यवस्था, संस्कृति और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state/uttrakhand')) {
            $DisplayTitle = 'उत्तराखंड न्यूज़: Latest Uttarakhand News, Breaking Headlines | NMF News';
            $DisplayDescription = 'उत्तराखंड से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, पर्यावरण, संस्कृति और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state/new-delhi')) {
            $DisplayTitle = 'नई दिल्ली न्यूज़: Latest New Delhi News, Breaking Headlines | NMF News';
            $DisplayDescription = 'नई दिल्ली से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, समाज, सरकार की योजनाएं और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state/jharkhand')) {
            $DisplayTitle = 'झारखंड न्यूज़: Latest Jharkhand News, Breaking Headlines | NMF News';
            $DisplayDescription = 'झारखंड से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, विकास, जनजीवन और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state/uttar-pradesh')) {
            $DisplayTitle = 'उत्तर प्रदेश न्यूज़: Latest Uttar Pradesh News, Breaking Headlines | NMF News';
            $DisplayDescription = 'उत्तर प्रदेश से जुड़ी ताज़ा खबरें, ब्रेकिंग न्यूज़ और इन-डेप्थ एनालिसिस यहां पढ़ें। राजनीति, समाज, संस्कृति और अन्य ख़बरों के लिए जुड़े रहें NMF News के साथ।';
        } elseif (str_contains($URL, 'state=maharashtra')) {
            $DisplayTitle = 'महाराष्ट्र विधान सभा चुनाव: Latest Updates, Results, and Analysis | NMF News';
            $DisplayDescription = 'महाराष्ट्र विधान सभा चुनाव से जुड़ी ताज़ा खबरें, चुनाव परिणाम, राजनीति और विश्लेषण पढ़ें। जानें महाराष्ट्र के विधानसभा चुनाव के बारे में सभी अपडेट और नतीजे NMF News पर।';
        } elseif (str_contains($URL, 'state=jharkhand')) {
            $DisplayTitle = 'झारखंड विधान सभा चुनाव: Latest Updates, Results, and Analysis | NMF News';
            $DisplayDescription = 'झारखंड विधान सभा चुनाव से जुड़ी ताज़ा खबरें, चुनाव परिणाम, और राजनीतिक विश्लेषण पढ़ें। झारखंड विधानसभा चुनाव के बारे में हर अपडेट और नतीजे पाएँ NMF News पर।';
        } elseif (str_contains($URL, 'state=new-delhi')) {
            $DisplayTitle = 'नई दिल्ली विधान सभा चुनाव: Latest Updates, Results, and Analysis | NMF News';
            $DisplayDescription = 'नई दिल्ली विधान सभा चुनाव से जुड़ी ताज़ा खबरें, चुनाव परिणाम और राजनीतिक विश्लेषण पढ़ें। नई दिल्ली विधानसभा चुनाव के नतीजे और सभी महत्वपूर्ण घटनाओं की जानकारी पाएं NMF News पर।';
        } elseif (str_contains($URL, 'state=bihar')) {
            $DisplayTitle = 'बिहार विधान सभा चुनाव: Latest Updates, Results, and Analysis | NMF News';
            $DisplayDescription = 'बिहार विधान सभा चुनाव से जुड़ी ताज़ा खबरें, चुनाव परिणाम और विश्लेषण पढ़ें। बिहार विधानसभा चुनाव के नतीजे और सभी प्रमुख घटनाओं की जानकारी पाएं NMF News पर।';
        } elseif ($URL == '/state-legislative-assembly-election') {
            $DisplayTitle = 'राज्य विधान सभा चुनाव 2024: ताजा अपडेट्स और चुनावी विश्लेषण | NMF न्यूज | newsnmf.com';
            $DisplayDescription = 'राज्य विधान सभा चुनाव 2024 से जुड़ी सभी एक्सक्लूसिव खबरें और ताजा अपडेट्स पाएं। जानें प्रमुख पार्टियों, उम्मीदवारों और चुनावी रणनीतियों का गहराई से विश्लेषण केवल NMF News – जिस पर देश करता है भरोसा। पर।';
        } elseif ($URL == '/breakingnews') {
            $todayEng=date('jS F Y');
            // Get current day and month in English
            $day = date('j');
            $month = date('n'); // Numeric month (1-12)
            $weekday = date('w'); // Numeric day of week (0 = Sunday, 6 = Saturday)

            // Hindi names for months and weekdays
            $hindiMonths = ['जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'];
            $hindiWeekdays = ['रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार'];

            // Compose the final string
            $formattedDate = $day . ' ' . $hindiMonths[$month - 1] . ', ' . $hindiWeekdays[$weekday];
            
            $DisplayTitle = "NMF न्यूज - एक क्लिक में पढ़ें ".$formattedDate. " की अहम खबरें - " . $todayEng ." breaking latest news";
            $DisplayDescription = "Stay updated with the latest breaking news for ".$formattedDate. " – covering top headlines, current affairs, and major developments as they unfold throughout the day.";
        } else {
            $DisplayTitle = 'NMF News - जिस पर देश करता है भरोसा | Trusted Global Affairs Insights';
            $DisplayDescription = 'NMF News – जिस पर देश करता है भरोसा। पर पाएं रक्षा, कूटनीति और वैश्विक संबंधों पर ताज़ा खबरें, विश्लेषण और विशेषज्ञ दृष्टिकोण।';
        }
        if ($statepos > 0) {
            $statename = substr($URL, $statepos + 7);
            $DisplayTitle = $DisplayTitle . '(' . $statename . ')';
        }
    
        if ($title == 'title') {
            echo $DisplayTitle;
        } elseif ($title == 'description') {
            echo $DisplayDescription;
        }
    }
    ?>
    <title><?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->name) ? $data['blog']->name : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('title');
    }
    ?></title>
    <meta name="description" content="<?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->sort_description) ? $data['blog']->sort_description : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('description');
    }
    
    ?>">
    <meta name="keywords"
        content="{{ isset($data['blog']->keyword) ? $data['blog']->keyword : (isset($setting->keyword) ? $setting->keyword : '') }}">
    <meta property="fb:app_id" content="3916260501994016" />
    <meta property="og:site_name" content="newsnmf" />
    <meta property="og:title" content="<?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->name) ? $data['blog']->name : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('title');
    }
    ?>" />
    <meta property="og:description" content="<?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->sort_description) ? $data['blog']->sort_description : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('description');
    }
    ?>" />
    <meta property="og:type" content="xxx:photo">
    <meta property="og:url" content="<?php
    if (isset($data['category']) && isset($data['blog'])) {
        echo asset('/') . $data['category']->site_url . '/' . $data['blog']->site_url;
    }
    ?>" />
    <meta property="og:image" content="<?php
    $ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
    echo asset($ff);
    ?>" />
    <meta property="og:image:type" content="image/jpeg">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="newsnmf">
    <meta name="twitter:url" content="<?php
    if (isset($data['category']) && isset($data['blog'])) {
        echo asset('/') . $data['category']->site_url . '/' . $data['blog']->site_url;
    }
    ?>" />
    <meta name="twitter:title" content="<?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->name) ? $data['blog']->name : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('title');
    }
    ?>" />
    <meta name="twitter:description" content="<?php
    if (isset($data['blog'])) {
        echo isset($data['blog']->sort_description) ? $data['blog']->sort_description : (isset($setting->site_name) ? $setting->site_name : '');
    } else {
        DisplayTitleDescription('description');
    }
    ?>" />
    <meta property="twitter:image:type" content="image/jpeg" />
    <meta property="twitter:image:width" content="660" />
    <meta property="twitter:image:height" content="367" />
    <meta name="twitter:image" content="<?php
    $ff = config('global.blog_images_everywhere')($data['blog'] ?? null);
    echo asset($ff);
    ?>" />

    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="robots" content="max-image-preview:large" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('asset/css/swiper-bundle.min.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" type="text/css" media="all" />
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async src="//www.instagram.com/embed.js"></script>

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('asset/plugins/bootstrap.min.css') }}" type="text/css" media="all" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@100..900&display=swap" rel="stylesheet">
    <script src="{{ asset('asset/plugins/bootstrap.min.js') }}"></script>



    <script type="text/javascript" src="{{ asset('/asset/js/jquery.min.js') }}" id="jquery-core-js"></script>


    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-9D3VCPPRWL');
    </script>

    <script type="text/javascript">
        /* <![CDATA[ */
        window._wpemojiSettings = {
            "baseUrl": "https:\/\/s.w.org\/images\/core\/emoji\/14.0.0\/72x72\/",
            "ext": ".png",
            "svgUrl": "https:\/\/s.w.org\/images\/core\/emoji\/14.0.0\/svg\/",
            "svgExt": ".svg",
            "source": {
                "concatemoji": "https:\/\/demo.themebeez.com\/demos-2\/cream-magazine-free\/wp-includes\/js\/wp-emoji-release.min.js?ver=6.4.4"
            }
        };
        /*! This file is auto-generated */
        ! function(i, n) {
            var o, s, e;

            function c(e) {
                try {
                    var t = {
                        supportTests: e,
                        timestamp: (new Date).valueOf()
                    };
                    sessionStorage.setItem(o, JSON.stringify(t))
                } catch (e) {}
            }

            function p(e, t, n) {
                e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(t, 0, 0);
                var t = new Uint32Array(e.getImageData(0, 0, e.canvas.width, e.canvas.height).data),
                    r = (e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(n, 0, 0), new Uint32Array(e
                        .getImageData(0, 0, e.canvas.width, e.canvas.height).data));
                return t.every(function(e, t) {
                    return e === r[t]
                })
            }

            function u(e, t, n) {
                switch (t) {
                    case "flag":
                        return n(e, "\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f", "\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f") ? !1 : !
                            n(e, "\ud83c\uddfa\ud83c\uddf3", "\ud83c\uddfa\u200b\ud83c\uddf3") && !n(e,
                                "\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f",
                                "\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f"
                            );
                    case "emoji":
                        return !n(e, "\ud83e\udef1\ud83c\udffb\u200d\ud83e\udef2\ud83c\udfff",
                            "\ud83e\udef1\ud83c\udffb\u200b\ud83e\udef2\ud83c\udfff")
                }
                return !1
            }

            function f(e, t, n) {
                var r = "undefined" != typeof WorkerGlobalScope && self instanceof WorkerGlobalScope ? new OffscreenCanvas(
                        300, 150) : i.createElement("canvas"),
                    a = r.getContext("2d", {
                        willReadFrequently: !0
                    }),
                    o = (a.textBaseline = "top", a.font = "600 32px Arial", {});
                return e.forEach(function(e) {
                    o[e] = t(a, e, n)
                }), o
            }

            function t(e) {
                var t = i.createElement("script");
                t.src = e, t.defer = !0, i.head.appendChild(t)
            }
            "undefined" != typeof Promise && (o = "wpEmojiSettingsSupports", s = ["flag", "emoji"], n.supports = {
                everything: !0,
                everythingExceptFlag: !0
            }, e = new Promise(function(e) {
                i.addEventListener("DOMContentLoaded", e, {
                    once: !0
                })
            }), new Promise(function(t) {
                var n = function() {
                    try {
                        var e = JSON.parse(sessionStorage.getItem(o));
                        if ("object" == typeof e && "number" == typeof e.timestamp && (new Date).valueOf() <
                            e.timestamp + 604800 && "object" == typeof e.supportTests) return e.supportTests
                    } catch (e) {}
                    return null
                }();
                if (!n) {
                    if ("undefined" != typeof Worker && "undefined" != typeof OffscreenCanvas && "undefined" !=
                        typeof URL && URL.createObjectURL && "undefined" != typeof Blob) try {
                        var e = "postMessage(" + f.toString() + "(" + [JSON.stringify(s), u.toString(), p
                                .toString()
                            ].join(",") + "));",
                            r = new Blob([e], {
                                type: "text/javascript"
                            }),
                            a = new Worker(URL.createObjectURL(r), {
                                name: "wpTestEmojiSupports"
                            });
                        return void(a.onmessage = function(e) {
                            c(n = e.data), a.terminate(), t(n)
                        })
                    } catch (e) {}
                    c(n = f(s, u, p))
                }
                t(n)
            }).then(function(e) {
                for (var t in e) n.supports[t] = e[t], n.supports.everything = n.supports.everything && n
                    .supports[t], "flag" !== t && (n.supports.everythingExceptFlag = n.supports
                        .everythingExceptFlag && n.supports[t]);
                n.supports.everythingExceptFlag = n.supports.everythingExceptFlag && !n.supports.flag, n
                    .DOMReady = !1, n.readyCallback = function() {
                        n.DOMReady = !0
                    }
            }).then(function() {
                return e
            }).then(function() {
                var e;
                n.supports.everything || (n.readyCallback(), (e = n.source || {}).concatemoji ? t(e
                    .concatemoji) : e.wpemoji && e.twemoji && (t(e.twemoji), t(e.wpemoji)))
            }))
        }((window, document), window._wpemojiSettings);
        /* ]]> */
    </script>



    <link rel="stylesheet" id="wp-block-library-css" href="{{ asset('/asset/style.min.css') }}" type="text/css"
        media="all" />
    <link rel="stylesheet" id="cream-magazine-fonts-css"
        href="https://fonts.googleapis.com/css2?family=Inter&#038;family=Poppins:ital,wght@0,600;1,600&#038;display=swap"
        type="text/css" media="all" />
    <link rel="stylesheet" id="fontAwesome-4-css" href="{{ asset('/asset/fonts/fontAwesome/fontAwesome.min.css') }}"
        type="text/css" media="all" />
    <link rel="stylesheet" id="feather-icons-css" href="{{ asset('/asset/fonts/feather/feather.min.css') }}"
        type="text/css" media="all" />
    <link rel="stylesheet" id="cream-magazine-main-css" href="{{ asset('/asset/css/main.css?v=1.13') }}"
        type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('/asset/css/header.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('/asset/css/footer.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('/asset/css/webstory.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('/asset/css/category.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('asset/css/swiper-bundle.min.css') }}" type="text/css" media="all" />


    <script type="text/javascript" src="{{ asset('/asset/js/jquery/jquery.min.js') }}" id="jquery-core-js"></script>
    <script type="text/javascript" src="{{ asset('/asset/js/jquery/jquery-migrate.min.js') }}" id="jquery-migrate-js">
    </script>
    <script type="text/javascript" src="{{ asset('asset/js/swiper-bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/asset/css/style.css') }}" type="text/css" media="all" />
    <style id="theia-sticky-sidebar-stylesheet-TSS">
        .theiaStickySidebar:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body data-rsssl="1"
    class="home page-template page-template-template-home page-template-template-home-php page page-id-362 wp-custom-logo wp-embed-responsive right-sidebar">

    <div class="page-wrapper">

        <header class="general-header cm-header-style-one">
            <nav class="top-header-nav">
                <div class="cm-container">
                    <div class="header-top-wrapper">
                        <?php 
                        
                          //$breakingNews = App\Models\Blog::where('breaking_status', '1')->orderBy('id', 'DESC')->limit(5)->get();
                          $trending_blog = App\Models\Blog::with('category')->where('status', '1')->where('breaking_status', '0')->where('sequence_id', '=', '0')->where('AppHitCount', '>', '0')->orderBy('AppHitCount', 'DESC')->limit(5)->get();
                        
                        ?>
                        <div class="breaking-news-wrapper">
                            <div class="breaking-news">
                                <span>ट्रेंडिंग न्यूज़</span>
                            </div>
                            <div class="slider-block">
                                <div class="breaking-news-slider">
                                    <div class="slide-track">
                                        @foreach ($trending_blog as $bblog)
                                            <div class="slide">
                                                @php
                                                    $cat = App\Models\Category::where(
                                                        'id',
                                                        $bblog->categories_ids,
                                                    )->first();
                                                    $blink = '';
                                                    $blogdesc = isset($bblog->description) ? $bblog->description : '';
                                                    $blogdesc = trim($blogdesc);

                                                    //if (strlen($blogdesc) > 0) {
                                                        $catname = isset($cat->site_url) ? $cat->site_url : '';
                                                        $blink = asset('/') . $catname . '/' . $bblog->site_url;
                                                   // }
                                                @endphp

                                                @if (strlen($blink) > 0)
                                                    <a href="{{ $blink }}"
                                                        class="slide-itm">{{ isset($bblog->name) ? $bblog->name : '' }}</a>
                                                @else
                                                    <a
                                                        class="slide-itm">{{ isset($bblog->name) ? $bblog->name : '' }}</a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="header-right">
                            {{-- <div class="login-wrap">
              <a href=""><i class="fa-regular fa-user" style="color: red;"></i> LOGIN</a>
            </div> --}}
                            <div class="social-wrap">
                                <a href="https://{{ isset($setting->facebook) ? $setting->facebook : '' }}"
                                    target="_blank" class="social-item"><span><i
                                            class="fa-brands fa-facebook-f"></i></span></a>
                                <a href="https://{{ isset($setting->twitterx) ? $setting->twitterx : '' }}"
                                    target="_blank" class="social-item"><span><i class="fa-brands fa-twitter"></i>
                                        </span"></a>
                                <a href="https://{{ isset($setting->instagram) ? $setting->instagram : '' }}"
                                    target="_blank" class="social-item"><span><i
                                            class="fa-brands fa-instagram"></i></span></a>
                                <a href="https://{{ isset($setting->youtube) ? $setting->youtube : '' }}"
                                    target="_blank" class="social-item"><span><i
                                            class="fa-brands fa-youtube"></i></span></a>
                                <a href="https://{{ isset($setting->whatsapp) ? $setting->whatsapp : '' }}"
                                    target="_blank" class="social-item"><span><i
                                            class="fa-brands fa-whatsapp"></i></span></a>
                            </div>
                        </div>
                    </div>

                </div>
            </nav>
            <div class="header-mid-wrapper">
                <div class="cm-container d-flex  align-items-center">
                    <div class="header-left-block  ">
                        <div class="act_buttons">
                            <div class="search_bx">
                                {{-- -Mobile Search- --}}
                                <form role="search" action="{{ asset('/search') }}" method="get"
                                    class="srch_input_box">
                                    <input type="search" name="search" class="srch_input" placeholder="Search..."
                                        pattern=".*\S.*" required>
                                    <button class="srch_btn" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </form>
                            </div>

                            <button class="toggle-box" id="toggle-btn">
                                {{-- <i class="fa-solid fa-bars"></i> --}}
                                <label class="burger" for="burger">
                                    {{-- <input type="checkbox" id="burger"> --}}
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </label>
                            </button>
                        </div>

                        <div class="date-box">
                            <i class="fa-regular fa-calendar-days"></i> <span><?php echo strtoupper(date('l d F Y')); ?></span>
                        </div>
                    </div>
                    <div class="header-center-block">
                        <a href="{{ asset('/') }}" style="display: inline-block;">
                            <img loading="lazy" src="{{ asset('asset/images/logo.png') }}" alt="">
                        </a>
                    </div>
                    <div class="header-right-block">
                        <div class="pod-cast">
                            <a href="{{ asset('/being-ghumakkad') }}"><img loading="lazy" style="width: 30px;"
                                    src="https://www.newsnmf.com/file/bg_icon.png" alt=""></a>
                            <a href="{{ asset('/Podcast') }}"><img loading="lazy" style="width: 54px;"
                                    src="https://www.newsnmf.com/file/podcost_icon.png" alt=""></a>
                        </div>
                        <div class="privacy-block">
                            <a href="{{ asset('/about') }}">About us</a>
                            <a href="{{ asset('/contact') }}">Contact us</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-overlay" id="modal-overlay">
                <!-- Modal Content -->
                <div class="modal-content">
                    <div class="modal_top">
                        <button class="close_btn" id="close-btn">
                            <i class="fa-solid fa-times"></i>
                        </button>
                        <a href="{{ asset('/') }}" class="modal_logo"><img loading="lazy"
                                src="/asset/images/logo.png" alt=""></a>
                        <a class="Headertag ms-0" style="margin-left: 0px"> <span class="">जिस पर
                                देश</span><span class="HeadertagHalf">करता है भरोसा</span> </a>
                    </div>

                    <?php
                    // Define category-to-icon mapping
                    $categoryIcons = [
                        'न्यूज' => 'fa-solid fa-newspaper',
                        'राज्य' => 'fa-solid fa-landmark',
                        'एक्सक्लूसिव' => 'fa-solid fa-star',
                        'खेल' => 'fa-solid fa-futbol',
                        'मनोरंजन' => 'fa-solid fa-film',
                        'धर्म ज्ञान' => 'fa-solid fa-om',
                        'टेक्नोलॉजी' => 'fa-solid fa-microchip',
                        'लाइफस्टाइल' => 'fa-solid fa-heart',
                        'पॉडकास्ट' => 'fa-solid fa-podcast',
                        'दुनिया' => 'fa-solid fa-globe',
                        'विधान सभा चुनाव' => 'fa-solid fa-vote-yea',
                    ];
                    //$toggleMenus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->get();
                    $toggleMenus = App\Models\Menu::where('menu_id', 0)->get();
                    ?>
                    <ul class="modalmenu">
                        @foreach ($toggleMenus as $menu)
                            @if (array_key_exists($menu->menu_name, $categoryIcons))
                                <li class="modal_item">
                                    <a href="{{ asset($menu->menu_link) }}">
                                        <i class="{{ $categoryIcons[$menu->menu_name] }}"></i> {{ $menu->menu_name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="nav-background">
                <div class="cm-container">
                    <nav class="main-navigation scrollMargin" id="myHeader">
                        <div id="" class=" inner-nav cm-container">
                            <ul class="menuSearch menuSearchalign-end navmenu">
                                <li id="navLogo"
                                    class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-400 showLogo">
                                    <a class="sub_logo" href="{{ asset('/') }}" aria-current="page">
                                        <img loading="lazy" src="{{ asset('asset/images/logo.png') }}"
                                            alt="" style="width: 41px">
                                    </a>
                                </li>

                                <?php
                                // $menus = App\Models\Menu::whereRelation('type', 'type', 'Header')
                                //     ->whereRelation('category', 'category', 'User')
                                //     ->where([['status', '1'], ['menu_id', 0]])
                                //     ->get()
                                //     ->toArray();


                              /*  $menus = App\Models\Menu::whereRelation('type', 'type', 'Header')
                                ->whereRelation('category', 'category', 'User')
                                ->where([['status', '1'], ['menu_id', 0]])
                               ->whereNotNull('sequence_id')
                               ->where('sequence_id', '!=', 0)
                               ->orderBy('sequence_id', 'asc')  
                               ->get()
                               ->toArray(); */


                                $menus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->get();
                                
                               /*  $duniyaIndex = array_search('दुनिया', array_column($menus, 'menu_name'));
                                $rajyaIndex = array_search('राज्य', array_column($menus, 'menu_name'));
                                $slaIndex = array_search('विधान सभा चुनाव 2024', array_column($menus, 'menu_name'));
                                
                                if ($duniyaIndex !== false && $rajyaIndex !== false && $slaIndex !== false) {
                                    $duniyaItem = $menus[$duniyaIndex];
                                    $slaItem = $menus[$slaIndex];
                                    unset($menus[$duniyaIndex], $menus[$slaIndex]);
                                
                                    array_splice($menus, $rajyaIndex + 1, 0, [$duniyaItem, $slaItem]);
                                } */
                                
                                ?>
                                @foreach ($menus as $menu)
                                    <?php
                                    // if ($menu['menu_name'] === 'PODCAST') {
                                    //     $menu['menu_name'] = 'पॉडकास्ट';
                                    // }
                                    $subMenus = App\Models\Menu::where('menu_id', $menu['id'])->where('status', '1')->where('type_id', '1')->where('category_id', '2')->get();
                                    $file = App\Models\File::where('id', $menu['image'])->first();
                                    ?>
                                    <li class="item">
                                        <a href="{{ asset($menu['menu_link']) }}" class="link">
                                            <span> {{ $menu['menu_name'] }}</span>
                                            @if (count($subMenus) > 0)
                                                <svg viewBox="0 0 360 360" xml:space="preserve">
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path id="XMLID_225_"
                                                            d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393 c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393 s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z">
                                                        </path>
                                                    </g>
                                                </svg>
                                            @endif
                                        </a>

                                        @if (count($subMenus) > 0)
                                            <div class="submenu">
                                                @foreach ($subMenus as $subMenu)
                                                    <div class="submenu-item">
                                                        <a href="{{ asset($subMenu['menu_link']) }}"
                                                            class="submenu-link"> {{ $subMenu['menu_name'] }} </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            {{-- -Web Search- --}}
                            <form role="search" action="{{ asset('/search') }}" method="get" class="search_bar">
                                <input type="search" name="search" pattern=".*\S.*" required>
                                <button class="search_btn" type="submit">
                                    <span>Search</span>
                                </button>
                            </form>
                    </nav>
                </div>
            </div>
    </div>
    </header>

    <div id="content" class="site-content">
        @yield('content')
    </div>

    <footer class="footer_main">
        <div class="cm-container">
            <div class="footer-top">
                <div class="footer_left">
                    <div class="footer_logo_wrap">
                        <a href="{{ asset('/') }}" class="footer_logo">
                            <img loading="lazy" src="{{ asset('asset/images/logo.png') }}" alt="" />
                        </a>
                        <div class="footer_logo">
                            <img loading="lazy" src="https://www.newsnmf.com/banner/logo.png" alt="">
                        </div>
                    </div>
                    <p>NMF News is a Subsidary of Khetan Media Creation Pvt Ltd</p>
                    <div class="contact_wrap">
                        <div class="contact_block">
                            <div class="ct_left">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="ct_right">
                                <small>Give us a Call</small>
                                <a href="tel:+91-080767 27261">+91-080767 27261</a>
                            </div>
                        </div>
                        <div class="contact_block">
                            <div class="ct_left">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="ct_right">
                                <small>Visit Our Office</small>
                                <p>D-4 1st Floor, Sector 10, Noida, Uttar Pradesh 201301</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer_centre">
                    <div class="footer_col">
                        <h4>Company</h4>
                        <ul class="footer_menu">

                            <li class="footer_item"><a href="{{ asset('/about') }}">About us</a></li>
                            <li class="footer_item"><a href="{{ asset('/privacy') }}">Privacy Policy</a></li>
                            <li class="footer_item"><a href="{{ asset('/disclaimer') }}">Disclaimer</a></li>
                            <li class="footer_item"><a href="{{ asset('/contact') }}">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer_col">
                        <h4>Category</h4>
                        <?php
                        $footer_menus = App\Models\Menu::where('menu_id', 0)->where('status', 1)->where('type_id', '1')->where('category_id', '2')->limit(8)->get();
                        $chunks = $footer_menus->chunk(4);
                        ?>
                        <ul class="footer_menu">
                            @foreach ($chunks as $chunk)
                                <div class="footer_ct">
                                    @foreach ($chunk as $footer_menu)
                                        <li class="footer_item">
                                            <a href="{{ $footer_menu->menu_link }}">{{ $footer_menu->menu_name }}</a>
                                        </li>
                                    @endforeach
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="footer_right">
                    <h5>Download Our NMF News App</h5>
                    <div class="app_btn_wrap">

                        <a href="https://play.google.com/store/apps/details?id=com.kmcliv.nmfnews"
                            class="playstore-button">
                            <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M99.617 8.057a50.191 50.191 0 00-38.815-6.713l230.932 230.933 74.846-74.846L99.617 8.057zM32.139 20.116c-6.441 8.563-10.148 19.077-10.148 30.199v411.358c0 11.123 3.708 21.636 10.148 30.199l235.877-235.877L32.139 20.116zM464.261 212.087l-67.266-37.637-81.544 81.544 81.548 81.548 67.273-37.64c16.117-9.03 25.738-25.442 25.738-43.908s-9.621-34.877-25.749-43.907zM291.733 279.711L60.815 510.629c3.786.891 7.639 1.371 11.492 1.371a50.275 50.275 0 0027.31-8.07l266.965-149.372-74.849-74.847z">
                                </path>
                            </svg>
                            <span class="texts">
                                <span class="text-1">GET IT ON</span>
                                <span class="text-2">Google Play</span>
                            </span>
                        </a>
                        <a href="#" class="playstore-button">
                            <svg viewBox="0 0 512 512" class="_icon" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg" style="margin-right: -7px;">
                                <path
                                    d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z" />
                            </svg>
                            <span class="texts">
                                <span class="text-1">GET IT ON</span>
                                <span class="text-2">App Store</span>
                            </span>
                        </a>

                    </div>
                    @if (session('subscribemessage'))
                                        <div class="alert alert-success mt-3">
                                            {{ session('subscribemessage') }}
                                        </div>
                                    @endif
                                    

                    <form method="POST" action="{{ url('/') }}">
                        @csrf
                        <input type="hidden" name="_action" value="subscribe">
                        <div class="nsl-block">
                            <p>Subscribe to our newsletter</p>
                            <div class="input_wrap">
                                <input placeholder="Your email address" class="signup_input" name="email" type="email" required>
                                <button class="Subscribe-btn">Subscribe</button>
                            </div>
                        </div>
                    </form>
                    <div class="poweredby">
                        <span>Powered by</span>
                       <a href="https://www.abrosys.com/"> <img src="{{ asset('asset/images/abrosys.png') }}" alt=""></a>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer-bottom">

            <div class="cm-container row">

                <div class="col-md-4">
                    <div class="footer-site-info">Copyright © 2025 KMC PVT. LTD. All Rights Reserved.</div>
                </div>



                <div class="ftcol">
                    <div id="footer-socials">
                        <p>Follow us</p>
                        <div class="socials inline-inside socials-colored">
                            <a href="https://{{ isset($setting->facebook) ? $setting->facebook : '' }}"
                                target="_blank" title="Facebook" class="socials-item">
                                <i class="fab fa-facebook-f facebook"></i>
                            </a>
                            <a href="https://{{ isset($setting->twitterx) ? $setting->twitterx : '' }}"
                                target="_blank" title="Twitter" class="socials-item">
                                <i class="fab fa-twitter twitter"></i>
                            </a>
                            <a href="https://{{ isset($setting->instagram) ? $setting->instagram : '' }}"
                                target="_blank" title="Instagram" class="socials-item">
                                <i class="fab fa-instagram instagram"></i>
                            </a>
                            <a href="https://{{ isset($setting->youtube) ? $setting->youtube : '' }}" 
                                target="_blank" title="YouTube" class="socials-item">
                                <i class="fab fa-youtube youtube"></i>
                            </a>
                            <a href="https://{{ isset($setting->whatsapp) ? $setting->whatsapp : '' }}"
                                target="_blank" title="WhatsApp" class="socials-item">
                                <i class="fab fa-whatsapp whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </footer>
    <div class="backtoptop">
        <button id="toTop" class="btn btn-info">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </button>
    </div>
    <script type="text/javascript" id="cream-magazine-bundle-js-extra">
        /* <![CDATA[ */
        var cream_magazine_script_obj = {
            "show_search_icon": "1",
            "show_news_ticker": "1",
            "show_banner_slider": "1",
            "show_to_top_btn": "1",
            "enable_sticky_sidebar": "1",
            "enable_sticky_menu_section": ""
        };
        /* ]]> */
    </script>
    <script type="text/javascript" src="{{ asset('/asset/js/bundle.min.js') }}" id="cream-magazine-bundle-js"></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v55bfa2fee65d44688e90c00735ed189a1713218998793"
        integrity="sha512-FIKRFRxgD20moAo96hkZQy/5QojZDAbyx0mQ17jEGHCJc/vi0G2HXLtofwD7Q3NmivvP9at5EVgbRqOaOQb+Rg=="
        data-cf-beacon='{"rayId":"877e2b567a269fa5","r":1,"version":"2024.3.0","token":"e07ffd4cc02748408b326adb64b6cc16"}'
        crossorigin="anonymous"></script>
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <!-- newsnmf.com -->
    <!--script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3986924419662120"
        crossorigin="anonymous"></script-->
    <!-- nmfnewsonline.com -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3986924419662120"
        crossorigin="anonymous"></script>
</body>

</html>
