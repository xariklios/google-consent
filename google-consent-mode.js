document.addEventListener('DOMContentLoaded', function () {
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }

    const consentCookie = getCookie("gmc_user_consent");

    console.log(consentCookie);

    if (consentCookie) {
        const consentValues = isJsonString(consentCookie);

        if (consentValues){
            console.log('entered');
            Object.entries(consentValues).forEach(([feature, consent]) => {
                consentGranted(feature, consent === 'granted');
            });
        }else{
            console.log('delete')
            document.cookie = "gmc_user_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=None; Secure";
        }

    }else{
        gtag('consent', 'default', {
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'ad_storage': 'denied',
            'analytics_storage': 'denied',
            'functionality_storage': 'denied',
            'personalization_storage': 'denied',
            'security_storage': 'granted'
        });
    }

    // Select DOM elements
    const settingsBtn = document.getElementById('settings-btn');
    const settingsPanel = document.getElementById('settings-panel');
    const acceptBtn = document.getElementById('accept-btn');
    const declineBtn = document.getElementById('decline-btn');
    const saveSettingsBtn = document.getElementById('save-settings-btn');
    const gmcBanner = document.getElementById('gmc_cookie_banner');
    const closeButton = document.querySelector('.gcm_close_banner');

    // Event listeners
    if (settingsBtn){
        settingsBtn.addEventListener('click', function () {
            settingsPanel.classList.toggle('show')
        });
    }

    if (closeButton){
        closeButton.addEventListener('click', function(){
            gmcBanner.style.display = 'none';
        });
    }


    if (acceptBtn){
        acceptBtn.addEventListener('click', function () {
            // Grant consent for all features
            grantAllConsents();
            gmcBanner.style.display = 'none';
            // Set cookie to indicate user accepted
            setCookie(
                "gmc_user_consent",
                JSON.stringify(
                    {
                        'ad_user_data': 'true',
                        'ad_personalization': 'true',
                        'ad_storage': 'true',
                        'analytics_storage': 'true',
                        'functionality_storage': 'true',
                        'personalization_storage': 'true',
                        'security_storage': 'granted'
                    }),
                30
            ); // Expires in 30 days
        });
    }


    if (declineBtn){
        declineBtn.addEventListener('click', function () {
            // Deny consent for all features
            denyAllConsents();
            gmcBanner.style.display = 'none';

            // Set cookie to indicate user declined
            setCookie(
                "gmc_user_consent",
                JSON.stringify(
                    {
                        'ad_user_data': 'false',
                        'ad_personalization': 'false',
                        'ad_storage': 'false',
                        'analytics_storage': 'false',
                        'functionality_storage': 'false',
                        'personalization_storage': 'false',
                        'security_storage': 'granted'
                    }),
                30
            );
        });
    }

    if (saveSettingsBtn) {
        saveSettingsBtn.addEventListener('click', function () {
            const functionalConsent = document.getElementById('gcm_functional_cookies');
            const trackingConsent = document.getElementById('gcm_tracking_toggle');
            const marketingConsent = document.getElementById('gcm_marketing_toggle');
            if (functionalConsent && trackingConsent && marketingConsent) {
                consentGranted('functionality_storage', functionalConsent.checked);
                consentGranted('personalization_storage', functionalConsent.checked);
                consentGranted('analytics_storage', trackingConsent.checked);
                consentGranted('ad_user_data', marketingConsent.checked);
                consentGranted('ad_personalization', marketingConsent.checked);
                consentGranted('ad_storage', marketingConsent.checked);

                setCookie("gmc_user_consent", JSON.stringify({
                    'ad_user_data': marketingConsent.checked ? 'granted' : 'denied',
                    'ad_personalization': marketingConsent.checked ? 'granted' : 'denied',
                    'ad_storage': marketingConsent.checked ? 'granted' : 'denied',
                    'analytics_storage': trackingConsent.checked ? 'granted' : 'denied',
                    'functionality_storage': functionalConsent.checked ? 'granted' : 'denied',
                    'personalization_storage': functionalConsent.checked ? 'granted' : 'denied',
                    'security_storage': 'granted'
                }), 30); // Expires in 30 days
                if (gmcBanner) {
                    gmcBanner.style.display = 'none';
                }
            }
        });
    }

    // Function to grant consent for all features
    function grantAllConsents() {
        consentGranted('ad_user_data', true);
        consentGranted('ad_personalization', true);
        consentGranted('ad_storage', true);
        consentGranted('analytics_storage', true);
        consentGranted('functionality_storage', true);
        consentGranted('personalization_storage', true);
        consentGranted('security_storage', true);
    }

    // Function to deny consent for all features
    function denyAllConsents() {
        consentGranted('ad_user_data', false);
        consentGranted('ad_personalization', false);
        consentGranted('ad_storage', false);
        consentGranted('analytics_storage', false);
        consentGranted('functionality_storage', false);
        consentGranted('personalization_storage', false);
        consentGranted('security_storage', false);
    }

    // Function to update consent
    function consentGranted(feature, consent) {
        const auth = consent ? 'granted' : 'denied';
        gtag('consent', 'update', {
            [feature]: auth
        });
    }

    // Function to set a cookie with an expiration date 30 days from now
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=None; Secure";

    }

    // Function to retrieve a cookie by name
    function getCookie(name) {
        const cookieArr = document.cookie.split("; "); //get all cookies
        for (let i = 0; i < cookieArr.length; i++) {
            const cookiePair = cookieArr[i].split("=");
            if (name === cookiePair[0]) {
                return decodeURIComponent(cookiePair[1]);
            }
        }
        return null;
    }

    function isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
});
