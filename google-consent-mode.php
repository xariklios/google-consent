<?php

/*
* Plugin Name:       Google Consent Mode V2
* Plugin URI:        https://vforvaltzis.dev
* Description:       Handle the basics of google consent v2 GDPR
* Version:           1.0.1
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

    $my_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'google-consent-mode.js'));
    $my_css_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'google-consent-mode.css'));


    wp_enqueue_script('custom_js', plugins_url('google-consent-mode.js', __FILE__), array(), $my_js_ver);
    wp_register_style('my_css', plugins_url('google-consent-mode.css', __FILE__), false, $my_css_ver);
    wp_enqueue_style('my_css');
});

add_action('wp_footer', 'gcm_banner',99);
add_action('admin_menu', 'gcm_cookie_consent_banner_settings_page');

function gcm_cookie_consent_banner_settings_page()
{
    add_menu_page(
        'Cookie Consent Banner Settings',
        'Cookie Consent Banner',
        'manage_options',
        'cookie-consent-banner-settings',
        'gcm_render_cookie_consent_banner_settings_page'
    );
}

function gcm_render_cookie_consent_banner_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Cookie Consent Banner Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('gcm_cookie_consent_banner_settings_group'); ?>
            <?php do_settings_sections('gcm_cookie_consent_banner_settings_page'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function gcm_cookie_consent_banner_settings_fields()
{
    add_settings_section(
        'gcm_cookie_consent_banner_settings_section',
        'Cookie Consent Banner Settings',
        '__return_empty_string',
        'gcm_cookie_consent_banner_settings_page'
    );

    // Title
    add_settings_field(
        'gcm_cookie_consent_banner_title',
        'Title',
        'gcm_render_cookie_consent_banner_title_field',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // Description
    add_settings_field(
        'gcm_cookie_consent_banner_description',
        'Description',
        'gcm_render_cookie_consent_banner_description_field',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // Color Input
    add_settings_field(
        'gcm_cookie_consent_banner_background_color',
        'Banner Background Color',
        'gcm_render_cookie_consent_banner_background_color',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    add_settings_field(
        'gcm_cookie_consent_banner_text_color',
        'Banner Text Color',
        'gcm_render_cookie_consent_banner_text_color',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );


    // Color Input
    add_settings_field(
        'gcm_cookie_consent_banner_btns_background_color',
        'Buttons Background Color',
        'gcm_render_cookie_consent_banner_btns_background_color',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // Color Input
    add_settings_field(
        'gcm_cookie_consent_banner_btns_text_color',
        'Buttons Text Color',
        'gcm_render_cookie_consent_banner_btns_text_color',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // show disagree btn
    add_settings_field(
        'gcm_cookie_consent_banner_show_disagree',
        'Show Decline Button',
        'gcm_render_cookie_consent_banner_show_disagree_btn',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // show close btn
    add_settings_field(
        'gcm_cookie_consent_banner_show_close',
        'Show Close Button',
        'gcm_render_cookie_consent_banner_show_close_btn',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // Banner position
    add_settings_field(
        'gcm_cookie_consent_banner_position',
        'Banner Position',
        'gcm_render_cookie_consent_banner_position_field',
        'gcm_cookie_consent_banner_settings_page',
        'gcm_cookie_consent_banner_settings_section'
    );

    // Register settings
    register_setting('gcm_cookie_consent_banner_settings_group', 'gcm_cookie_consent_banner_settings');
}

add_action('admin_init', 'gcm_cookie_consent_banner_settings_fields');

// Render Title Field
function gcm_render_cookie_consent_banner_title_field()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $value = isset($options['title']) ? $options['title'] : '';

    echo '<input type="text" name="gcm_cookie_consent_banner_settings[title]" value="' . esc_attr($value) . '" placeholder="Αυτή η ιστοσελίδα χρησιμοποιεί cookies">';
}

// Render Description Field
function gcm_render_cookie_consent_banner_description_field()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $value = isset($options['description']) ? $options['description'] : '';
    echo '<textarea name="gcm_cookie_consent_banner_settings[description]" placeholder="Χρησιμοποιούμε cookies και άλλες τεχνολογίες εντοπισμού για την βελτίωση της εμπειρίας περιήγησης στην ιστοσελίδα μας, για την εξατομίκευση περιεχομένου και διαφημίσεων, την παροχή λειτουργιών κοινωνικών μέσων και την ανάλυση της επισκεψιμότητάς μας.">' . esc_textarea($value) . '</textarea>';
}

// Render Color Field
/**
 * @return void
 */
function gcm_render_cookie_consent_banner_background_color()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $color = isset($options['banner_background_color']) ? $options['banner_background_color'] : '#f0f0f0'; // Default color if option is not set

    echo '<input type="color" name="gcm_cookie_consent_banner_settings[banner_background_color]" value="' . esc_attr($color) . '">';
}

/**
 * @return void
 */
function gcm_render_cookie_consent_banner_text_color()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $color = isset($options['banner_text_color']) ? $options['banner_text_color'] : '#000'; // Default color if option is not set

    echo '<input type="color" name="gcm_cookie_consent_banner_settings[banner_text_color]" value="' . esc_attr($color) . '">';
}

// Render Color Field
/**
 * @return void
 */
function gcm_render_cookie_consent_banner_btns_background_color()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $color = isset($options['btns_background_color']) ? $options['btns_background_color'] : '#007bff'; // Default color if option is not set

    echo '<input type="color" name="gcm_cookie_consent_banner_settings[btns_background_color]" value="' . esc_attr($color) . '">';
}

