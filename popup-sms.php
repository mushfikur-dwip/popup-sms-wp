<?php
/**
 * Plugin Name: Popup SMS
 * Description: Shows a professional popup SMS after 5 seconds of loading the website.
 * Version: 1.1
 * Author: Your Name
 */

function popup_sms_enqueue_scripts() {
    // Enqueue jQuery (needed for popup interaction)
    wp_enqueue_script('jquery');
    
    // Enqueue custom CSS
    wp_enqueue_style('popup-sms-style', plugin_dir_url(__FILE__) . 'style.css');
    
    // Custom JS for the popup SMS
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            setTimeout(function() {
                // Show the popup modal
                $("#sms-popup").fadeIn();
            }, 5000); // 5000 milliseconds = 5 seconds
            
            // Close the popup when clicking the close button
            $("#sms-close").click(function() {
                $("#sms-popup").fadeOut();
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'popup_sms_enqueue_scripts');

function popup_sms_html() {
    echo '
        <div id="sms-popup" class="popup-modal">
            <div class="popup-content">
                <div class="popup-header">
                    <span id="sms-close" class="popup-close">&times;</span>
                    <h2>SMS Notification</h2>
                </div>
                <div class="popup-body">
                    <p>This is your professional popup SMS message! You have a new update.</p>
                </div>
            </div>
        </div>
    ';
}
add_action('wp_footer', 'popup_sms_html');
?>
