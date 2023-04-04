<?php 

add_filter( 'comments_array' , 'w3dev_filter_comments_by_role_frontend' , 10, 2 );
function w3dev_filter_comments_by_role_frontend( $comments, $post_id ){

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    if (!empty($settings['hide_banned_users_comments'])) {

        // go over each comments for the current post
        // --
        foreach( $comments as $key => $comment ){

            // Remove comment from comment array if user account is banned
            // --
            if ( get_user_option( 'w3dev_user_banned', $comment->user_id, FALSE ) ) {

                unset( $comments[$key] );

                // for future reference...
                // $comment_author = new WP_User( $comment->user_id ); // for each comment get the author user object
                // here we say unset the current comment if the role of the comment author is not the same as the role of the logged in user
                // if( $comment_author->roles[0] != $current_user->roles[0] ){ unset( $comments[$key] ); }

            }

        }

    }

    return $comments; // Return the filtered $comments array 

}

function w3dev_deny_password_reset( $allow, $user_id ) {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    if (!empty($settings['disable_password_reset_banned_users'])) {

        // WP_User object
        if (!empty($user_id)) {
            if ($w3dev_ban_user_class->is_user_banned($user_id)) { 
                $allow = false;
            }
        }

    }
    return $allow;
}
add_filter( 'allow_password_reset', 'w3dev_deny_password_reset', 10, 2 );


add_action( 'admin_menu', 'w3dev_ban_user_menu' );
function w3dev_ban_user_menu() {
    add_options_page( 
        'BAN User', 
        'BAN User', 
        'manage_options', 
        'ban_user_page.php', 
        'w3dev_ban_user_options_partial', 
        'dashicons-businessman', 6
        );

}


add_filter( 'wp_authenticate_user', 'w3dev_authenticatation_is_user_banned', 10, 2 );
function w3dev_authenticatation_is_user_banned( $user, $password ) {

    if (is_wp_error($user)) { return $user; }
    $user_id = $user->ID;    

    // Return error if user account is banned
    if ( get_user_option( 'w3dev_user_banned', $user_id, FALSE ) ) {

        $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
        $settings               = $w3dev_ban_user_class->get_options('settings');

        $banned_login_message = !empty($settings['banned_login_message']) ? $settings['banned_login_message'] :  $settings['_defaults']['banned_login_message'];
        $banned_login_message = $w3dev_ban_user_class->ban_tag_edit($banned_login_message, $user_id);

        return new WP_Error(
            'w3dev_user_banned',
            __( '<strong>ERROR</strong>: '.stripslashes($banned_login_message), 'w3dev' )
        );
    }
    
    return $user;
}


add_action( 'admin_init', 'w3dev_check_if_banned_user', 1 );
function w3dev_check_if_banned_user() {

    $user = get_user_by( 'ID', get_current_user_id() );
    
    if (is_wp_error($user)) { return $user; }

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    // Return error if user account is banned
    if ( get_user_option( 'w3dev_user_banned', $user->ID, FALSE ) ) {
        if (!empty($settings['display_message'])) {
	    wp_die( __( stripslashes( $w3dev_ban_user_class->ban_tag_edit($settings['custom_message'], $user->ID) ) ) );
        } else if (!empty($settings['force_logout'])) {
            if (!empty($settings['custom_logout'])) {
                header('Location: '.$settings['custom_logout_url']);
            } else {
                wp_logout();
                header('Location: /wp-login.php');
            }
        }
    }
}


function w3dev_ban_users_init_function() {

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    // check if user is logged in
    // check if public banned message is checked
    // check if current user is banned
    // --
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        if (!empty($current_user->ID) && $w3dev_ban_user_class->is_user_banned($current_user->ID)) {

            // only do this part on front end
            // --
            if ( ! is_admin() ) {

                if (!empty($settings['frontend_notification_force_logout']) || !empty($settings['force_logout'])) { 

                    wp_clear_auth_cookie();
                    wp_logout();

                    if (!empty($settings['force_logout'])) {
                        $logout_url = (!empty($settings['custom_logout'])) ? $settings['custom_logout_url'] : "/wp-login.php";
                        header('Location: '.$logout_url);
                        exit;
                    }

                }

                // display message to user on front end if options set
                // --
                if (!empty($settings['display_message']) && !empty($settings['frontend_banned_notification'])) {

                    $show_dialog = true;
                    if (!empty($settings['frontend_notification_hide'])) {

                        if (isset($_COOKIE['w3dev_bu_hide_notification'])) { 
                            $show_dialog = false; 
                        } else {
                            setcookie('w3dev_bu_hide_notification', 1, time() + (86400 * 30), "/");
                        }

                    } else {
                        if (isset($_COOKIE['w3dev_bu_hide_notification'])) { 
                            setcookie('w3dev_bu_hide_notification', "", time() - 3600);
                        }
                    }

                    // show dialog if it's not been seen before or should be show everytime
                    // --
                    if ($show_dialog) {
                        function w3dev_ban_user_script() {
                            
                            $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
                            $settings               = $w3dev_ban_user_class->get_options('settings');
                            $custom_message         = !empty($settings['custom_message']) ? $settings['custom_message'] : $settings['_defaults']['custom_message'];
                            $custom_message         = $w3dev_ban_user_class->ban_tag_edit($custom_message, $current_user->ID);
                            
                            ?>
                            <script type="text/javascript">
                            alertify.alert('Important Notification', '<?php echo $custom_message; ?>', function(){ 

                                <?php
                                if (!empty($settings['frontend_notification_force_logout'])) { 

                                    $logout_url = (!empty($settings['custom_logout'])) ? $settings['custom_logout_url'] : "/wp-login.php";
                                    echo 'window.location.href = "'.$logout_url.'";';

                                }
                                ?>

                            });
                            </script>

                        <?php
                        }
                        add_action('wp_footer', 'w3dev_ban_user_script');
                    
                    }

                } elseif ( !empty( $settings['frontend_notification_force_logout'] ) ) {
                    $logout_url = (!empty($settings['custom_logout'])) ? $settings['custom_logout_url'] : "/wp-login.php";
                    header('Location: '.$logout_url);
                    exit;
                }

            }

        }
    }

}
add_action('init', 'w3dev_ban_users_init_function');

?>