// Render Color Field
function gcm_render_cookie_consent_banner_btns_text_color()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $color = isset($options['btns_text_color']) ? $options['btns_text_color'] : '#fff'; // Default color if option is not set

    echo '<input type="color" name="gcm_cookie_consent_banner_settings[btns_text_color]" value="' . esc_attr($color) . '">';
}

function gcm_render_cookie_consent_banner_show_close_btn()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $checked = isset($options['show_close_btn']) ? $options['show_close_btn'] : false;

    echo '<input type="checkbox" name="gcm_cookie_consent_banner_settings[show_close_btn]" ' . ($checked ? 'checked' : '') . '>';
}

/**
 * @return void
 */
function gcm_render_cookie_consent_banner_show_disagree_btn()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $checked = isset($options['show_disagree_btn']) ? $options['show_disagree_btn'] : false; // Default color if option is not set

    echo '<input type="checkbox" name="gcm_cookie_consent_banner_settings[show_disagree_btn]" ' . ($checked ? 'checked' : '') . '>';
}


// Render Multiselect Field
function gcm_render_cookie_consent_banner_position_field()
{
    $options = get_option('gcm_cookie_consent_banner_settings');
    $selected = isset($options['banner_position']) ? $options['banner_position'] : array();
    $values = array('Top', 'Center', 'Bottom'); // Example values

    echo '<select name="gcm_cookie_consent_banner_settings[banner_position][]">';
    foreach ($values as $value) {
        $selected_attr = in_array($value, $selected) ? 'selected' : '';
        echo '<option ' . ($value == 'Bottom' ? "selected" : "") . ' value="' . esc_attr($value) . '" ' . $selected_attr . '>' . esc_html($value) . '</option>';
    }
    echo '</select>';
}


/**
 * @return void
 */
function gcm_banner()
{

    $showBanner = !isset($_COOKIE['gmc_user_consent']);
    $options = get_option('gcm_cookie_consent_banner_settings');

    if (!$showBanner) return;
    ?>

    <div id="gmc_cookie_banner" class="cookie-banner <?php echo isset($options['banner_position'][0]) ? strtolower($options['banner_position'][0]) : 'bottom' ?>" style="<?php echo isset($options['banner_background_color']) ? 'background-color:' . $options['banner_background_color'] : '' ?>">
        <div class="banner__inner">
            <?php if (isset($options['show_close_btn'])) { ?>
                <span class="gcm_close_banner">X</span>
            <?php } ?>
            <h3 style="<?php echo isset($options['banner_text_color']) ? 'color:' . $options['banner_text_color'] : 'color:#000'; ?>">
                <?php echo !empty($options['title']) ? esc_html($options['title']) : esc_html__('Αυτή η ιστοσελίδα χρησιμοποιεί cookies', 'gmc_banner'); ?>
            </h3>

            <p style="<?php echo isset($options['banner_text_color']) ? 'color:' . $options['banner_text_color'] : "color:#000"; ?>">
                <?php echo !empty($options['description']) ? esc_html($options['description']) : esc_html__('Χρησιμοποιούμε cookies και άλλες τεχνολογίες εντοπισμού για την βελτίωση της εμπειρίας περιήγησης στην
                ιστοσελίδα μας, για την εξατομίκευση περιεχομένου και διαφημίσεων, την παροχή λειτουργιών κοινωνικών
                μέσων και την ανάλυση της επισκεψιμότητάς μας.', 'gmc_banner'); ?>
            </p>

            <div class="buttons">
                <button id="accept-btn" style=" <?php echo isset($options['btns_background_color']) ? 'background-color:' . $options['btns_background_color'] : ''; ?>"><?php _e("Συμφωνώ", 'gcm_banner') ?></button>
                <?php
                if (isset($options['show_disagree_btn'])) {
                    ?>
                    <button id="decline-btn" style=" <?php echo isset($options['btns_background_color']) ? 'background-color:' . $options['btns_background_color'] : ''; ?>"><?php _e("Διαφωνώ", 'gcm_banner') ?></button> <?php
                }
                ?>
                <button id="settings-btn" style=" <?php echo isset($options['btns_background_color']) ? 'background-color:' . $options['btns_background_color'] : ''; ?>"><?php _e("Προτιμήσεις", 'gcm_banner') ?></button>
            </div>
            <div id="settings-panel" class="settings-panel">
                <h3><?php _e("Ρυθμίσεις προτιμήσεων", 'gcm_banner') ?></h3>
                <label class="toggle-switch">
                    <input type="checkbox" id="gcm_essential_cookies" checked disabled>
                    <span class="slider"></span>
                    <?php _e("Απαραίτητα Cookies", 'gcm_banner') ?>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="gcm_functional_cookies" checked>
                    <span class="slider"></span>
                    <?php _e("Cookies Λειτουργικότητας", 'gcm_banner') ?>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="gcm_tracking_toggle" checked>
                    <span class="slider"></span>
                    <?php _e("Cookies Εντοπισμού", 'gcm_banner') ?>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="gcm_marketing_toggle" checked>
                    <span class="slider"></span>
                    <?php _e("Cookies Εξατομικευμένου περιεχομένου και διαφημίσεων", 'gcm_banner') ?>
                </label>
                <button id="save-settings-btn" style=" <?php echo isset($options['btns_background_color']) ? 'background-color:' . $options['btns_background_color'] : ''; ?>"><?php _e("Αποθήκευση", 'gcm_banner') ?></button>
            </div>
        </div>
    </div>
    <?php
}

add_action('wp_head', function () {
    $options = get_option('gcm_cookie_consent_banner_settings');

    ?>

    <style>
        #gmc_cookie_banner input:checked+.slider {
            background-color: <?php echo esc_attr($options['btns_background_color']) ?>;
        }
    </style>
    <?php
});