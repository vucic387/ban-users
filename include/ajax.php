<?php
if ( ! defined('ABSPATH' ) ) exit;

add_action( 'wp_ajax_w3dev_banned_history', 'w3dev_banned_history_callback' );
function w3dev_banned_history_callback() {

    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();
    $user_id    = isset($_POST['user_id']) ? floatval($_POST['user_id']) : false;
    $is_banned  = $w3dev_ban_user_class->is_user_banned($user_id);
    $user_info  = get_userdata($user_id);
    ?>

    <table id="w3dev-shared-accounts">
        <tr class="alt">
            <td style="text-align:center;width:40px"><i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i></td>
            <th style="width:140px;">User Login (ID)</th>
            <td><?php echo $user_info->user_login.' ('.$user_id.')'; ?></td>
        </tr>
        <tr>
            <td style="text-align:center;width:40px"><i class="fa fa-ban fa-lg" aria-hidden="true"></i></td>
            <th style="width:140px;">Currently banned</th>
            <td><?php echo $is_banned ? 'Yes' : 'No'; ?></td>
        </tr>
        <tr class="alt">
            <td style="text-align:center"><i class="fa fa-calendar-times-o fa-lg" aria-hidden="true"></i></td>
            <th>Date <?php echo $is_banned ? null : 'un'; ?>banned</th>
            <td><?php $banned_date = get_user_option( 'w3dev_user_banned_date',  $user_id ); echo !empty($banned_date) ? $banned_date : '--'; ?></td>
        </tr>
        <tr>
            <td style="text-align:center"><i class="fa fa-clock-o fa-lg" aria-hidden="true"></i></td>
            <th>Ban duration</th>
            <td>
                <?php if ($is_banned) {
                    $ban_expires = wp_next_scheduled('w3dev_unban_user', array( intval($user_id) ));
                    if (empty($ban_expires)) {
                        echo '--'; 
                    } else {
                        $date_a = new DateTime(date('Y-m-d', strtotime($banned_date)));
                        $date_b = new DateTime(date('Y-m-d', $ban_expires));
                        $interval = date_diff($date_a,$date_b);
                        echo $interval->format('%d').' days';
                    }
                } else { 
                    echo '--'; 
                }
                ?>
             </td>
        </tr>
        <tr class="alt">
            <td style="text-align:center"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"></i></td>
            <th>Ban expires</th>
            <td>
                <?php 
                if ($is_banned) {
                    echo !empty($ban_expires) ? date('d-m-Y', $ban_expires) : 'Never'; //Indefinately
                } else {
                    echo '--';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td style="text-align:center;vertical-align: top"><i class="fa fa-sticky-note-o fa-lg" aria-hidden="true"></i></td>
            <th style="vertical-align: top">Reason for <?php echo $is_banned ? null : 'last '; ?>ban</th>
            <td style="vertical-align: top">
                <?php 
                $banned_reason = get_user_option( 'w3dev_user_banned_reason',  $user_id );
                if (!empty($banned_reason)) {
                    echo $banned_reason;
                } else {
                    echo '--';
                }
                ?>
            </td>
        </tr>
    </table>
    <style>
    .jconfirm-content{height:auto !important;}
    table#w3dev-shared-accounts { width: 100%; border-collapse: collapse; }
    table#w3dev-shared-accounts tr th { text-align: left; }
    table#w3dev-shared-accounts td,
    table#w3dev-shared-accounts th { padding: 10px; color: #666666; vertical-align:middle }
    table#w3dev-shared-accounts tr.alt { background-color: #f9f9f9 }
    </style>

    <?php

    echo $html;
    wp_die();

}

add_action( 'wp_ajax_w3dev_quick_unban_user', 'w3dev_quick_unban_user_callback' );
function w3dev_quick_unban_user_callback() {

    $user_id = isset($_POST['user_id']) ? floatval($_POST['user_id']) : false;
    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();

    if (!empty($user_id)) {
        $w3dev_ban_user_class->unban_user( $user_id, array() );
        echo 'unbanned'; // user has been unbanned
    }
    
    wp_die();

}

add_action( 'wp_ajax_w3dev_toggle_ban_user', 'w3dev_toggle_ban_user_callback' );
function w3dev_toggle_ban_user_callback() {

    $user_id        = isset($_POST['user_id']) ? floatval($_POST['user_id']) : false;
    $message        = isset($_POST['message']) ? trim($_POST['message']) : false;
    $date           = isset($_POST['unban_date']) ? trim($_POST['unban_date']) : false;
    $ban_duration   = isset($_POST['ban_duration']) ? trim($_POST['ban_duration']) : false;

    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();

    if (!empty($user_id)) {
        if ($w3dev_ban_user_class->is_user_banned($user_id)) {
            $w3dev_ban_user_class->unban_user( $user_id, array() );
            echo 'unbanned'; // user has been unbanned
        } else {
            $w3dev_ban_user_class->ban_user( $user_id, $message, $date, $ban_duration );
            echo 'banned'; // user has been banned
        }
    }
    
    wp_die();

}

add_action( 'wp_ajax_w3dev_warn_ban_user', 'w3dev_toggle_warn_user_callback' );
function w3dev_toggle_warn_user_callback() {

    $user_id    = isset($_POST['user_id']) ? floatval($_POST['user_id']) : false;
    $reason     = isset($_POST['reason']) ? trim($_POST['reason']) : false;

    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();

    if (!empty($user_id)) {

        $settings               = $w3dev_ban_user_class->get_options('settings');
        $notifications          = $w3dev_ban_user_class->get_options('notifications');
        $email_template         = $notifications['user_notification'];

        $user_info  = get_userdata($user_id);
        $user_email = $user_info->user_email;
        $user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);

        if (!empty($user_email)) {
    
            // next we need to get the template and replace the reason tag 
            // %%reason%% with the text provided in the options or popup window
            // --
            $subject_title      = !empty( $email_template['warn_subject_title'] ) ? $email_template['warn_subject_title'] : $notifications['_defaults']['user_notification']['warn_subject_title'];
            $body               = !empty( $email_template['warn_body'] ) ? $email_template['warn_body'] : $notifications['_defaults']['user_notification']['warn_body'];
            $find_reason_tag    = strpos( $body, '%%reason%%' );

            // Determine if the message sent as a parameter is empty
            // Replaces the empty message with a generic one
            // --
            if ( empty($message) ) { $message = $settings['ban_email_default_message']; }
            if ( $find_reason_tag !== false ) { 
                $reason = empty($reason) ? null : $reason;
                $body = str_replace('%%reason%%', $reason, $body);
            }

            $body = str_replace('%%username%%', $user_info->user_login, $body);
            $body = str_replace('%%first_name%%', $user_info->first_name, $body);
            $body = str_replace('%%last_name%%', $user_info->last_name, $body);

            // define headers
            // --
            $headers = array();
            $headers[] = "Content-Type: text/html; charset=utf-8\r\n";

            // include bcc and cc if applicable
            // --
            if (!empty($email_template['warn_cc_field'])) { $headers[] = 'Cc: '.$email_template['warn_cc_field']; }
            if (!empty($email_template['warn_bcc_field'])) { $headers[] = 'Bcc: '.$email_template['warn_bcc_field']; }

            // finally, a quick check to ensure the email is valid
            // before sending using wp_mail
            // --
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                wp_mail( $user_email, $subject_title, $body, $headers );            
            }
        }

        
        echo '<span style="color:#a00">Warn</span>';
    }
    
    wp_die();

}

