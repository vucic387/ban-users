<?php
/*
 * Plugin Name: w3dev Ban Users
 * Plugin URI: http://webxmedia.co.uk
 * Description: Ban a user from logging into their wordpress account.
 * Version: 1.5.4
 * Author: WEBxMedia LTD. (Matt Whiteman)
 * Author URI: http://webxmedia.co.uk
 * License: GPLv2
 * Text Domain: ban-users
 * Domain Path: ./languages
 */

/*
$args = array(
    'user_id' => 13, // use user_id
    'count' => true //return only the count
);
$comments = get_comments($args);
echo $comments;
exit;
*/

if ( ! defined('ABSPATH' ) ) exit;
if ( !defined('W3DEV_DEBUG_MODE') ) { DEFINE('W3DEV_DEBUG_MODE', '0'); }

DEFINE( 'W3DEV_BAN_USERS_PLUGIN_VERSION', '1.5.4' );
DEFINE( 'W3DEV_BAN_USERS_PREMIUM_VERSION', FALSE );
DEFINE( 'W3DEV_BAN_USERS_PLUGIN_FILE', __FILE__ );
DEFINE( 'W3DEV_BAN_USERS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
DEFINE( 'W3DEV_BAN_USERS_PLUGIN_NAME', ( W3DEV_BAN_USERS_PREMIUM_VERSION ? 'Ultimate BAN Users' : 'BAN Users' ) );

if ( !defined('W3DEV_IS_ADMIN') ) { DEFINE( 'W3DEV_IS_ADMIN', is_admin() ? true : false ); }
if ( W3DEV_DEBUG_MODE ) { ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); }

add_action( 'plugins_loaded', 'w3dev_ban_users_load_textdomain' );
function w3dev_ban_users_load_textdomain() {
  load_plugin_textdomain( 'ban-users', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

include_once('w3dev-ban-users-class.php');
$w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
$settings               = $w3dev_ban_user_class->get_options('settings');

if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {
    include_once( 'include/plugin-activation.php' );
    include_once( 'include/mobidetect/Mobile_Detect.php' );
    include_once( 'include/ultimate-ajax.php' );
    
    if ( !empty( $settings['send_notification_new_post'] ) ) { // check to see if email moderation is enabled
        include_once('include/moderation.php');
    }
}

function ban_users_init() {
  load_plugin_textdomain( 'ban_users', false, 'ban_users/languages' );
}
add_action('init', 'ban_users_init');

include_once( 'include/admin-enqueue-script.php' );
include_once( 'include/public-enqueue-script.php' );
include_once( 'include/crons.php' );
include_once( 'include/ajax.php' );
include_once( 'include/settings-page.php' );
include_once( 'include/view.php' );
include_once( 'include/functions.php' );
include_once( 'include/shortcodes.php' );

if ( !empty( $settings['extensions']['ultimate_member'] ) || !isset( $settings['extensions']['ultimate_member'] ) ) {
    include_once( 'include/plugins/ultimate-member.php' );
}

function w3dev_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=ban_user_page.php">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
    if ( !W3DEV_BAN_USERS_PREMIUM_VERSION ) {
        $buyLink = '<a target="_blank" style="pointer-events: none; text-decoration: line-through;" href="https://codecanyon.net/item/wp-ultimate-ban-users/17508338"><b style="color:red">Go <i>Pro</i>!</b></a>';
        array_unshift($links, $buyLink);
    }
    return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'w3dev_plugin_add_settings_link' );

include_once('banned-history-class.php');

function w3dev_ban_users_activate() {

    global $wpdb;
	
    // select all users without cpt banned-history (user.meta_key='_banned_history_id')
	$users = $wpdb->get_results( "SELECT u.ID FROM {$wpdb->users} AS u LEFT JOIN {$wpdb->usermeta} AS m ON u.ID = m.user_id AND m.meta_key = '_banned_history_id' WHERE m.meta_value IS NULL" );
    // loop trough each user
    foreach ($users as $user) {
        // cpt banned-history add
        BANNED_HISTORY::profile_update($user->ID);
    }
};
register_activation_hook( __FILE__, 'w3dev_ban_users_activate' );


?>