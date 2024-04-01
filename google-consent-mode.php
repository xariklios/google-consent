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

add_action('wp_footer', 'gcm_banner');

function gcm_banner()
{

    if (!current_user_can("administrator")) return;
    $showBanner = !isset($_COOKIE['gmc_user_consent']);

    if (!$showBanner) return;
?>

    <div id="gmc_cookie_banner" class="cookie-banner">
        <h3>Αυτή η ιστοσελίδα χρησιμοποιεί cookies</h3>
        <p>Χρησιμοποιούμε cookies και άλλες τεχνολογίες εντοπισμού για την βελτίωση της εμπειρίας περιήγησης στην ιστοσελίδα μας, για την εξατομίκευση περιεχομένου και διαφημίσεων, την παροχή λειτουργιών κοινωνικών μέσων και την ανάλυση της επισκεψιμότητάς μας.</p>
        <div class="buttons">
            <button id="accept-btn">Συμφωνώ</button>
            <button id="decline-btn">Αρνούμαι</button>
            <button id="settings-btn">Προτιμήσεις</button>
        </div>
        <div id="settings-panel" class="settings-panel">
            <h3>Ρυθμίσεις προτιμήσεων</h3>
            <label class="toggle-switch">
                <input type="checkbox" id="essential_cookies" checked disabled>
                <span class="slider"></span>
                Απαραίτητα Cookies
            </label>
            <label class="toggle-switch">
                <input type="checkbox" id="user_toggle" checked>
                <span class="slider"></span>
                Cookies Λειτουργικότητας
            </label>
            <label class="toggle-switch">
                <input type="checkbox" id="tracking_toggle" checked>
                <span class="slider"></span>
                Cookies Εντοπισμού
            </label>
            <label class="toggle-switch">
                <input type="checkbox" id="marketing_toggle" checked>
                <span class="slider"></span>
                Cookies Εξατομικευμένου περιεχομένου και διαφημίσεων
            </label>
            <button id="save-settings-btn">Αποθήκευση</button>
        </div>
    </div>
<?php
}
