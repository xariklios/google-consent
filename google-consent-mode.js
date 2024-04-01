document.addEventListener('DOMContentLoaded', function () {
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }

    gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied'
    });

    // Select DOM elements
    const settingsBtn = document.getElementById('settings-btn');
    const settingsPanel = document.getElementById('settings-panel');
    const acceptBtn = document.getElementById('accept-btn');
    const declineBtn = document.getElementById('decline-btn');
    const saveSettingsBtn = document.getElementById('save-settings-btn');
    const gmcBanner = document.getElementById('gmc_cookie_banner');

    // Event listeners
    settingsBtn.addEventListener('click', function () {
        settingsPanel.style.display = 'block';
    });

    acceptBtn.addEventListener('click', function () {
        // Grant consent for all features
        grantAllConsents();
        gmcBanner.style.display = 'none';
        // Set cookie to indicate user accepted
        setCookie("gmc_user_consent", 'user_consent', 30); // Expires in 30 days
    });

    declineBtn.addEventListener('click', function () {
        // Deny consent for all features
        denyAllConsents();
        gmcBanner.style.display = 'none';

        // Set cookie to indicate user declined
        setCookie("gmc_user_consent", 'user_consent', 30); // Expires in 30 days
    });

    saveSettingsBtn.addEventListener('click', function () {
        // Read user's individual consent preferences
        const userConsent = document.getElementById('user_toggle').checked;
        const trackingConsent = document.getElementById('tracking_toggle').checked;
        const marketingConsent = document.getElementById('marketing_toggle').checked;

        // Update consent based on user's choices
        if (userConsent) {
            consentGranted('ad_storage', true);
            consentGranted('ad_personalization', true);
        } else {
            consentGranted('ad_storage', false);
            consentGranted('ad_personalization', false);
        }

        if (trackingConsent) {
            consentGranted('ad_user_data', true);
        } else {
            consentGranted('ad_user_data', false);
        }

        if (marketingConsent) {
            consentGranted('analytics_storage', true);
        } else {
            consentGranted('analytics_storage', false);
        }
        setCookie("gmc_user_consent", 'user_consent', 30); // Expires in 30 days

        gmcBanner.style.display = 'none';

    });

    // Function to grant consent for all features
    function grantAllConsents() {
        consentGranted('ad_storage', true);
        consentGranted('ad_personalization', true);
        consentGranted('ad_user_data', true);
        consentGranted('analytics_storage', true);
    }

    // Function to deny consent for all features
    function denyAllConsents() {
        consentGranted('ad_storage', false);
        consentGranted('ad_personalization', false);
        consentGranted('ad_user_data', false);
        consentGranted('analytics_storage', false);
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
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
});
