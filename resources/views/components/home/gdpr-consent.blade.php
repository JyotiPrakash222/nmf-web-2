<style>
    /* Cookie Banner Container */
    .cookie-banner {
        display: none;
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #ffffffde;
        color: #fff;
        padding: 6px 20px;
        font-size: 15px;
        z-index: 1000;
        box-shadow: 0 -1px 6px rgb(0 0 0 / 15%);
        box-sizing: border-box;
        justify-content: center;
        align-items: center;
    }

    /* Inner Content Container */
    .cookie-content {
        max-width: 760px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    /* Text */
    .cookie-text {
        flex: 1;
        min-width: 250px;
        color: #666;
    }

    /* Button Container */
    .cookie-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Buttons */
    .cookie-btn {
        padding: 5px 21px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s ease;
        color: #fff;
    }

    .cookie-btn.accept {
        background-color: #212121;
    }

    .cookie-btn.accept:hover {
        background-color: #3c3c3c;
    }

    .cookie-btn.reject {
        background-color: #e41e31;
    }

    .cookie-btn.reject:hover {
        background-color: #bb1c2c;
    }

    @media (max-width: 600px) {
        .cookie-content {
            max-width: 760px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
            padding-bottom: 6px;
        }
    }
</style>
<div id="cookie-banner" class="cookie-banner">
    <div class="cookie-content">
        <div class="cookie-text">
            We use cookies to enhance your experience. You can accept or reject tracking.
        </div>
        <div class="cookie-buttons">
            <button class="cookie-btn accept" onclick="acceptCookies()">Accept</button>
            <button class="cookie-btn reject" onclick="rejectCookies()">Reject</button>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const consent = localStorage.getItem('cookie_consent');
        const banner = document.getElementById('cookie-banner');

        if (!consent) {
            if (banner) banner.style.display = 'flex';
        } else {
            // Push consent settings to dataLayer for GTM
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                event: 'default_consent_update',
                'ad_storage': consent === 'accepted' ? 'granted' : 'denied',
                'analytics_storage': consent === 'accepted' ? 'granted' : 'denied',
                'functionality_storage': consent === 'accepted' ? 'granted' : 'denied',
                'personalization_storage': consent === 'accepted' ? 'granted' : 'denied'
            });
        }
    });

 function acceptCookies() {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'default_consent_update',
            'ad_storage': 'granted',
            'analytics_storage': 'granted',
            'functionality_storage': 'granted',
            'personalization_storage': 'granted'
        });
        localStorage.setItem('cookie_consent', 'accepted');
        hideBanner();
    }


    function rejectCookies() {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'default_consent_update',
            'ad_storage': 'denied',
            'analytics_storage': 'denied',
            'functionality_storage': 'denied',
            'personalization_storage': 'denied'
        });
        localStorage.setItem('cookie_consent', 'rejected');
        hideBanner();
    }

    function hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) banner.style.display = 'none';
    }
</script>