add_action( 'wp_ajax_w3dev_save_ban_user_settings', 'w3dev_save_ban_user_settings_callback' );
function w3dev_save_ban_user_settings_callback() {

    global $wpdb;

    // validation
    // --
    
    // custom_logout_url can be relative (i.e. about-us.php, or /about-us.php) so does not necessarily need a prefix of http(s)://
    // post_status, display_message, force_logout, custom_logout will always be 0 or 1 so set with inval()
    // --   
    $post_status                            = isset( $_POST['post_status'] ) ? trim( $_POST['post_status'] ) : null;
    $supported_cpt                          = isset( $_POST['supported_cpt'] ) ? trim( $_POST['supported_cpt'] ) : null;
    $set_banned_user_role                   = isset( $_POST['set_banned_user_role'] ) ? trim( $_POST['set_banned_user_role'] ) : null;
    $set_unbanned_user_role                 = isset( $_POST['set_unbanned_user_role'] ) ? trim( $_POST['set_unbanned_user_role'] ) : null;
    $custom_message                         = isset( $_POST['custom_message'] ) ? trim( $_POST['custom_message'] ) : null;
    $custom_logout_url                      = isset( $_POST['custom_logout_url'] ) ? trim( $_POST['custom_logout_url'] ) : null;
    $ban_email_default_message              = isset( $_POST['ban_email_default_message'] ) ? trim( $_POST['ban_email_default_message'] ) : null;
    $users_tbl_row_highlighted              = !empty( $_POST['users_tbl_row_highlighted'] ) ? 1 : 0;
    $users_tbl_data_column                  = !empty( $_POST['users_tbl_data_column'] ) ? 1 : 0;
    $banned_login_message                   = isset( $_POST['banned_login_message'] ) ? trim( $_POST['banned_login_message'] ) : null;
    $default_ban_reason                     = isset( $_POST['default_ban_reason'] ) ? trim( $_POST['default_ban_reason'] ) : null;
    $default_warn_reason                    = isset( $_POST['default_warn_reason'] ) ? trim( $_POST['default_warn_reason'] ) : null;
    $change_posts_status                    = intval( $_POST['change_posts_status'] );
    $post_status                            = !empty( $post_status ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $post_status) : null;
    $enable_support_cpt                     = intval( $_POST['enable_support_cpt'] );
    $supported_cpt                          = !empty( $supported_cpt ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $supported_cpt) : null;
    $on_ban_change_user_role                = intval( $_POST['on_ban_change_user_role'] );
    $set_banned_user_role                   = !empty( $set_banned_user_role ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $set_banned_user_role) : null;
    $on_unban_change_user_role              = intval( $_POST['on_unban_change_user_role'] );
    $set_unbanned_user_role                 = !empty( $set_unbanned_user_role ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $set_unbanned_user_role) : null;
    $display_message                        = intval( $_POST['display_message'] );
    $custom_message                         = !empty( $custom_message ) ? strip_tags($custom_message) : null;
    $force_logout                           = intval( $_POST['force_logout'] );
    $custom_logout                          = intval( $_POST['custom_logout'] );
    $custom_logout_url                      = !empty( $custom_logout_url ) ? preg_replace('/[^a-z_\\/:~%-.0-9\s|+]/i', '', $custom_logout_url) : null;
    $close_panels                           = !empty( $_POST['close_panels'] ) ? 1 : 0;
    $unban_date                             = intval( $_POST['unban_date'] );
    $ban_email                              = intval( $_POST['ban_email'] );
    $ban_email_default                      = intval( $_POST['ban_email_default'] );
    $ban_email_default_message              = !empty( $ban_email_default_message ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $ban_email_default_message) : null;
    $banned_login_message                   = !empty( $banned_login_message ) ? strip_tags($banned_login_message) : null;
    $date_format                            = !empty( $_POST['date_format'] ) ? $_POST['date_format'] : 'd-m-Y';
    $warn_user                              = !empty( $_POST['warn_user'] ) ? 1 : 0;
    $warn_user_reason                       = !empty( $_POST['warn_user_reason'] ) ? 1 : 0;
    $hide_banned_users_comments             = !empty( $_POST['hide_banned_users_comments'] ) ? 1 : 0;
    $scramble_banned_users_password         = !empty( $_POST['scramble_banned_users_password'] ) ? 1 : 0;
    $disable_password_reset_banned_users    = !empty( $_POST['disable_password_reset_banned_users'] ) ? 1 : 0;


    $set_spammer_option                 = !empty( $_POST['set_spammer_option'] ) ? 1 : 0;
    $unset_spammer_option               = !empty( $_POST['unset_spammer_option'] ) ? 1 : 0;

    $default_ban_reason                 = !empty( $default_ban_reason ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $default_ban_reason) : null;
    $default_warn_reason                = !empty( $default_warn_reason ) ? preg_replace('/[^a-z_\-.0-9\s]/i', '', $default_warn_reason) : null;

    $frontend_banned_notification       = !empty( $_POST['frontend_banned_notification'] ) ? 1 : 0;
    $frontend_notification_force_logout = !empty( $_POST['frontend_notification_force_logout'] ) ? 1 : 0;
    $frontend_notification_hide         = !empty( $_POST['frontend_notification_hide'] ) ? 1 : 0;

    $enable_accessibility               = !empty( $_POST['enable_accessibility'] ) ? 1 : 0;

    $disable_autoload = array();
    $disable_autoload['fa']             = !empty( $_POST['autoload_fa'] ) ? 1 : 0;
    $disable_autoload['jq_confirm']     = !empty( $_POST['autoload_jq_confirm'] ) ? 1 : 0;
    $disable_autoload['datatables']     = !empty( $_POST['autoload_datatables'] ) ? 1 : 0;
    $disable_autoload['notify']         = !empty( $_POST['autoload_notify'] ) ? 1 : 0;
    $disable_autoload['selectric']      = !empty( $_POST['autoload_selectric'] ) ? 1 : 0;
    $disable_autoload['flatpickr']      = !empty( $_POST['autoload_flatpickr'] ) ? 1 : 0;
    $disable_autoload['alertify']       = !empty( $_POST['autoload_alertify'] ) ? 1 : 0;
    $disable_autoload['faanimation']    = !empty( $_POST['autoload_faanimation'] ) ? 1 : 0;
    

    $extentions = array();
    $extensions['ultimate_member']      = !empty( $_POST['ext_ultimate_member'] ) ? 1 : 0;

    $security = array();
    $security['enable_admin_override']  = !empty( $_POST['security_enable_admin_override'] ) ? $_POST['security_enable_admin_override'] : 0;
    $security['set_moderator_roles']    = !empty( $_POST['security_set_moderator_roles'] ) ? $_POST['security_set_moderator_roles'] : 0;
    $security['moderator_roles']        = !empty( $_POST['security_moderator_roles'] ) ? $_POST['security_moderator_roles'] : 0;
    $security['set_moderated_roles']    = !empty( $_POST['security_set_moderated_roles'] ) ? $_POST['security_set_moderated_roles'] : 0;
    $security['moderated_roles']        = !empty( $_POST['security_moderated_roles'] ) ? $_POST['security_moderated_roles'] : 0;

    // if set post_status is true, then check selected status is valid
    // --
    if (!empty($post_status)) {
    
        $stati = array('publish','future','draft','pending','private','trash','auto-draft','inherit');

        // if status is not in array, then set post_status back to false (0)
        // --
        $post_status = in_array($post_status,$stati) ? $post_status : 0;

    }

    $w3dev_ban_user_options = array(
        'post_status'                           => $post_status,
        'change_posts_status'                   => $change_posts_status,
        'enable_support_cpt'                    => $enable_support_cpt,
        'supported_cpt'                         => $supported_cpt,
        'on_ban_change_user_role'               => $on_ban_change_user_role,
        'set_banned_user_role'                  => $set_banned_user_role,
        'on_unban_change_user_role'             => $on_unban_change_user_role,
        'set_unbanned_user_role'                => $set_unbanned_user_role,
        'display_message'                       => $display_message,
        'custom_message'                        => sanitize_text_field($custom_message),
        'force_logout'                          => $force_logout,
        'custom_logout'                         => $custom_logout,
        'custom_logout_url'                     => $custom_logout_url,
        'close_panels'                          => $close_panels,
        'unban_date'                            => $unban_date,
        'ban_email'                             => $ban_email,
        'ban_email_default'                     => $ban_email_default,
        'ban_email_default_message'             => $ban_email_default_message,
        'users_tbl_row_highlighted'             => $users_tbl_row_highlighted,
        'users_tbl_data_column'                 => $users_tbl_data_column,
        'banned_login_message'                  => $banned_login_message,
        'hide_banned_users_comments'            => $hide_banned_users_comments,
        'scramble_banned_users_password'        => $scramble_banned_users_password,
        'disable_password_reset_banned_users'   => $disable_password_reset_banned_users,
        'set_spammer_option'                    => $set_spammer_option,
        'unset_spammer_option'                  => $unset_spammer_option,
        'default_ban_reason'                    => $default_ban_reason,
        'default_warn_reason'                   => $default_warn_reason,
        'date_format'                           => $date_format,
        'warn_user'                             => $warn_user,
        'warn_user_reason'                      => $warn_user_reason,
        'frontend_banned_notification'          => $frontend_banned_notification,
        'frontend_notification_force_logout'    => $frontend_notification_force_logout,
        'frontend_notification_hide'            => $frontend_notification_hide,
        'enable_accessibility'                  => $enable_accessibility,
        'disable_autoload'                      => $disable_autoload,
        'extensions'                            => $extensions,
        'security'                              => $security
        );

    update_option( 'w3dev_ban_user_options', $w3dev_ban_user_options );
    
    echo '1';
    wp_die(); // this is required to terminate immediately and return a proper response

}

