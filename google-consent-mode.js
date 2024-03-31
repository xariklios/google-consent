document.addEventListener('DOMContentLoaded', function () {
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    const settingsBtn = document.getElementById('settings-btn');
    const settingsPanel = document.getElementById('settings-panel');
    const acceptBtn = document.getElementById('accept-btn');
    const declineBtn = document.getElementById('decline-btn');

    settingsBtn.addEventListener('click', function () {
        settingsPanel.style.display = 'block';
    });

    acceptBtn.addEventListener('click', function () {
        consentGranted('ad_storage', true);
        consentGranted('ad_personalization', true);
        consentGranted('ad_user_data', true);
        consentGranted('analytics_storage', true);
    });

    declineBtn.addEventListener('click', function () {
        consentGranted('ad_storage', false);
        consentGranted('ad_personalization', false);
        consentGranted('ad_user_data', false);
        consentGranted('analytics_storage', false);
    });

    function consentGranted(feature, consent) {
        const auth = consent ? 'granted' : 'denied';
        gtag('consent', 'update', {
            [feature]: auth
        });
    }

    const toggleSwitches = document.querySelectorAll('.toggle-switch input');
    toggleSwitches.forEach(function (switchInput) {
        switchInput.addEventListener('change', function () {
            if (this.checked) {
                // Run function for enabled toggle
                console.log('Toggle enabled:', this.id);
            } else {
                // Run function for disabled toggle
                console.log('Toggle disabled:', this.id);
            }
        });
    });
});
