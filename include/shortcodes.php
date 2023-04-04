<?php 

function w3dev_is_user_banned_sc_function($atts){
	extract(shortcode_atts(array(
	'user_id' 	=> 0,
	'username' 	=> null,
	'email' 	=> null,
	), $atts));

	if (!empty($user_id)) {
		$user = get_user_by( 'ID', $user_id );
		$user_id = !empty($user) ? $user->ID : false;
	} elseif (!empty($username)) {
		$user = get_user_by( 'login', $username );
		$user_id = !empty($user) ? $user->ID : false;
	} elseif  (!empty($email)) {
		$user = get_user_by( 'email', $email );
		$user_id = !empty($user) ? $user->ID : false;
	}

	if (!empty($user_id)) {
		$w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
		$settings               = $w3dev_ban_user_class->get_options('settings');
		$banned_login_message   = !empty($settings['banned_login_message']) ? $settings['banned_login_message'] :  $settings['_defaults']['banned_login_message'];
		$banned_login_message   = $w3dev_ban_user_class->ban_tag_edit($banned_login_message, $user_id);

		// Return error if user account is banned
		// --
		if ( get_user_option( 'w3dev_user_banned', $user_id, FALSE ) ) {
			return stripslashes($banned_login_message);
		} else {
			return false;
		}
	} else {
		return false;
	}

   return;
}

function w3dev_get_user_ip_sc_function($atts){
	extract(shortcode_atts(array(
	'user_id' 	=> 0,
	'show_device' 	=> false,
	'show_country' 	=> false,
	'show_ip' 	=> true
	), $atts));

	$user_id = $atts["user_id"];


	global $wpdb;
	$db_table_name = $wpdb->prefix . 'w3dev_login_details';

	$results = $wpdb->get_results( $wpdb->prepare("
		SELECT ip_address, device, geodata FROM $db_table_name
		WHERE user_id = %d
		ORDER BY id DESC
		LIMIT 1
		", $user_id
		) );

		$ip_address = !empty($results[0]->ip_address) ? long2ip($results[0]->ip_address) : 'unknown';

	return $ip_address;

}


function register_w3dev_ubu_shortcodes(){
   add_shortcode('w3dev-is-user-banned', 'w3dev_is_user_banned_sc_function');
   add_shortcode('w3dev-get-user-ip', 'w3dev_get_user_ip_sc_function');
}
add_action( 'init', 'register_w3dev_ubu_shortcodes');