/**
 * This function is used to save the email template content
 * 
 */
add_action( 'wp_ajax_w3dev_save_ban_email_template', 'w3dev_save_ban_email_template_callback' );
function w3dev_save_ban_email_template_callback() {

    $ban_subject_title = trim( $_POST['ban_subject_title'] );
    $ban_body = trim( $_POST['ban_body'] );

    $unban_subject_title            = trim( $_POST['unban_subject_title'] );
    $unban_body                     = trim( $_POST['unban_body'] );
    $unban_indefinite_date_tag      = trim( $_POST['unban_indefinite_date_tag'] );
    $warn_subject_title             = trim( $_POST['warn_subject_title'] );
    $warn_body                      = trim( $_POST['warn_body'] );

    $ban_cc_field                   = trim( $_POST['ban_cc_field'] );
    $ban_bcc_field                  = trim( $_POST['ban_bcc_field'] );
    $unban_cc_field                 = trim( $_POST['unban_cc_field'] );
    $unban_bcc_field                = trim( $_POST['unban_bcc_field'] );
    $warn_cc_field                  = trim( $_POST['warn_cc_field'] ) ;
    $warn_bcc_field                 = trim( $_POST['warn_bcc_field'] );

    $w3dev_ban_templates = array(
        'user_notification' => array(
            'subject_title'                 => $ban_subject_title,
            'body'                          => $ban_body, 
            'ban_cc_field'                  => (!filter_var($ban_cc_field, FILTER_VALIDATE_EMAIL) ? null : $ban_cc_field),
            'ban_bcc_field'                 => (!filter_var($ban_bcc_field, FILTER_VALIDATE_EMAIL) ? null : $ban_bcc_field),
            'unban_subject_title'           => $unban_subject_title,
            'unban_body'                    => $unban_body,
            'unban_cc_field'                => (!filter_var($unban_cc_field, FILTER_VALIDATE_EMAIL) ? null : $unban_cc_field),
            'unban_bcc_field'               => (!filter_var($unban_bcc_field, FILTER_VALIDATE_EMAIL) ? null : $unban_bcc_field),
            'unban_indefinite_date_tag'     => $unban_indefinite_date_tag,
            'warn_subject_title'            => $warn_subject_title,
            'warn_body'                     => $warn_body,
            'warn_cc_field'                 => (!filter_var($warn_cc_field, FILTER_VALIDATE_EMAIL) ? null : $warn_cc_field),
            'warn_bcc_field'                => (!filter_var($warn_bcc_field, FILTER_VALIDATE_EMAIL) ? null : $warn_bcc_field)
            )
        );

    update_option( 'w3dev_ban_user_email_templates', $w3dev_ban_templates);

    echo '1';
    wp_die(); // this is required to terminate immediately and return a proper response

}

?>
