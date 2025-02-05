<?php
/**
 * Plugin Name: Popup SMS
 * Description: Shows a professional popup SMS after 5 seconds of loading the website.
 * Version: 1.2
 * Author: Mushfikur Rahmang
 */

// Add the admin menu and settings page
function popup_sms_admin_menu() {
    add_menu_page(
        'Popup SMS Settings',
        'Popup SMS',
        'manage_options',
        'popup-sms-settings',
        'popup_sms_settings_page',
        'dashicons-email-alt',
        30
    );
}
add_action('admin_menu', 'popup_sms_admin_menu');

// Settings page content
function popup_sms_settings_page() {
    ?>
    <div class="wrap">
        <h1>Popup SMS Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('popup_sms_options_group');
            do_settings_sections('popup-sms-settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Popup Display Time (seconds)</th>
                    <td>
                        <input type="number" name="popup_sms_display_time" value="<?php echo esc_attr(get_option('popup_sms_display_time', 5)); ?>" min="1" />
                        <p class="description">Enter the time in seconds before the popup appears.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Popup Message</th>
                    <td>
                        <textarea name="popup_sms_message" rows="4" cols="50"><?php echo esc_textarea(get_option('popup_sms_message', 'This is your professional popup SMS message! You have a new update.')); ?></textarea>
                        <p class="description">Enter the SMS message to be displayed in the popup.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Enable Popup</th>
                    <td>
                        <input type="checkbox" name="popup_sms_enable" value="1" <?php checked(1, get_option('popup_sms_enable', 1)); ?> />
                        <p class="description">Check to enable the popup. Uncheck to disable it.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register the settings
function popup_sms_register_settings() {
    register_setting('popup_sms_options_group', 'popup_sms_display_time');
    register_setting('popup_sms_options_group', 'popup_sms_message');
    register_setting('popup_sms_options_group', 'popup_sms_enable');
}
add_action('admin_init', 'popup_sms_register_settings');

// Enqueue front-end scripts and styles
function popup_sms_enqueue_scripts() {
    // Enqueue jQuery (needed for popup interaction)
    wp_enqueue_script('jquery');

    // Enqueue custom CSS
    wp_enqueue_style('popup-sms-style', plugin_dir_url(__FILE__) . 'style.css');

    // Custom JS for the popup SMS
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            if (' . (get_option('popup_sms_enable', 1) == 1 ? 'true' : 'false') . ') {
                var popupDisplayTime = ' . esc_js(get_option('popup_sms_display_time', 5)) . ' * 1000; // Convert to milliseconds
                setTimeout(function() {
                    // Show the popup modal
                    $("#sms-popup").fadeIn();
                }, popupDisplayTime); // Custom display time from admin settings
                
                // Close the popup when clicking the close button
                $("#sms-close").click(function() {
                    $("#sms-popup").fadeOut();
                });
            }
        });
    ');
}
add_action('wp_enqueue_scripts', 'popup_sms_enqueue_scripts');

// Add HTML for the popup
function popup_sms_html() {
    if (get_option('popup_sms_enable', 1) == 1) {
        $message = get_option('popup_sms_message', 'This is your professional popup SMS message! You have a new update.');
        echo '
            <div id="sms-popup" class="popup-modal">
                <div class="popup-content">
                    <div class="popup-header">
                        <span id="sms-close" class="popup-close">&times;</span>
                        <h2>SMS Notification</h2>
                    </div>
                    <div class="popup-body">
                        <p>' . esc_html($message) . '</p>
                    </div>
                </div>
            </div>
        ';
    }
}
add_action('wp_footer', 'popup_sms_html');
?>
