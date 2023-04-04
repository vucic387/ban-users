<?php 

function w3dev_cron_unban_user($user_id) {

    include_once( W3DEV_BAN_USERS_PLUGIN_DIR_PATH. 'w3dev-ban-users-class.php' );
    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();

    if ($w3dev_ban_user_class->is_user_banned($user_id)) { $w3dev_ban_user_class->unban_user( $user_id ); }

}
add_action( 'w3dev_unban_user','w3dev_cron_unban_user');


?>