<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
$option_name = 'w3dev_user_banned';
delete_option( $option_name );
delete_site_option( $option_name ); // For site options in Multisite 

$option_name = 'w3dev_ban_user_email_templates';
delete_option( $option_name );
delete_site_option( $option_name ); // For site options in Multisite 

