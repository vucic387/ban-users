<?php
if ( ! defined('ABSPATH' ) ) exit;

add_action( 'admin_enqueue_scripts', 'w3dev_ban_users_css' );
function w3dev_ban_users_css() {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');
    
    if (empty($settings['disable_autoload']['flatpickr'])) {
        wp_register_style('w3dev-jquery-datepicker-css', "//cdnjs.cloudflare.com/ajax/libs/flatpickr/2.0.5/flatpickr.base16_flat.min.css", array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-jquery-datepicker-css');
        wp_register_style('w3dev-jquery-datepicker-red-css', "//cdnjs.cloudflare.com/ajax/libs/flatpickr/2.0.5/flatpickr.material_red.min.css", array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-jquery-datepicker-red-css');
    }

    if (empty($settings['disable_autoload']['fa'])) {
        wp_register_style('w3dev-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-font-awesome');
    }

    wp_register_style('w3dev-mini-bs-helper-css', plugins_url('../css/mini-bs-v3-helper.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
    wp_enqueue_style('w3dev-mini-bs-helper-css');

    wp_register_style('w3dev-ban-users-css', plugins_url('../css/style.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
    wp_enqueue_style('w3dev-ban-users-css');

    wp_register_style('w3dev-balloon-css', plugins_url('../css/balloon.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
    wp_enqueue_style('w3dev-balloon-css');

    if (empty($settings['disable_autoload']['faanimation'])) {
        wp_register_style('w3dev-faa-css', plugins_url('../lib/font-awesome-animation/font-awesome-animation.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-faa-css');
    }
    
    if (empty($settings['disable_autoload']['datatables'])) {
        wp_register_style('w3dev-dataTables-css', "//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css", array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-dataTables-css');
    }

    if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {

        wp_register_style('w3dev-flags-css', plugins_url('../css/flags.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-flags-css');

        if (empty($settings['disable_autoload']['notify'])) {
            wp_register_style('w3dev-notifyjs-metro-css', plugins_url('../lib/notifyjs/styles/metro/notify-metro.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
            wp_enqueue_style('w3dev-notifyjs-metro-css');
        }

    }

    if (empty($settings['disable_autoload']['jq_confirm'])) {

        wp_register_style('jquery-confirm-css', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css', array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('jquery-confirm-css');
    }

    if (empty($settings['disable_autoload']['selectric'])) {
        wp_register_style('jquery-selectric-css', plugins_url('../lib/jQuery-Selectric-v1.13.0/selectric.css', __FILE__), array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('jquery-selectric-css');
    }

    if (empty($settings['disable_autoload']['sumoselect'])) {
        wp_register_style('jquery-sumoselect-css', '//cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/sumoselect.min.css', array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('jquery-sumoselect-css');
    }

}


add_action( 'admin_enqueue_scripts', 'w3dev_ban_users_js' );
function w3dev_ban_users_js($hook) {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    wp_register_script('w3dev-ban-users-js', plugins_url('../javascript/app.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
    wp_enqueue_script('w3dev-ban-users-js');


    $default_ban_reason     = isset($settings['default_ban_reason']) ? $settings['default_ban_reason'] : '';
    $default_warn_reason    = isset($settings['default_warn_reason']) ? $settings['default_warn_reason'] : '';

    $localize_script_data = array(
        'default_ban_reason'  => $default_ban_reason,
        'default_warn_reason' => $default_warn_reason
    );
    wp_localize_script( 'w3dev-ban-users-js', 'php_vars', $localize_script_data );

    // only load on Plugin Settings Page
    // --
    if ($hook == 'settings_page_ban_user_page') {

        wp_register_script('w3dev-ban-users-settings', plugins_url('../javascript/app-settings.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-ban-users-settings');

        if (empty($settings['disable_autoload']['datatables'])) {

            wp_register_script('w3dev-datatables-js', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
            wp_enqueue_script('w3dev-datatables-js');
        }
    }
        
    if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {

        if (empty($settings['disable_autoload']['notify'])) {
            wp_register_script('w3dev-notifyjs-js', plugins_url('../lib/notifyjs/notify.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
            wp_enqueue_script('w3dev-notifyjs-js');
            wp_register_script('w3dev-notify-metro-js', plugins_url('../lib/notifyjs/styles/metro/notify-metro.js', __FILE__), array('jquery','w3dev-notifyjs-js'), W3DEV_BAN_USERS_VERSION_ID, false);
            wp_enqueue_script('w3dev-notify-metro-js');
        }

    }

    wp_register_script('w3dev-momentjs-js', plugins_url('../javascript/moment-with-locales.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
    wp_enqueue_script('w3dev-momentjs-js');

    if (empty($settings['disable_autoload']['flatpickr'])) {
        wp_register_script('w3dev-jquery-datepicker-js', '//cdnjs.cloudflare.com/ajax/libs/flatpickr/2.0.5/flatpickr.js', array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-jquery-datepicker-js');
    }

    if (empty($settings['disable_autoload']['jq_confirm'])) {
        wp_register_script('w3dev-jquery-confirm-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-jquery-confirm-js');
    }

    if (empty($settings['disable_autoload']['selectric'])) {
        wp_register_script('w3dev-jquery-selectric-js', plugins_url('../lib/jQuery-Selectric-v1.13.0/jquery.selectric.min.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-jquery-selectric-js');
    }

    if (empty($settings['disable_autoload']['sumoselect'])) {
        wp_register_script('w3dev-jquery-sumoselect-js', '//cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js', array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-jquery-sumoselect-js');
    }

    if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {

        wp_register_script('w3dev-ultimate-ban-users-js', plugins_url('../javascript/ultimate-app.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-ultimate-ban-users-js');

        // only load on Plugin Settings Page
        // --
        if ($hook == 'settings_page_ban_user_page') {
            wp_register_script('w3dev-ultimate-ban-users-settings-js', plugins_url('../javascript/plugin-settings.js', __FILE__), array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
            wp_enqueue_script('w3dev-ultimate-ban-users-settings-js');
        }

    }

}
