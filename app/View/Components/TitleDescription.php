<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TitleDescription extends Component
{
    public $type; // 'title' or 'description'

    public function __construct($type = 'title')
    {
        $this->type = $type;
    }

    /**
     * Compute the display value based on current URL and type
     */
    public function display()
    {
        //$URL = url()->current();
        $URL = url()->full();
        $statepos = strrpos($URL, '?');
        $statename = '';
        $DisplayTitle = '';
        $DisplayDescription = '';
         // --- Copy all your conditions here ---
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
             $DisplayTitle = 'Bihar Vidhan Sabha Chunav 2025 Schedule – Voting Dates, Phases & Counting Details | बिहार विधानसभा चुनाव शेड्यूल';
             $DisplayDescription = 'जानें Bihar Election 2025 की पूरी जानकारी – Phase-wise voting dates, counting date और latest updates यहां देखें। Stay updated with Bihar Vidhan Sabha Chunav 2025 schedule and news.';
        } elseif ($URL == '/state-legislative-assembly-election') {
            $DisplayTitle = 'Bihar Vidhan Sabha Chunav 2025 Schedule – Voting Dates, Phases & Counting Details | बिहार विधानसभा चुनाव शेड्यूल';
            $DisplayDescription = 'जानें Bihar Election 2025 की पूरी जानकारी – Phase-wise voting dates, counting date और latest updates यहां देखें। Stay updated with Bihar Vidhan Sabha Chunav 2025 schedule and news.';
        } elseif (str_contains($URL, 'breakingnews')) {
            $todayEng = date('jS F Y');
            // Get current day and month in English
            $day = date('j');
            $month = date('n'); // Numeric month (1-12)
            $weekday = date('w'); // Numeric day of week (0 = Sunday, 6 = Saturday)
    
            // Hindi names for months and weekdays
            $hindiMonths = ['जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर'];
            $hindiWeekdays = ['रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार'];
    
            // Compose the final string
            $formattedDate = $day . ' ' . $hindiMonths[$month - 1] . ', ' . $hindiWeekdays[$weekday];
    
            $DisplayTitle = 'NMF न्यूज - एक क्लिक में पढ़ें ' . $formattedDate . ' की अहम खबरें - ' . $todayEng . ' breaking latest news';
            $DisplayDescription = 'Stay updated with the latest breaking news for ' . $formattedDate . ' – covering top headlines, current affairs, and major developments as they unfold throughout the day.';
        } elseif (str_contains($URL,'bihar-election-2025-phase-1') ){
            $DisplayTitle = 'Bihar Election 2025 Phase 1 – Polling Date, Key Areas & Latest Updates | बिहार चुनाव पहला चरण';
            $DisplayDescription = 'Bihar Chunav 2025 Phase 1 की पूरी जानकारी – मतदान की तारीख, प्रमुख क्षेत्रों की जानकारी और लेटेस्ट updates यहां देखें। Follow all news from Bihar Election 2025 Phase 1.';
        }elseif (str_contains($URL,'bihar-election-2025-phase-2')) {
            $DisplayTitle = 'Bihar Election 2025 Phase 2 – Voting Schedule, Important Seats & Updates | बिहार चुनाव दूसरा चरण';
            $DisplayDescription = 'जानें Bihar Vidhan Sabha Election 2025 Phase 2 की details – polling schedule, important areas और ताजा चुनावी अपडेट यहां पढ़ें। Stay tuned for Bihar Election 2025 news.';
        }elseif (str_contains($URL,'web-stories')) {
            $DisplayTitle = 'Web Stories – Trending Short News, Entertainment & Lifestyle Updates | NMF News';
            $DisplayDescription = 'Explore engaging Web Stories from NMF News — quick, visual updates on trending topics, politics, entertainment, lifestyle, and more. Stay informed with short, immersive stories designed for mobile readers.';
        } 
        
        else {
            $DisplayTitle = 'Hindi News, हिंदी समाचार, Latest News in Hindi, Breaking News, ताज़ा खबरें - NewsNMF';
            $DisplayDescription = 'NewsNMF: हिंदी समाचार (Hindi News) website. पढ़ें ताज़ा खबरें, ब्रेकिंग न्यूज़, राजनीति, खेल, बॉलीवुड, व्यापार और देश-दुनिया की हिंदी खबरें सबसे पहले। Latest Hindi News, Hindi Samachar, Breaking News in Hindi.';
        }
        // ... (add all other elseif conditions from your function)

       

        if ($statepos > 0) {
            $statename = substr($URL, $statepos + 7);
            $DisplayTitle = $DisplayTitle . '(' . $statename . ')';
        }

        return $this->type === 'title' ? $DisplayTitle : $DisplayDescription;
    }

    public function render()
        {
            return view('components.title-description', [
                'display' => $this->display(), // call the display() method and pass result
            ]);
        }

}
