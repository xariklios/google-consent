<?php

/*
* Plugin Name:       Google Consent Mode V2
* Plugin URI:        https://vforvaltzis.dev
* Description:       Handle the basics of google consent v2 GDPR
* Version:           1.0.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Charis Valtzis 
* Author URI:        https://vforvaltzis.dev
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Update URI:        https://example.com/my-plugin/
* Text Domain:       google-consent-v2
* Domain Path:       /languages
*/

add_action('wp_enqueue_scripts', function () {

    $my_js_ver  = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'google-consent-mode.js'));
    $my_css_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'google-consent-mode.css'));


    wp_enqueue_script('custom_js', plugins_url('google-consent-mode.js', __FILE__), array(), $my_js_ver);
    wp_register_style('my_css',     plugins_url('google-consent-mode.css',      __FILE__), false,   $my_css_ver);
    wp_enqueue_style('my_css');
});

add_action('wp_head', 'gcm_load_consent_layer', 0);

function gcm_load_consent_layer()
{
    echo "gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied'
      });";
}

add_action('wp_footer', 'gcm_banner');

function gcm_banner()
{
?>

    <div id="cookie-banner" class="cookie-banner">
        <p>We use cookies to enhance your experience. Please accept or decline the use of cookies.</p>
        <div class="buttons">
            <button id="accept-btn">Accept</button>
            <button id="decline-btn">Decline</button>
            <button id="settings-btn">Settings</button>
        </div>
        <div id="settings-panel" class="settings-panel">
            <h3>Cookie Settings</h3>
            <label class="toggle-switch">
                <input type="checkbox" id="analytics-toggle" checked>
                <span class="slider"></span>
                Analytics
            </label>
            <label class="toggle-switch">
                <input type="checkbox" id="marketing-toggle" checked>
                <span class="slider"></span>
                Marketing
            </label>
            <label class="toggle-switch">
                <input type="checkbox" id="preferences-toggle" checked>
                <span class="slider"></span>
                Preferences
            </label>
        </div>
    </div>
<?php
}
