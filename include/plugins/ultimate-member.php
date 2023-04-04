<?php 

function w3dev_ultimatemember_login_check( $args ){

	global $ultimatemember;

	if (!empty($ultimatemember)) {

		$user_id = ( isset( $ultimatemember->login->auth_id ) ) ? $ultimatemember->login->auth_id : '';

		include_once( W3DEV_BAN_USERS_PLUGIN_DIR_PATH. 'w3dev-ban-users-class.php' );
		$w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();
		$settings = get_option('w3dev_ban_user_options', $w3dev_ban_user_class->default_options('settings') );

		if ($w3dev_ban_user_class->is_user_banned($user_id)) {
			if(!empty($settings['display_message'])){
				$banned_login_message = stripslashes($settings['banned_login_message']);
			}
			else {
				$banned_login_message = $w3dev_ban_user_class->default_options('settings');
				$banned_login_message = $banned_login_message['banned_login_message'];
			}
            $ultimatemember->form->errors[] = $banned_login_message;
        }

	}

}
add_filter( 'um_submit_form_errors_hook_logincheck', 'w3dev_ultimatemember_login_check' );

?>