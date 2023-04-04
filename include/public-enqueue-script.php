<?php
if ( ! defined('ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'w3dev_ban_users_public_css' );
function w3dev_ban_users_public_css() {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    if (empty($settings['disable_autoload']['alertify'])) {
        wp_register_style('w3dev-alertify-css', "//cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css", array(), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-alertify-css');
        wp_register_style('w3dev-alertify-theme-css', "//cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css", array('w3dev-alertify-css'), W3DEV_BAN_USERS_VERSION_ID);
        wp_enqueue_style('w3dev-alertify-theme-css');
    }

}


add_action( 'wp_enqueue_scripts', 'w3dev_ban_users_public_js' );
function w3dev_ban_users_public_js() {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    if (empty($settings['disable_autoload']['alertify'])) {
        wp_register_script('w3dev-alertify-js', '//cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js', array('jquery'), W3DEV_BAN_USERS_VERSION_ID, false);
        wp_enqueue_script('w3dev-alertify-js');
    }
    
}