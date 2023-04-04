<?php

if ( ! defined('ABSPATH' ) ) exit;

function w3dev_ban_user_options_partial()
{
    global $wp_roles;
    $roles = $wp_roles->get_names();

    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');
    $notifications          = $w3dev_ban_user_class->get_options('notifications');
    $email_template         = $notifications['user_notification'];

    // set default values
    // --
    $default_ban_subject_title              = isset( $notifications['_defaults']['user_notification']['subject_title'] ) ? $notifications['_defaults']['user_notification']['subject_title'] : null;
    $default_ban_body                       = isset( $notifications['_defaults']['user_notification']['body'] ) ? $notifications['_defaults']['user_notification']['body'] : null;
    $default_unban_subject_title            = isset( $notifications['_defaults']['user_notification']['unban_subject_title'] ) ? $notifications['_defaults']['user_notification']['unban_subject_title'] : null;
    $default_unban_body                     = isset( $notifications['_defaults']['user_notification']['unban_body'] ) ? $notifications['_defaults']['user_notification']['unban_body'] : null;
    $default_unban_indefinite_date_tag      = isset( $notifications['_defaults']['user_notification']['unban_indefinite_date_tag'] ) ? $notifications['_defaults']['user_notification']['unban_indefinite_date_tag'] : null;
    $default_warn_subject_title             = isset( $notifications['_defaults']['user_notification']['warn_subject_title'] ) ? $notifications['_defaults']['user_notification']['warn_subject_title'] : null ;
    $default_warn_body                      = isset( $notifications['_defaults']['user_notification']['warn_body'] ) ? $notifications['_defaults']['user_notification']['warn_body'] : null ;

    // set saved values
    // --
    $ban_subject_title              = !empty( $email_template['subject_title'] ) ? $email_template['subject_title'] : $default_ban_subject_title;
    $ban_body                       = !empty( $email_template['body'] ) ? $email_template['body'] : $default_ban_body;
    $ban_cc_field                   = !empty( $email_template['ban_cc_field'] ) ? $email_template['ban_cc_field'] : null;
    $ban_bcc_field                  = !empty( $email_template['ban_bcc_field'] ) ? $email_template['ban_bcc_field'] : null;

    $unban_subject_title            = !empty( $email_template['unban_subject_title'] ) ? $email_template['unban_subject_title'] : $default_unban_subject_title;
    $unban_body                     = !empty( $email_template['unban_body'] ) ? $email_template['unban_body'] : $default_unban_body;
    $unban_indefinite_date_tag      = !empty( $email_template['unban_indefinite_date_tag'] ) ? $email_template['unban_indefinite_date_tag'] : $default_unban_indefinite_date_tag;
    $unban_cc_field                 = !empty( $email_template['unban_cc_field'] ) ? $email_template['unban_cc_field'] : null;
    $unban_bcc_field                = !empty( $email_template['unban_bcc_field'] ) ? $email_template['unban_bcc_field'] : null;

    $warn_subject_title             = !empty( $email_template['warn_subject_title'] ) ? $email_template['warn_subject_title'] : $default_warn_subject_title;
    $warn_body                      = !empty( $email_template['warn_body'] ) ? $email_template['warn_body'] : $default_warn_body ;
    $warn_cc_field                  = !empty( $email_template['warn_cc_field'] ) ? $email_template['warn_cc_field'] : null;
    $warn_bcc_field                 = !empty( $email_template['warn_bcc_field'] ) ? $email_template['warn_bcc_field'] : null;
    ?>

    <div id="js-save-message" class="alert success" style="display: none;">
        <a class="close" href=""><i class="fa fa-2x fa-times" aria-hidden="true"></i></a>
        <i class="fa fa-check" aria-hidden="true"></i> <?php _e('Successfully Saved Changes', 'ban-users'); ?>
    </div>
    <style>
        #js-save-message { 
            background-color: #5db85b;color:#fff; border-radius: 0; margin-left: 0; margin-bottom: 0;
            position: fixed; left: 0; right: 0; top: 0; z-index: 2000; z-index: 110000;
            padding: 15px 30px; line-height: 2em; font-weight: bold;
        }
        #js-save-message a.close { float: right; color: #fff; }
    </style>
      
    <div id="ban-user-container" style="margin-top: 20px;">

        <div style="position:relative;background-image: url(https://ps.w.org/ban-users/assets/banner-772x250.png?rev=1455118); background-size: cover; background-position: center center; background-repeat: no-repeat;height: 200px;margin-left:-20px;margin-right: -20px;margin-top: -20px;margin-bottom: 20px;padding: 30px;">
            
            <div style="position:absolute;bottom: 30px;">
                <h1 style="letter-spacing:2px;margin-bottom:0;background-color:#111;background-color:rgba(0,0,0,0.8);display:inline-block;padding: 12px 20px;line-height:1em;color:#fff;border-radius: 4px;">BAN Users - <span style="letter-spacing:0;color:#e5a238;font-weight:300;">v<?php echo W3DEV_BAN_USERS_PLUGIN_VERSION; ?></span></h1> 
            </div>

        </div>

        <ul id="w3dev-tabs">
            <li><a class="active" data-tab="options" href="javascript:void(0)"><?php _e('Options', 'ban-users'); ?></a></li>
            <li><a data-tab="banned-users" href="javascript:void(0)"><?php _e('Banned Users', 'ban-users'); ?></a></li>
            <li><a data-tab="email-template" href="javascript:void(0)"><?php _e('Email Templates', 'ban-users'); ?></a></li>
            <?php if (W3DEV_BAN_USERS_PREMIUM_VERSION) { ?>
                <li><a data-tab="registration-ban" href="javascript:void(0)"><?php _e('Registration Bans', 'ban-users'); ?></a></li>
            <?php } ?>
            <li><a data-tab="faqs" href="javascript:void(0)"><?php _e('FAQs', 'ban-users'); ?></a></li>
            <?php if (!W3DEV_BAN_USERS_PREMIUM_VERSION) { ?>
                <li class="go-pro"><a data-tab="go-pro" href="javascript:void(0)"><span><?php _e('Go Pro!', 'ban-users'); ?></span></a></li>
            <?php } ?>
        </ul>
        <div class="w3dev-tab active" id="tab-options">

            <form style="padding-top: 15px;">

                <div id="w3dev-js-scrollto-section" class="w3dev-quick-links" style="margin-bottom:10px;">
                    <a href="#w3dev-accessibility-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-low-vision faa-pulse" aria-hidden="true"></i><span><?php _e('Accessibility', 'ban-users'); ?></span></a>
                    <a href="#w3dev-general-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-cog faa-pulse" aria-hidden="true"></i><span><?php _e('General', 'ban-users'); ?></span></a>
                    <a href="#w3dev-ban-options-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-ban faa-pulse" aria-hidden="true"></i><span><?php _e('Banning', 'ban-users'); ?></span></a>
                    <a href="#w3dev-default-messages-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-bullhorn faa-pulse" aria-hidden="true"></i><span><?php _e('Messages', 'ban-users'); ?></span></a>
                    <a href="#w3dev-ipgeo-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-map-marker faa-pulse" aria-hidden="true"></i><span><?php _e('IP/GEO', 'ban-users'); ?></span></a>
                    <a href="#w3dev-notification-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-flag faa-pulse" aria-hidden="true"></i><span><?php _e('Notify', 'ban-users'); ?></span></a>
                    <a href="#w3dev-users-table-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-table faa-pulse" aria-hidden="true"></i><span><?php _e('Users', 'ban-users'); ?></span></a>
                    <a href="#w3dev-warn-options-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-exclamation-triangle faa-pulse" aria-hidden="true"></i><span><?php _e('Warn', 'ban-users'); ?></span></a>
                    <a href="#w3dev-security-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-shield faa-pulse" aria-hidden="true"></i><span><?php _e('Security', 'ban-users'); ?></span></a>
                    <a href="#w3dev-3rdparty-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-plug faa-pulse" aria-hidden="true"></i><span><?php _e('3rd Party', 'ban-users'); ?></span></a>
                    <a href="#w3dev-shortcodes-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-wordpress faa-pulse" aria-hidden="true"></i><span><?php _e('Shortcodes', 'ban-users'); ?></span></a>
                    <a href="#w3dev-conflict-section" class="faa-parent animated-hover"><i class="fa fa-4x fa-bug faa-pulse" aria-hidden="true"></i><span><?php _e('Conflict', 'ban-users'); ?></span></a>
                </div>

                <div id="w3dev-accessibility-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-low-vision" aria-hidden="true"></i><?php _e('Accessibility Options', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div class="mb-10">
                        <input type="checkbox" name="input-enable-accessibility" id="input-enable-accessibility" value="1" <?php echo (!empty($settings['enable_accessibility'])) ? 'checked' : null; ?> /><?php _e('Optimise plugin for accessibility / screen readers (i.e. displays text links instead of icons)', 'ban-users'); ?>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

               <div id="w3dev-general-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-cog" aria-hidden="true"></i><?php _e('General Options', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div id="js-custom-logout" style="margin-bottom: 10px;">
                        <input type="checkbox" name="input-close-panels" id="input-close-panels" value="1" <?php echo (!empty($settings['close_panels'])) ? 'checked' : null; ?> /> Close all setting panels by default
                    </div>
                    <div id="js-custom-logout" style="margin-bottom: 10px;">
                        <input type="checkbox" name="input-custom-logout" id="input-custom-logout" value="1" <?php echo (!empty($settings['custom_logout'])) ? 'checked' : null; ?> /><?php _e("Set custom redirection url after logout (i.e. to a notification page, or back to the site's login url). Especially important if your WordPress setup doesn't use wp-login.php", 'ban-users'); ?>
                    </div>
                    <div id="js-custom-logout-url" style="margin-bottom:10px; <?php echo (!empty($settings['custom_logout'])) ? null : 'display:none;' ?>">
                        <input class="form-control" type="text" name="input-custom-logout-url" id="input-custom-logout-url" value="<?php echo esc_url(stripslashes($settings['custom_logout_url'])); ?>" placeholder="Custom logout URL" /> <span class="help-block" style="color:#999"><?php _e('(i.e. http://mywebsite.com or relative so /about-us)', 'ban-users'); ?></span>
                    </div>

                    <div id="js-banned-login-message" style="margin-bottom: 10px; padding-top: 10px;">
                        <label style="margin-bottom:5px;display:block;"><?php _e('Set Date Format:', 'ban-users'); ?></label>
                        <select class="SlectBox" name="input-date-format" id="input-date-format">
                            <option value="d-m-Y" <?php echo (empty($settings['date_format']) || $settings['date_format'] == 'd-m-Y') ? 'selected="selected"' : null; ?>>DD-MM-YYYY</option>
                            <option value="m-d-Y" <?php echo (!empty($settings['date_format']) && $settings['date_format'] == 'm-d-Y') ? 'selected="selected"' : null; ?>>MM-DD-YYYY</option>
                        </select>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-ban-options-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-ban" aria-hidden="true"></i><?php _e('Ban &amp; Unban Options', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div class="mb-10">
                        <input type="checkbox" name="input-on-ban-change-user-role" id="input-on-ban-change-user-role" value="1" <?php echo (!empty($settings['on_ban_change_user_role'])) ? 'checked' : null; ?> /><?php _e("Change the user's role when banned.", 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-set-banned-user-role" style="margin-bottom:10px; padding-left: 30px; <?php echo (empty($settings['on_ban_change_user_role'])) ? 'display:none;' : null; ?>">
                    <?php
                    $roles = get_editable_roles();
                    echo '<select class="SlectBox" id="input-set-banned-user-role" name="input-set-banned-user-role">';
                    foreach ($roles as $key => $role) {
                        echo '<option '.($settings['set_banned_user_role'] == $key ? 'selected="selected"' : null).' value="' . $key . '">'.$key.'</option>';
                    }
                    echo '</select>';
                    ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-on-unban-change-user-role" id="input-on-unban-change-user-role" value="1" <?php echo (!empty($settings['on_unban_change_user_role'])) ? 'checked' : null; ?> /><?php _e("Change the user's role when unbanned.", 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-set-unbanned-user-role" style="margin-bottom:10px; padding-left: 30px; <?php echo (empty($settings['on_unban_change_user_role'])) ? 'display:none;' : null; ?>">
                    <?php
                    $roles = get_editable_roles();
                    echo '<select class="SlectBox" id="input-set-unbanned-user-role" name="input-set-unbanned-user-role">';
                    foreach ($roles as $key => $role) {
                        echo '<option '.($settings['set_unbanned_user_role'] == $key ? 'selected="selected"' : null).' value="' . $key . '">'.$key.'</option>';
                    }
                    echo '</select>';
                    ?>
                    </div>

                    <div class="mb-10">
                        <input type="checkbox" name="input-enable-support-cpt" id="input-enable-support-cpt" value="1" <?php echo (!empty($settings['enable_support_cpt'])) ? 'checked' : null; ?> /><?php _e("Enable support for CPT (Custom Post Types)", 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-supported-cpt" style="margin-bottom:10px; padding-left: 30px; <?php echo (empty($settings['enable_support_cpt'])) ? 'display:none;' : null; ?>">
                    <?php
                    $all_post_types = get_post_types();
                    echo '<select class="SlectBox" id="input-supported-cpt" name="input-supported-cpt">';
                    foreach ($all_post_types as $key => $post_type) {
                        echo '<option '.($settings['supported_cpt'] == $key ? 'selected="selected"' : null).' value="' . $key . '">'.$post_type.'</option>';
                    }
                    echo '</select>';
                    ?>
                    </div>

                    <div class="mb-10">
                        <input type="checkbox" name="input-change-status" id="input-change-status" value="1" <?php echo (!empty($settings['change_posts_status'])) ? 'checked' : null; ?> /><?php _e("Change the user's posts status when banned (i.e. select draft to hide posts from showing on blog).", 'ban-users'); ?>
                    </div>
                    <div id="js-post-status" style="margin-bottom:10px; padding-left: 30px; <?php echo (empty($settings['change_posts_status'])) ? 'display:none;' : null; ?>">
                    <?php
                    $all_statuses = get_post_stati();
                    echo '<select class="SlectBox" id="input-post-status" name="input-post-status">';
                    foreach ($all_statuses as $key => $status) {
                        echo '<option '.($settings['post_status'] == $key ? 'selected="selected"' : null).' value="' . $key . '">'.$status.'</option>';
                    }
                    echo '</select>';
                    ?>
                    </div>

                    <div id="js-force-logout" class="mb-10">
                        <input type="radio" name="input-logged-in-options" id="input-force-logout" value="1" <?php echo (!empty($settings['force_logout'])) ? 'checked' : null; ?> /><?php _e('If already logged in when banned, force logout.', 'ban-users'); ?>
                    </div>
                    <div id="js-display-message" class="mb-10">
                        <input type="radio" name="input-logged-in-options" id="input-display-message" value="1" <?php echo (!empty($settings['display_message'])) ? 'checked' : null; ?> /><?php _e('If already logged in when banned, display message to user.', 'ban-users'); ?>
                    </div>
                    
                    <div id="js-display-message-extras" style="<?php echo (empty($settings['display_message'])) ? 'display:none;' : null; ?>">

                        <div style="margin-bottom: 10px; padding-left: 30px;">
                            <input type="checkbox" disabled="disabled" value="1" checked="checked" /> <?php _e('Display message in back end of website', 'ban-users'); ?>
                        </div>
                        <div style="margin-bottom: 10px; padding-left: 30px;">
                            <input type="checkbox" name="input-frontend-banned-notification" id="input-frontend-banned-notification" value="1" <?php echo (!empty($settings['frontend_banned_notification'])) ? 'checked' : null; ?> /> <?php _e('Display message on front end of website in a dialog box', 'ban-users'); ?>
                        </div>
                        <div style="margin-bottom: 10px; padding-left: 30px;">
                            <input type="checkbox" name="input-frontend-notification-force-logout" id="input-frontend-notification-force-logout" value="1" <?php echo (!empty($settings['frontend_notification_force_logout'])) ? 'checked' : null; ?> /> <?php _e('Force logout and redirect (after diplaying message in dialog box; when applicable)', 'ban-users'); ?>
                        </div>
                        <div style="margin-bottom: 10px; padding-left: 30px;">
                            <input type="checkbox" name="input-frontend-notification-hide" id="input-frontend-notification-hide" value="1" <?php echo (!empty($settings['frontend_notification_force_logout'])) ? 'disabled' : null; ?> <?php echo (!empty($settings['frontend_notification_hide'])) ? 'checked' : null; ?> /> <?php _e('Only show diaglog box once (uses cookie - set to 30days)', 'ban-users'); ?>
                        </div>

                    </div>
                    <div id="js-scramble-banned-users-password" class="mb-10">
                        <input type="checkbox" name="input-scramble-banned-users-password" id="input-scramble-banned-users-password" value="1" <?php echo (!empty($settings['scramble_banned_users_password'])) ? 'checked' : null; ?> /> <?php _e('Scramble banned user\'s password when banned.', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-set-spammer-option" class="mb-10">
                        <input type="checkbox" name="input-set-spammer-option" id="input-set-spammer-option" value="1" <?php echo (!empty($settings['set_spammer_option'])) ? 'checked' : null; ?> /> <?php _e('Set WordPress option \'Spammer\' for banned users.', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-unset-spammer-option" class="mb-10">
                        <input type="checkbox" name="input-unset-spammer-option" id="input-unset-spammer-option" value="1" <?php echo (!empty($settings['unset_spammer_option'])) ? 'checked' : null; ?> /> <?php _e('Remove WordPress option \'Spammer\' when unbanning users.', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-hide-banned-users-comments" class="mb-10">
                        <input type="checkbox" name="input-hide-banned-users-comments" id="input-hide-banned-users-comments" value="1" <?php echo (!empty($settings['hide_banned_users_comments'])) ? 'checked' : null; ?> /> <?php _e('Hide banned users\' comments on front end.', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-disable-password-reset-banned-users" class="mb-10">
                        <input type="checkbox" name="input-disable-password-reset-banned-users" id="input-disable-password-reset-banned-users" value="1" <?php echo (!empty($settings['disable_password_reset_banned_users'])) ? 'checked' : null; ?> /> <?php _e('Disable password reset for banned users.', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div id="js-ban-email" class="mb-10">
                        <input type="checkbox" name="input-ban-email" id="input-ban-email" value="1" <?php echo (!empty($settings['ban_email'])) ? 'checked' : null; ?> /> <?php _e('Enable email notifications when banning or unbanning users', 'ban-users'); ?>
                    </div>
                    <div id="js-ban-email-default" class="mb-10">
                        <input type="checkbox" name="input-ban-email-default" id="input-ban-email-default" value="1" <?php echo (!empty($settings['ban_email_default'])) ? 'checked' : null; ?> /> <?php _e('Include option for custom message in email, and option to set ban duration when banning a user (i.e. date picker, day, week, month etc..)', 'ban-users'); ?>
                    </div>

                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

               <div id="w3dev-default-messages-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-bullhorn" aria-hidden="true"></i><?php _e('Default Messages', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div id="js-ban-email-message" style="margin-bottom:10px; <?php echo (!empty($settings['ban_email_default'] )) ? 'display:none;' : null; ?>">
                        <label style="margin-bottom:5px;display:block;"><?php _e('Default reason for banning a user:', 'ban-users'); ?></label>
                        <input type="text" class="form-control" name="input-ban-email-message" id="input-ban-email-message" value="<?php echo $settings['ban_email_default_message']; ?>" placeholder="<?php _e('Generic reason for ban', 'ban-users'); ?>" />
                    </div>
                    <div id="js-banned-login-message" class="mb-10">
                        <label style="margin-bottom:5px;display:block;"><?php _e('Message to display when a banned user attempts to login:', 'ban-users'); ?><br />
                        <span style="font-style: italic; color:#999"><strong><?php _e('Note:', 'ban-users'); echo ' </strong>'; _e('following tags can be used if required: %%reason%% and %%unban_date%%', 'ban-users'); ?></span></label>
                        <input class="form-control" type="text" name="input-banned-login-message" id="input-banned-login-message" value="<?php echo esc_html(stripslashes($settings['banned_login_message'])); ?>" placeholder="<?php _e('Your account has been suspended. Reason: %%reason%% This ban will be lifted: %%unban_date%%', 'ban-users'); ?>" />
                    </div>

                    <div class="mb-10">
                        <label style="margin-bottom:5px;display:block;"><?php _e('Message to display when user has been banned, whilst logged in, and attempts to access restricted content:', 'ban-users'); ?><br />
                        <span style="font-style: italic; color:#999"><strong><?php _e('Note:', 'ban-users'); echo ' </strong>'; _e('following tags can be used if required: %%reason%% and %%unban_date%%', 'ban-users'); ?></span></label>
                        <input class="form-control" type="text" name="input-custom-message" id="input-custom-message" value="<?php echo esc_html(stripslashes($settings['custom_message'])); ?>" placeholder="<?php _e('Your account has been suspended. Reason: %%reason%% This ban will be lifted: %%unban_date%%', 'ban-users'); ?>" />
                    </div>

                    <div id="js-default-ban-reason" class="mb-10" style="<?php echo (empty($settings['ban_email_default'] )) ? 'display:none;' : null; ?>">
                        <label style="margin-bottom:5px;display:block;"><?php _e("Default 'reason' for banning a user:", 'ban-users'); ?><br />
                        <span style="font-style: italic; color:#999"><strong><?php _e('Note:', 'ban-users'); echo ' </strong>'; _e('this default value will be inserted into the ban user modal/popup when sending a custom message', 'ban-users'); ?></span></label>
                        <input class="form-control" type="text" name="input-default-ban-reason" id="input-default-ban-reason" value="<?php echo !empty($settings['default_ban_reason']) ? esc_html(stripslashes($settings['default_ban_reason'])) : null; ?>" placeholder="<?php _e('i.e. Because you have broken our terms and conditions.', 'ban-users'); ?>" />
                    </div>

                    <div id="js-default-warn-reason" class="mb-10" style="<?php echo (empty($settings['warn_user'] )) ? 'display:none;' : null; ?>">
                        <label style="margin-bottom:5px;display:block;"><?php _e("Default 'reason' for warning a user:", 'ban-users'); ?><br />
                        <span style="font-style: italic; color:#999"><strong><?php _e('Note:', 'ban-users'); echo ' </strong>'; _e('this default value will be inserted into the warn user modal/popup when sending a custom message', 'ban-users'); ?></span></label>
                        <input class="form-control" type="text" name="input-default-warn-reason" id="input-default-warn-reason" value="<?php echo !empty($settings['default_warn_reason']) ? esc_html(stripslashes($settings['default_warn_reason'])) : null; ?>" placeholder="<?php _e('i.e. Failure to adhere to our terms and conditions will result in your immediate suspension. This is your first and final warning.', 'ban-users'); ?>" />
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>


                <div id="w3dev-ipgeo-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-map-marker" aria-hidden="true"></i><?php _e('IP/Geodata Options', 'ban-users'); echo ' <span style="color:#d8534f">'; _e('(Premium Feature)', 'ban-users'); ?></span><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div class="mb-10">
                        <input type="checkbox" disabled /><?php _e('Capture IP/Geodata during user login', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" disabled /><?php _e('Display IP/Geodata column in users table', 'ban-users'); ?>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>


                <div id="w3dev-notification-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-flag" aria-hidden="true"></i><?php _e('Notification Options', 'ban-users'); echo ' <span style="color:#d8534f">'; _e('(Premium Feature)', 'ban-users'); ?></span><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div id="js-send-notification-new-post" class="mb-10">
                        <input type="checkbox" disabled value="1" /><?php _e('Send admin(s) an email notification when user publishes first post.', 'ban-users'); ?>
                    </div>
                    <div id="js-notification-email-addresses" class="mb-10">
                        <p style="margin:0;padding:0 0 10px 0;"><?php _e('Email addresss(es) to receive notification when user publishes first post:', 'ban-users'); ?></p>
                        <textarea style="width: 350px;height: 60px;" disabled placeholder="<?php _e('Comma seperated list of email addresses', 'ban-users'); ?>"></textarea>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-users-table-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-table" aria-hidden="true"></i><?php _e('Users Table Options', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div class="mb-10">
                        <input type="checkbox" name="input-users-tbl-data-column" id="input-users-tbl-data-column" value="1" <?php echo (!empty($settings['users_tbl_data_column'])) ? 'checked' : null; ?> /><?php _e('Display banned column in users table showing login status', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-users-tbl-row-highlighted" id="input-users-tbl-row-highlighted" value="1" <?php echo (!empty($settings['users_tbl_row_highlighted'])) ? 'checked' : null; ?> /><?php _e('Highlight banned users in users table using red background colour', 'ban-users'); ?>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-warn-options-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-exclamation-triangle" aria-hidden="true"></i><?php _e('Warn Options', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div id="js-warn-user" class="mb-10">
                        <input type="checkbox" name="input-warn-user" id="input-warn-user" value="1" <?php echo (!empty($settings['warn_user'])) ? 'checked' : null; ?> /><?php _e('Enable warn user feature', 'ban-users'); ?>
                    </div>
                    <div id="js-warn-user-reason" style="margin-bottom: 10px; <?php echo (!empty($settings['warn_user'])) ? null : 'display:none;'; ?>">
                        <input type="checkbox" name="input-warn-user-reason" id="input-warn-user-reason" value="1" <?php echo (!empty($settings['warn_user_reason'])) ? 'checked' : null; ?> /><?php _e('Include option for custom message in email when warning a user', 'ban-users'); ?>
                    </div> 
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-security-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-shield" aria-hidden="true"></i><?php _e('Security Settings', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <div class="mb-10">
                        <input type="checkbox" name="input-enable-admin-override" value="1" <?php echo (!empty($settings['security']['enable_admin_override'])) ? 'checked' : null; ?> /><?php _e("Always allow super admins/admins to perform ban/unban tasks regardless of any restrictions enabled below", 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-security-set-moderator-roles" value="1" <?php echo !empty($settings['security']['set_moderator_roles']) ? 'checked' : null;  echo '/>'; _e('Only allow users in the following roles to moderate other user accounts', 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div class="mb-10" style="padding-left: 30px;">
                        <select class="SlectBox" multiple="multiple" name="input-security-moderator-roles">
                            <?php
                            $moderator_roles = !empty($settings['security']['moderator_roles']) ? $settings['security']['moderator_roles'] : array();
                            $roles = get_editable_roles();

                            if (!empty($roles)) {
                                foreach ($roles as $key => $value) {
                                    echo '<option '.(in_array($key, $moderator_roles)? 'selected' : null).' value="'.$key.'">'.ucfirst($key).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-security-set-moderated-roles" value="1" <?php echo (!empty($settings['security']['set_moderated_roles'])) ? 'checked' : null; ?> /><?php _e("Only allow the following roles to be moderated (i.e. can be banned)", 'ban-users'); ?> <span style="position: relative;top:-1px;" class="bs-label label-success"><?php _e('NEW', 'ban-users'); ?></span>
                    </div>
                    <div style="margin-bottom:10px; padding-left: 30px;">
                        <select class="SlectBox" multiple="multiple" name="input-security-moderated-roles">
                            <?php
                            $moderated_role = !empty($settings['security']['moderated_roles']) ? $settings['security']['moderated_roles'] : array();
                            $roles = get_editable_roles();

                            if (!empty($roles)) {
                                foreach ($roles as $key => $value) {
                                    echo '<option '.(in_array($key, $moderated_role) ? 'selected' : null).' value="'.$key.'">'.ucfirst($key).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div style="border-bottom: 1px solid #ddd;margin-bottom: 15px;padding-top: 15px;">
                        <h4 style="padding:0;margin:0;padding-bottom: 5px;"><?php _e('ACCOUNT SECURITY', 'ban-users'); ?> <?php echo ' <span style="color:#d8534f">'; _e('(Premium Feature)', 'ban-users'); ?></span></h4>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" value="0" disabled="disabled" /> <?php _e('Enable automated notifications for successful user logins when accessed from a new IP Address', 'ban-users'); ?>
                    </div>

                    <div style="border-bottom: 1px solid #ddd;margin-bottom: 15px;padding-top: 15px;">
                        <h4 style="padding:0;margin:0;padding-bottom: 5px;"><?php _e('SPAM PREVENTION', 'ban-users'); ?> <?php echo ' <span style="color:#d8534f">'; _e('(Premium Feature)', 'ban-users'); ?></span></h4>
                    </div>

                    <div class="mb-10">
                        <input type="checkbox" value="0" disabled="disabled" /> <?php _e('Deny user registration if user agent can\'t be determined', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" value="0" disabled="disabled" /> <?php _e('Deny user registration if IP Address can\'t be determined', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" value="0" disabled="disabled" /> <?php _e('Deny login if user agent can\'t be determined', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" value="0" disabled="disabled" /> <?php _e('Deny login if IP Address can\'t be determined', 'ban-users'); ?>
                    </div>

                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-3rdparty-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-plug" aria-hidden="true"></i><?php _e('3rd Party Plugin Support', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <p><?php _e('There are a number of awesome plugins available which extend the membership capabilities of WordPress. If you use any of the following plugins please tick the checkboxes below to enable support for them.', 'ban-users'); ?> </p>

                    <div class="mb-10">
                        <input type="checkbox" name="input-ext-ultimate-member" id="input-ext-ultimate-member" value="1" <?php echo (!empty($settings['extensions']['ultimate_member'])) ? 'checked' : null; ?> /><?php _e('Ultimate Member Plugin', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input disabled type="checkbox" name="input-ext-ultimate-member" id="input-ext-buddypress" value="1" checked="checked" /><?php _e('BuddyPress Plugin (by default)', 'ban-users'); ?>
                    </div> 
                    <div class="mb-10">
                        <input disabled type="checkbox" name="input-ext-s2member" id="input-ext-s2member" value="1" /><?php _e('s2member Plugin (Coming Soon)', 'ban-users'); ?>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

               <div id="w3dev-shortcodes-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-wordpress" aria-hidden="true"></i><?php _e('Plugin Shortcodes', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>

                    <p class="w3dev-advisory"><strong><?php _e('Please note: These shortcodes are included for developers.', 'ban-users'); ?></strong><br><?php _e('They are provided to developers to help them integrate the features of this plugin into their own bespoke solution. They are not required for typical use of this plugin.', 'ban-users'); ?></p>

                    <div class="mb-10">
                        <code>[w3dev-is-user-banned user_id="0"]</code> <?php _e('Check if user is banned (by user id); if banned shortcode will output message', 'ban-users'); ?>
                        <?php //do_shortcode( '[w3dev-is-user-banned user_id="7"]' ); ?>
                    </div>
                    <div class="mb-10">
                        <code>[w3dev-is-user-banned email="me@myaddress.com"]</code> <?php _e('Check if user is banned (by email address); if banned shortcode will output message', 'ban-users'); ?>
                        <?php //do_shortcode( '[w3dev-is-user-banned user_id="7"]' ); ?>
                    </div>
                    <div class="mb-10">
                        <code>[w3dev-is-user-banned username="myusername"]</code> <?php _e('Check if user is banned (by username); if banned shortcode will output message', 'ban-users'); ?>
                        <?php //do_shortcode( '[w3dev-is-user-banned user_id="7"]' ); ?>
                    </div>
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="w3dev-conflict-section" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                    <h3><i class="w3dev-icon fa fa-2x fa-bug" aria-hidden="true"></i><?php _e('Conflict Management', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                    <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                    <p class="mb-20"><?php _e("If you want to manually load any of the libraries required by this plugin then simply check the correspnding tick box(es) below to disable them from autoloading. But remember, the plugin won't function correctly if these libraries aren't all available. So I recommend you keep all tick boxes below unchecked unless you know what you are doing.", 'ban-users'); ?></p>

                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-fa" id="input-autoload-fa" value="1" <?php echo (!empty($settings['disable_autoload']['fa'])) ? 'checked' : null; ?> /><?php _e('Disable js/css autoload Font Awesome (ver 4.7.0)', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-jq-confirm" id="input-autoload-jq-confirm" value="1" <?php echo (!empty($settings['disable_autoload']['jq_confirm'])) ? 'checked' : null;  echo '/>'; _e('Disable js/css autoload jQuery Confirm (ver 3.3.2)', 'ban-users'); echo ' <span style="position: relative;top:-1px;" class="bs-label label-warning">'; _e('UPDATED', 'ban-users'); ?></span>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-datatables" id="input-autoload-datatables" value="1" <?php echo (!empty($settings['disable_autoload']['datatables'])) ? 'checked' : null; ?> /><?php _e('Disable js/css autoload DataTables (ver 1.10.16)', 'ban-users'); ?>
                    </div> 
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-notify" id="input-autoload-notify" value="1" <?php echo (!empty($settings['disable_autoload']['notify'])) ? 'checked' : null; ?> /><?php _e('Disable js/css autoload Notify (ver 0.4.1)', 'ban-users'); ?>
                    </div> 
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-selectric" id="input-autoload-selectric" value="1" <?php echo (!empty($settings['disable_autoload']['selectric'])) ? 'checked' : null; ?> /><?php _e('Disable js/css autoload Selectric (ver 1.13.0)', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-flatpickr" id="input-autoload-flatpickr" value="1" <?php echo (!empty($settings['disable_autoload']['flatpickr'])) ? 'checked' : null; ?> /><?php _e('Disable js/css autoload Flatpickr (ver 2.0.5)', 'ban-users'); ?>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-faanimation" id="input-autoload-faanimation" value="1" <?php echo (!empty($settings['disable_autoload']['faanimation'])) ? 'checked' : null; ?>><?php _e('Disable js/css autoload Font Awesome Animation (ver 0.0.10)', 'ban-users'); ?>
                    </div>
                    <!--
                    <div class="mb-10">
                        <input type="checkbox" name="input-autoload-alertify" id="input-autoload-alertify" value="1" <?php echo (!empty($settings['disable_autoload']['alertify'])) ? 'checked' : null; ?> /> Disable js/css autoload Alertify (ver 1.8.0)
                    </div>
                    -->
                    </div>
                    <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                </div>

                <div id="form-actions" style="margin-bottom: 10px;">
                    <a id="w3dev-save-ban-user-settings" class="btn btn-success" href="javascript:void(0)"><?php _e('Save Options', 'ban-users'); ?></a>
                </div>
            </form>
<!--
            <style>
                .w3dev-settings-section { border:1px solid #ddd; padding: 20px; padding-top: 0; margin-bottom: 30px; }
                .w3dev-settings-section h3 { 
                    margin-left: -20px;margin-right: -20px;padding: 20px!important;border-bottom: 1px solid #ddd;
                    background-color: #f9f9f9; margin-bottom: 20px;
                }
                .w3dev-settings-section h3 i { color:#8a6d3b;float:left;position:relative;top:-9px;margin-right: 10px; }
                .mb-10 { margin-bottom: 10px; }
                .mb-20 { margin-bottom: 20px; }
            </style>
-->
        </div>
        <div class="w3dev-tab hidden" id="tab-banned-users">

            <div style="margin-top: 15px;" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">

                <h3><i class="w3dev-icon fa fa-2x fa-ban" aria-hidden="true"></i> Banned <a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                <div style="margin-bottom: 60px;">
                <table id="w3dev-table-banned-users" class="data-table w3dev-compact-table" style="border:0;">
                <thead>
                <tr>
                    <th><?php _e('User Login (ID)', 'ban-users'); ?></th>
                    <th><?php _e('Email', 'ban-users'); ?></th>
                    <th><?php _e('Date banned', 'ban-users'); ?></th>
                    <th><?php _e('Reason', 'ban-users'); ?></th>
                    <th><?php _e('Unban', 'ban-users'); ?></th>
                </tr>
                </thead>
                
                <?php
                global $wpdb;
                $db_table_name = $wpdb->prefix . 'usermeta';

                if ( $wpdb->get_var( "SHOW TABLES LIKE '$db_table_name'" ) != $db_table_name ) { ?>

                    <div style="margin-bottom: 25px;"><p><span style="color:#d8534f"><strong>ERROR: The required WordPress Table '<?php echo $db_table_name; ?>' does not exist.</strong></span><br />
                    Please deactivate and reactive this plugin and ensure your Wordpress database user has sufficient rights to create tables.</p></div>
                
                <?php
                } else {

                    /*
                    $users = get_users(array(
                        'meta_key' => 'wp_w3dev_user_banned'
                    ));
                    */
                    
                    $banned_users = $wpdb->get_results( "
                            SELECT ".$wpdb->prefix."users.ID as user_id, ".$wpdb->prefix."users.user_login as user_login, ".$wpdb->prefix."users.user_email as user_email, usermeta2.meta_value as date_banned, usermeta3.meta_value as reason FROM $db_table_name
                            LEFT JOIN ".$wpdb->prefix."users ON ".$wpdb->prefix."users.ID = ".$db_table_name.".user_id
                            LEFT JOIN ".$wpdb->prefix."usermeta as usermeta2 ON ".$wpdb->prefix."usermeta.user_id = usermeta2.user_id AND usermeta2.meta_key = '".$wpdb->prefix."w3dev_user_banned_date' 
                            LEFT JOIN ".$wpdb->prefix."usermeta as usermeta3 ON ".$wpdb->prefix."usermeta.user_id = usermeta3.user_id AND usermeta3.meta_key = '".$wpdb->prefix."w3dev_user_banned_reason' 
                            WHERE ".$wpdb->prefix."usermeta.meta_key = '".$wpdb->prefix."w3dev_user_banned' AND
                            ".$wpdb->prefix."usermeta.meta_value = 1 
                            ORDER BY user_id ASC");

                } ?>

                <tbody>
                <?php
                if ( !empty($banned_users) && is_array($banned_users) )
                    foreach ($banned_users as $banned_user)
                        echo '<tr id="row-'.$banned_user->user_id.'">
                                <td>'.$banned_user->user_login.' (<a href="'.get_edit_user_link( $banned_user->user_id ).'">'.$banned_user->user_id.'</a>)</td>
                                <td>'.$banned_user->user_email.'</td>
                                <td>'.$banned_user->date_banned.'</td>
                                <td><span class="w3dev-reason">'.substr($banned_user->reason, 0, 50).'...</span></td>
                                <td>
                                    <a class="js-unban-user" data-user-id="'.$banned_user->user_id.'" href="javascript:void(0)">
                                        <span class="fa-stack fa-lg" aria-hidden="true">
                                        <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                                        <i class="fa fa-ban fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                        </span>
                                    </a>            
                                </td>
                            </tr>';
                ?>
                </tbody>
                </table>
                </div>
                </div>
                <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

                <style>
                table.dataTable {
                    margin-bottom: 10px;
                }
                table.dataTable thead th, 
                table.dataTable thead td,
                table.dataTable tbody th, 
                table.dataTable tbody td {
                    padding: 8px 0!important;
                    text-align: left;
                }
                .dataTables_info { color: #aaa!important; }

                .w3dev-compact-table { border-collapse: collapse; }
                .w3dev-compact-table tr th,
                .w3dev-compact-table tr td {
                    border-bottom: 1px solid #ccc;
                }
                .action-group { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
                .action-group.reversed { border-top: 0; margin-top:0; padding-top:0; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #ddd; }
                </style>

            </div>
            
        </div>
        <div class="w3dev-tab hidden" id="tab-email-template">

            <div style="margin-top: 15px;" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">
                <h3><i class="w3dev-icon fa fa-2x fa-cog" aria-hidden="true"></i><?php _e('Email Options:', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                <form>
                    <div id="js-banned-login-message" style="margin-bottom: 30px;">
                        <label style="margin-bottom:10px;display:block;"><?php _e("Default message to show if user banned indefinitely and %%unban_date%% tag included in 'BAN User email template':", 'ban-users'); ?></label>
                        <input class="form-control" type="text" name="input-unban-indefinite-date-tag" id="input-unban-indefinite-date-tag" value="<?php echo $unban_indefinite_date_tag; ?>" />
                    </div>
                </form>
                </div>
                <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

            </div>

            <div style="margin-top: 15px;" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">

                <h3><i class="w3dev-icon fa fa-2x fa-code" aria-hidden="true"></i><?php _e('BAN User email template:', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>
                <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                <p><?php _e('If user email notification is enabled, then this email template will be used when notifying users that they have been banned.', 'ban-users'); ?></p>

<style>
    .tpl-tags {
        color: #ce4844;
        border: 2px solid #ddd;padding: 5px 12px;
        border-radius: 24px;
        margin-bottom: 10px;
        display: inline-block;
        margin-left: 0px;
        margin-right: 5px;
    }
</style>

                
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Subject Title:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-ban-user-subject-title" name="input-ban-user-subject-title" size="30" value="<?php echo $ban_subject_title ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Cc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-ban-user-cc-field" name="input-ban-user-cc-field" size="30" value="<?php echo $ban_cc_field; ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Bcc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-ban-user-bcc-field" name="input-ban-user-bcc-field" size="30" value="<?php echo $ban_bcc_field; ?>"/>
                </p>        

                <?php 
                wp_editor( $ban_body, 'ban_editor', $settings = array(
                    'textarea_name'     => 'post_content', 
                    'teeny'             => true,
                    'media_buttons'     => false,
                    'quicktags'         => false,
                    'editor_height'     => 350
                    )); ?>
                <p>
                    <span style="color:#ce4844;display:block;margin-bottom:6px;font-weight: bold;"><?php _e('Template TAGS:', 'ban-users'); ?></span>
                    <?php 
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%reason%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%unban_date%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%username%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%first_name%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%last_name%% </span>'; 
                    ?>
                </p>
                </div>
                <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

            </div>

            <div style="margin-top: 15px;" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">

                <h3><i class="w3dev-icon fa fa-2x fa-code" aria-hidden="true"></i><?php _e('UnBAN User email template:', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                <p><?php _e('If user email notification is enabled, then this email template will be used when notifying users that they have been unbanned.', 'ban-users'); ?></p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Subject Title:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-unban-user-subject-title" name="input-unban-user-subject-title" size="30" value="<?php echo $unban_subject_title ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Cc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-unban-user-cc-field" name="input-unban-user-cc-field" size="30" value="<?php echo $unban_cc_field; ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Bcc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-unban-user-bcc-field" name="input-unban-user-bcc-field" size="30" value="<?php echo $unban_bcc_field; ?>"/>
                </p>

                <?php   
                wp_editor( $unban_body, 'unban_editor', $settings = array(
                    'textarea_name'     => 'post_content', 
                    'teeny'             => true,
                    'media_buttons'     => false,
                    'quicktags'         => false,
                    'editor_height'     => 350
                    ));
                ?>
                <p>
                    <span style="color:#ce4844;display:block;margin-bottom:6px;font-weight: bold;"><?php _e('Template TAGS:', 'ban-users'); ?></span>
                    <?php 
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%username%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%first_name%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%last_name%% </span>'; 
                    ?>
                </p>
                </div>
                <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

            </div>

            <div style="margin-top: 15px;" class="w3dev-settings-section <?php echo (!empty($settings['close_panels'])) ? 'closed' : null; ?>">

                <h3><i class="w3dev-icon fa fa-2x fa-code" aria-hidden="true"></i><?php _e('Warn User email template:', 'ban-users'); ?><a class="w3dev-toggle-content" style="float:right"><i class="fa fa-caret-<?php echo (!empty($settings['close_panels'])) ? 'up' : 'down'; ?>"" aria-hidden="true"></i></a></h3>

                <div class="w3dev-content" <?php echo (!empty($settings['close_panels'])) ? 'style="display:none;"' : null; ?>>
                <p><?php _e('This template will be used when sending a warning to users for a possible future ban.', 'ban-users'); ?></p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Subject Title:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-warn-user-subject-title" name="input-warn-user-subject-title" size="30" value="<?php echo $warn_subject_title; ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Cc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-warn-user-cc-field" name="input-warn-user-cc-field" size="30" value="<?php echo $warn_cc_field; ?>"/>
                </p>
                <p>
                    <label style="margin-bottom:5px;display:block;font-size:1.2em;"><?php _e('Bcc:', 'ban-users'); ?></label>
                    <input class="form-control" type="text" id="input-warn-user-bcc-field" name="input-warn-user-bcc-field" size="30" value="<?php echo $warn_bcc_field; ?>"/>
                </p>
                <?php   
                wp_editor( $warn_body, 'warn_editor', $settings = array(
                    'textarea_name'     => 'post_content', 
                    'teeny'             => true,
                    'media_buttons'     => false,
                    'quicktags'         => false,
                    'editor_height'     => 350
                    ));
                ?>
                <p>
                    <span style="color:#ce4844;display:block;margin-bottom:6px;font-weight: bold;"><?php _e('Template TAGS:', 'ban-users'); ?></span>
                    <?php 
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%reason%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%username%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%first_name%% </span>';
                    echo '<span class="tpl-tags" style="color:#ce4844"> %%last_name%% </span>'; 
                    ?>
                </p>
                </div>
                <a class="w3dev-back-to-top" href="body"><?php _e('Back to Top', 'ban-users'); ?></a>

            </div>

            <a id="w3dev-save-ban-email-template" class="btn btn-success" href="javascript:void(0)"><?php _e('Save Template', 'ban-users'); ?></a>

        </div>
        <div class="w3dev-tab hidden" id="tab-faqs">

            <p><span class="text-primary " style="font-weight: bold; font-size: 1.4em;"><?php _e('FAQs', 'ban-users'); ?></span></p>

            <ul>
                <li class="question"><span>Q.</span> <?php _e("What does this plugin do?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("The BAN User plugin enables WordPress administrators to disable (aka BAN, suspend..) specific user accounts. Banning a user prevents them from being able to login to their WordPress account until an administrator reinstates their access. You can prevent a user from logging in indefinately, or until a specified date. There are a number of configurable options, including sending the user an email notification to inform them they have been banned from the website. There is also an email template for notifying a user when their login access has been reinstated (aka unbanned).", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I simply want the ability to BAN users and prevent them from logging in. What basic options do I need to set?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("The plugin by default allows you to BAN and UnBAN user accounts, and to force users to logout when banned. It also comes pre-configured with the email notification templates. Other options are available from the Plugin's option settings.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When banning or unbanning users I want to send them a custom email notification.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("This feature is enabled by default. To customise the emails simply click on the 'Email Templates' tab and then edit the subject titles and email body. There are currently two email templates. The 'BAN user notification template', and the 'UnBAN user notification template'. Both of which have a configurable subject title. If you have enabled the 'Require unique reason for ban' option then you will need to include the tag '%%reason%%' within the body of the 'BAN user notification template'. This tag will then be replaced with the text you enter when banning a user.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want to clearly highlight users who have been banned in the users table. ", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("By default banned users are highlighted in the users table by a red circle in the BANned column. Users who have been banned are also highlighted by a red row. You can disable these display options by editing the plugin options (click the Options tab).", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When banning a user, I want to include the reason why they have been banned in their email notification.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("To include the reason for banning the user in the notification email, ensure the option 'Require unique reason for ban' has been checked. Also make sure you have included the tag '%%reason%%' within the body of the 'BAN user notification template'. This tag will then be replaced with the text you enter when banning a user.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When a user has been banned from the site whilst still logged in, I want them to see a message.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("To display a message to logged in users when banned, first uncheck the plugin option 'If already logged in when banned, force logout'. This will then reveal another option called 'If already logged in when banned, display message to user'. Make sure this option is checked. A text input will then appear allowing you to set a short message (e.g. You have been banned from the website).", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When a user has been banned from the site whilst still logged in, I want to redirect them to the default WP login page", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("To automatically log a banned user out and redirect them to the default login page you will need to ensure the plugin's option 'If already logged in when banned, display message to user' has been unchecked. You will then see another option called 'If already logged in when banned, force logout'. Simply check this to enable this feature.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When a user has been banned from the site whilst still logged in, I want to redirect them to a custom url.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("To automatically log a banned user out and redirect them to a custom url you will need to ensure the plugin's option 'If already logged in when banned, display message to user' has been unchecked. You will then see another option called 'If already logged in when banned, force logout'. Simply check this to enable this feature. To then set a custom url instead of redirecting banned users to the login page, click the 'Set custom logout URL' and enter a valid url in the text field that appears.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("When banning a user I want to change all their posts to a different status (i.e. to hide all their published content).", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("Often the reason for banning a user from your website is they've posted content that breaches the site's policy. The BAN User plugin allows you to change the user's posts to a different status (i.e. draft) when banning them. To enable this feature click the 'Change user's posts statuses when banning user (i.e. to hide posts from showing on blog)' checkbox on the options tab. A dropdown list will then appear allowing you to select the preferred status you wish to use. ", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I only want to BAN a user for a week.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("The BAN User plugin allows you to specify how long you want to BAN the user for. The date is set using a datepicker which is available on the users table and appears as a calendar icon when hovering over the username. To enable the datepicker you must first ensure the plugin's option 'Allow user to be unbanned automatically when a date is reached.' has been checked.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want to BAN a user from logging on during certain hours of the day.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("Unfortunately this feature isn't currently available, but in plan for a future release of the plugin.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want the system to automatically reinstate users' login access when their banned period has expired.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("Once a banned user's banned date has elapsed they will automatically be able to log back in to the system. No administrative action is required.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want to customise the BANned and UnBANed email templates.", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("From the plugin's option's page click on the 'Email Templates' tab. You should then see two sections for each corresponding email template. One for the 'BAN User email notification template' and another for the 'UnBAN User email notification template'. Within each section is the subject title and the message body. Important, please make sure you include the tag %%reason%% within the message body of the ban user template if you've enabled the option 'Require unique reason for ban'.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want to ban a user how do I do this?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("There are two ways (and places!) in which you can ban a user (i.e. disable their account). Either by accessing the user's Profile page, or using the WordPress admin Users page. To BAN a user from their profile page, simply scroll to the bottom of their profile and tick the Ban User checkbox. To BAN a user from the Users page, navigate to the Users page and then find the corresponding user you wish to BAN. Once found hover your mouse over their username and some text links should appear below their username. To disable their account click the link 'Ban'. After a brief moment the link should change to 'Unban' and depending on the options you've set for the plugin the users row should go red and the BANned column should now display a red circle.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I want to unban a user how do I do this?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("There are two ways (and places!) in which you can unban a user (i.e. reinstate their account). Either by accessing the user's Profile page, or using the WordPress admin Users page. To UnBAN a user from their profile page, simply scroll of the bottom of their profile and untick the Ban User checkbox. To UnBAN a user from the Users page, navigate to the Users page and then find the corresponding user you wish to UnBAN. Once found hover your mouse over their username and some text links should appear below their username. To reinstate their account click the link 'Unban'. After a brief moment the link should change to 'Ban' and depending on the options you've set for the plugin the users row should return to white and the BANned column should now display a green circle.", 'ban-users'); ?></li>
                <li class="question"><span>Q.</span> <?php _e("I need further assistance, how can I request support?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("We're here to help you, so if you have any questions or comments please do get in touch using the contact form on our envato profile page:", 'ban-users'); ?> <a href="https://codecanyon.net/user/webxmedia">https://codecanyon.net/user/webxmedia</a></li>
                <li class="question"><span>Q.</span> <?php _e("Can I request changes or enhancements to the plugin?", 'ban-users'); ?></li>
                <li class="answer"><span>A.</span> <?php _e("We welcome any feedback and are always looking at new ways to enhance our plugins. To get in touch please contact us using our envato profile page:", 'ban-users'); ?> <a href="https://codecanyon.net/user/webxmedia">https://codecanyon.net/user/webxmedia</a></li>
            </ul>
<!--
            <style>
                li.answer { padding-bottom: 10px; }
                li.question { text-decoration: underline; }
                li.question span { font-weight: bold; color: #f0ad4e; }
                li.answer span { font-weight: bold; color: #5cb85c; }
            </style>
-->
        </div>
        <?php if (!W3DEV_BAN_USERS_PREMIUM_VERSION) { ?>
            <div class="w3dev-tab hidden" id="tab-go-pro">

            <p><span class="text-primary " style="font-weight: bold; font-size: 1.4em;"><?php _e('The Ultimate BAN Users WordPress Plugin', 'ban-users'); ?></span></p>

                <p><strong><?php _e("The Ultimate BAN Users WordPress Plugin is the last plugin you’ll ever need for managing access to your WordPress site and removing users’ content. Ban existing users, deny registrations based on banned ips/emails. Catpure IP/Geodata and much, much more…", 'ban-users');?></strong>.</p>

                <p><a href="https://codecanyon.net/item/wp-ultimate-ban-users/17508338" target='_blank' rel="nofollow"><?php _e('Buy Premium Version', 'ban_uer'); ?></a></p>

                <p><span style="font-size:1.2em;font-weight:bold;text-decoration:underline"><?php _e('Ultimate BAN Users Features:', 'ban-users'); ?></span></p>
                <ul> </ul>
                <?php _e('
                    <li>
                    <strong>Ban existing WordPress users</strong> from logging in</li>
                    <li>Ban users indefinately or until a specified date</li>
                    <li>
                    <strong>Deny visitors from registering</strong> based on email and/or ip address</li>
                    <li>Change the status of Ban users’ posts (i.e. to draft)</li>
                    <li>Capture unqiue reason for banning users</li>
                    <li>Send <strong>custom email notifications</strong>
                    </li>
                    <li>Force banned users to logout, or redirect them</li>
                    <li>
                    <strong>Capture IP/Geodata</strong> during logins</li>
                    <li>Display IP/Geodata/banned details on users table</li>
                    <li>Easily manage banned users/ips/emails</li>
                    <li>Clearly <strong>highlight banned users</strong> in users table</li>
                    <li>Send notifications when users’ publish first post</li>'
                    , 'ban-users');
                ?>
            </div>
        <?php } ?>
    </div>

    <?php
}

add_action( 'edit_user_profile', 'w3dev_edit_user_profile' );
function w3dev_edit_user_profile( $user ) {

    if (!current_user_can( 'edit_users')) { return; }
    if (get_current_user_id() == $user->ID ) { return; } // Do not show on user's own edit screen
    
    $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
    $settings               = $w3dev_ban_user_class->get_options('settings');

    $date = wp_next_scheduled('w3dev_unban_user', array( $user->ID ));  

    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><?php _e('Ban User', 'ban-users'); ?></th>
            <td>
            <label for="input-w3dev-ban-user">
                <input type="checkbox" name="input-w3dev-ban-user" id="input-w3dev-ban-user" <?php
                    checked( $w3dev_ban_user_class->is_user_banned( $user->ID ), TRUE )?> value="1"><?php
                    _e('Ban this user', 'ban-users');
                    ?></label>
                <input name="w3dev-ban-reason" type="hidden" id="w3dev-ban-reason" value="">
                <input name="w3dev-ban-checked" type="hidden" id="w3dev-ban-checked" value="<?php echo (($settings['ban_email'] && $settings['ban_email_default']) ? "1" : "0") ?>"  />
            </td>
        </tr>
        <tr id="js-w3dev-unban-date" <?php if(!$w3dev_ban_user_class->is_user_banned( $user->ID )) echo 'style="display:none'; ?> >
            <th scope="row"><?php _e('Unban Date', 'ban-users'); ?></th>
            <td>
                <label for="input-w3dev-user-unban-date">
                    <input type="text" name="input-w3dev-user-unban-date" id="input-w3dev-user-unban-date" class="datepicker" data-date-format="<?php if (!empty($settings['date_format'])) echo $settings['date_format']; ?>" value="<?php echo ($date ? date($settings['date_format'], $date) : __('Indefinent', 'ban_user') ); ?>" />
                </label>
            </td>
        </tr>
    </table>
    <?php
}


add_action( 'edit_user_profile_update', 'w3dev_edit_user_profile_update' ); 
function w3dev_edit_user_profile_update( $user_id ) {

    if (!current_user_can( 'edit_users')) { return; }    // Is admin
    if (get_current_user_id() == $user_id ) { return; } // Do not show on user's own edit screen

    $w3dev_ban_user_class = W3DEV_BAN_USER_CLASS::get_instance();

    if (empty($_POST['input-w3dev-ban-user'])) {
        $w3dev_ban_user_class->unban_user(floatval($user_id)); // Unlock
    } else {
        $message = $_POST['w3dev-ban-reason'];
        $date    = $_POST['input-w3dev-user-unban-date'];

        $w3dev_ban_user_class->ban_user(floatval($user_id), $message, $date);// Lock
    }
    
}
