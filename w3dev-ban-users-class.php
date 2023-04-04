<?php
if ( ! defined('ABSPATH' ) ) exit;

class W3DEV_BAN_USER_CLASS {

    protected static $instance = NULL;

    public static function get_instance() {

        // create an object
        NULL === self::$instance and self::$instance = new self;

        return self::$instance; // return the object
    }

    private function __construct() {
 
        $this->is_admin = W3DEV_IS_ADMIN;
        DEFINE( 'W3DEV_BAN_USERS_VERSION_ID', $this->version_id() );

    }


    public function default_options($set = 'settings') {

        $settings = array( 
            'change_posts_status'                   => 0,
            'post_status'                           => '',
            'display_message'                       => 0,
            'custom_message'                        => __('You have been banned from this part of the website', 'ban-users'),
            'force_logout'                          => 1,
            'custom_logout'                         => 0,
            'custom_logout_url'                     => '',
            'unban_date'                            => 0,
            'ban_email'                             => 1,
            'ban_email_default'                     => 1,
            'users_tbl_row_highlighted'             => 1,
            'users_tbl_data_column'                 => 1,
            'banned_login_message'                  => __('This user account has been banned', 'ban-users'),
            'ban_email_default_message'             => __("You have breached our site's terms of use.", 'ban-users'),
            'date_format'                           => 'd-m-Y',
            'frontend_banned_notification'          => 0,
            'frontend_notification_force_logout'    => 0,
            'frontend_notification_hide'            => 0,
            'warn_user'                             => 1,
            'warn_user_reason'                      => 1,
            'accessibility'                         => 0
        );

        if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {
            $settings['notification_emails'] = null;
            $settings['send_notification_new_post'] = 0;
        }

// move these out into email templates partials
// --

$ban_email_body = __("
<p>Dear Member,<br />
 
Your account has been suspended for the following reason. You will therefore be unable to login until your access has been reinstated.<br />
 
%%reason%%<br />

This ban will be lifted: %%unban_date%%<br />
 
If you believe this message has been sent in error, or if you have any questions concerning this notification please email us at [enter your email here]<br />
</p>
", 'ban-users');

$unban_email_body = __("
<p>Dear Member,<br />
 
We are pleased to inform you that your account has been reinstated. You are now able to login as normal.<br />
 
If you have any questions concerning this notification please email us at [enter your email here]<br />
</p>
", 'ban-users');

$warn_email_body = __("
<p>Dear Member,<br />

Your user account is under investigation and at risk of being banned. Please review our comments below.<br />

%%reason%%<br />

If you believe this message has been sent in error, or if you have any questions concerning this notification please email us at [enter your email here]<br />
</p>
", 'ban-users');

        $notification_templates = array(
            'user_notification'             => array(
                'subject_title'             => __('Your account has been suspended.', 'ban-users'),
                'body'                      => $ban_email_body, 
                'unban_subject_title'       => __('Your account has been reinstated.', 'ban-users'),
                'warn_subject_title'        => __('Your account is at risk of being banned.'),
                'warn_body'                 => $warn_email_body,
                'unban_body'                => $unban_email_body,
                'unban_indefinite_date_tag' => __('No date specified', 'ban-users'),
                'warn_body_reason'          => true,
                ),
            'admin_notification' => array(),
            'team_notification' => array()
            );

        if ( W3DEV_BAN_USERS_PREMIUM_VERSION ) {
            $registration_ban_options = array(
                'enable_reg_ban_by_email'       => 1,
                'enable_login_ban_by_email'     => 1, 
                'enable_reg_ban_by_ip'          => 1,
                'enable_login_ban_by_ip'        => 1
                );
        }

        $data = $settings;
        switch ($set) {
            case 'user_notification':
                $data = $notification_templates;
                break;
            
            case 'registration_ban_options':
                $data = $registration_ban_options;
                break;

            default:
                $data = $settings;
                break;
        }

        return $data;
    }



    // return the version id of the plugin
    // --
    public function version_id() {
        return ( W3DEV_DEBUG_MODE === '1' ) ? time() : W3DEV_BAN_USERS_PLUGIN_VERSION ;
    }

    // return the full url of the plugin directory
    // --
    public function plugin_folder() {
        $plugin_folder = basename(dirname(__FILE__));
        return WP_PLUGIN_URL.'/'.$plugin_folder;
    }

    public function ban_user( $user_id, $message="%%reason%%", $date = 'Indefinent', $ban_duration = false) {

        // if not in admin then return false
        // --
        if ($this->is_admin == false) { return false; }
        
        $default_settings           = $this->default_options('settings');
        $default_user_notification  = $this->default_options('user_notification');

        $settings = get_option('w3dev_ban_user_options', $default_settings);

        // change user's posts status
        // --
        if (!empty($settings['change_posts_status']) && !empty($settings['post_status'])) {

            // if CPT supported then get post_type, otherwise default to 'post'
            // --
            $post_type  = (!empty($settings['enable_support_cpt']) && !empty($settings['supported_cpt'])) ? $settings['supported_cpt'] : 'post';
            $user_posts = get_posts(array('author' => $user_id, 'post_type' => $post_type));

            if (!empty($user_posts)) : foreach ($user_posts as $a_post) :
                    wp_update_post(array( 'ID' => $a_post->ID, 'post_status' => $settings['post_status']));
                endforeach;
            endif;
        }

        // change user's role
        // --
        if (!empty($settings['on_ban_change_user_role']) && !empty($settings['set_banned_user_role'])) {
            wp_update_user(array('ID' => $user_id,'role' => $settings['set_banned_user_role']));
        }
        
        // scramble user's password
        // --
        if (!empty($settings['scramble_banned_users_password'])) {
            $scrambled_password = wp_generate_password();
            wp_set_password( $scrambled_password, $user_id );
        }

        // set spammer option
        // --
        if (!empty($settings['set_spammer_option'])) {
            function w3dev_set_spammer_option( $id, $pref, $value, $deprecated = null ) {

                global $wpdb;
                $wpdb->update( $wpdb->users, array( sanitize_key( $pref ) => $value ), array( 'ID' => $id ) );
                $user = new WP_User( $id );
                clean_user_cache( $user );

            }
            w3dev_set_spammer_option( $user_id, 'user_status', 1 );
        }

        // here we check to see if a ban duration has been selected
        // and if so then we set the date accordingly
        // --
        if (!empty($ban_duration)) {

            $now = date("Y-m-d H:i:s");

            switch ($ban_duration) {
                case 'indefinately':
                    $date = null;
                    break;

                case '1 day':
                    $date = date('Y-m-d H:i:s', strtotime($now . ' +1 day'));
                    break;

                case '1 week':
                    $date = date('Y-m-d H:i:s', strtotime($now . ' +1 week'));
                    break;

                case '2 weeks':
                    $date = date('Y-m-d H:i:s', strtotime($now . ' +2 weeks'));
                    break;

                case '1 month':
                    $date = date('Y-m-d H:i:s', strtotime($now . ' +1 month'));
                    break;

                case 'date picker':
                    $date = $date;
                    break;
                                
                default:
                    $date = null;
                    break;
            }

        }
        
        // Remove previous unban (if any)
        // Set new unban schedule (if defined)
        // --
        wp_unschedule_event(  wp_next_scheduled('w3dev_unban_user', array( intval($user_id) )), 'w3dev_unban_user', array( intval($user_id) ));
        if ($date != null) {
            if ( $settings[date_format] == "m-d-Y") $date = str_replace('-', '/', $date); // Converts to be assumed as American.
            wp_schedule_single_event( strtotime($date), 'w3dev_unban_user', array( intval($user_id)) );
        }


        // Update status
        //
        if ( ! $this->is_user_banned( $user_id ) ) {

            global $wpdb;

            // set the user to banned
            // --
            update_user_option( $user_id, 'w3dev_user_banned', TRUE, FALSE );
            update_user_option( $user_id, 'w3dev_user_banned_date', date('d-m-Y'), FALSE );

            // if ban reason (passed as paramater = message)
            // has been supplied then store with the userdata.
            // --
            if (!empty($message)) {
                $message = strip_tags($message);
                update_user_option( $user_id, 'w3dev_user_banned_reason', $message, FALSE );
                
            } elseif (!empty($settings['ban_email_default_message'])) {
                
                // Store the message as a variable for manipulation
                // Find if tags exist to be modified
                // --
                $body = $settings['ban_email_default_message'];
                $find_reason_tag            = strpos( $body, '%%reason%%' );
                $find_unban_date_tag        = strpos( $body, '%%unban_date%%' );

                // Determine if the message sent as a parameter is empty
                // Replaces the empty message with a generic one
                // --
                if ( empty($message) ) { $message = $settings['ban_email_default_message']; }
                if ( $find_reason_tag !== false ) { $body = str_replace('%%reason%%', $message, $body); }
                if ( $find_unban_date_tag !== false ) { $body = str_replace('%%unban_date%%', (!empty($date) ? date($settings['date_format'], strtotime($date)) : $ban_user_email_notification['unban_indefinite_date_tag']), $body); }

                update_user_option( $user_id, 'w3dev_user_banned_reason', $body, FALSE );
            }

            // email should be sent
            // -- 
            if ( $settings['ban_email'] ) {

                // let's grab the users email address from the db
                // and pass it through php filter to sanitise it
                // --
                $user_info  = get_userdata($user_id);
                $user_email = $user_info->user_email;
                $user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
        
                if (!empty($user_email)) {
        
                    // let's get the plugin template options
                    // --
                    $email_templates = get_option('w3dev_ban_user_email_templates', $default_user_notification);

                    // next we need to get the template and replace the reason tag 
                    // %%reason%% with the text provided in the options or popup window
                    // --
                    $ban_user_email_notification        = $email_templates['user_notification'];
                    $subject_title                      = !empty( $ban_user_email_notification['subject_title'] ) ? $ban_user_email_notification['subject_title'] : $default_user_notification['user_notification']['subject_title'];
                    $body                               = !empty( $ban_user_email_notification['body'] ) ? $ban_user_email_notification['body'] : $default_user_notification['user_notification']['body'];
                    $find_reason_tag                    = strpos( $body, '%%reason%%' );
                    $find_unban_date_tag                = strpos( $body, '%%unban_date%%' );
        
                    // Determine if the message sent as a parameter is empty
                    // Replaces the empty message with a generic one
                    // --
                    if ( empty($message) ) { $message = $settings['ban_email_default_message']; }
                    if ( $find_reason_tag !== false ) { $body = str_replace('%%reason%%', $message, $body); }
                    if ( $find_unban_date_tag !== false ) { $body = str_replace('%%unban_date%%', (!empty($date) ? date($settings['date_format'], strtotime($date)) : $ban_user_email_notification['unban_indefinite_date_tag']), $body); }

                    $user = get_user_by( 'ID', $user_id );
                    if (!empty($user)) {
                        $body = str_replace('%%username%%', $user->user_login, $body);
                        $body = str_replace('%%first_name%%', $user->first_name, $body);
                        $body = str_replace('%%last_name%%', $user->last_name, $body);
                    }
        
                    // define headers
                    // --
                    $headers = array();
                    $headers[] = "Content-Type: text/html; charset=utf-8\r\n";

                    // include bcc and cc if applicable
                    // --
                    if (!empty($ban_user_email_notification['ban_cc_field'])) { $headers[] = 'Cc: '.$ban_user_email_notification['ban_cc_field']; }
                    if (!empty($ban_user_email_notification['ban_bcc_field'])) { $headers[] = 'Bcc: '.$ban_user_email_notification['ban_bcc_field']; }

                    // finally, a quick check to ensure the email is valid
                    // before sending using wp_mail
                    // --
                    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                        wp_mail( $user_email, $subject_title, $body, $headers );            
                    }

                }

            }

        }
    }


    public function unban_user( $user_id ) {

        $settings       = $this->get_options('settings');
        $notifications  = $this->get_options('notifications','user_notification');

        // Update status, first check user is actually banned
        // --
        if ( $this->is_user_banned( $user_id ) ) {

            wp_unschedule_event(  wp_next_scheduled('w3dev_unban_user', array( intval($user_id) )), 'w3dev_unban_user', array( intval($user_id) ));
            update_user_option( $user_id, 'w3dev_user_banned', FALSE, FALSE );
            update_user_option( $user_id, 'w3dev_user_unbanned_date', date('d-m-Y'), FALSE );

            if (!empty($settings['on_unban_change_user_role'])) {
                if (!empty($settings['set_unbanned_user_role'])) {
                    $args = array('ID' => $user_id,'role' => $settings['set_unbanned_user_role']);
                    wp_update_user( $args );
                }
            }

            if (!empty($settings['unset_spammer_option'])) {
                function w3dev_unset_spammer_option( $id, $pref, $value, $deprecated = null ) {

                    global $wpdb;
                    $wpdb->update( $wpdb->users, array( sanitize_key( $pref ) => $value ), array( 'ID' => $id ) );
                    $user = new WP_User( $id );
                    clean_user_cache( $user );

                }
                w3dev_unset_spammer_option( $user_id, 'user_status', 0 );
            }

            global $wpdb;

            // email should be sent
            // -- 
            if( $settings['ban_email'] ) {

                // let's grab the users email address from the db
                // and pass it through php filter to sanitise it
                // --
                $user_info  = get_userdata($user_id);
                $user_email = $user_info->user_email;
                $user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
        
                if (!empty($user_email)) {
        
                    // next we need to get the template and replace the reason tag 
                    // %%reason%% with the text provided in the options or popup window
                    // --
                    $unban_subject_title = 
                        !empty( $notifications['unban_subject_title'] ) ? 
                        $notifications['unban_subject_title'] : 
                        $notifications['_defaults']['user_notification']['unban_subject_title'] ;
        
                    // Determine if the message sent as a parameter is empty
                    // Replaces the empty message with a generic one
                    // --
                    $message = 
                        !empty($notifications['unban_body']) ? 
                        $notifications['unban_body'] : 
                        $notifications['_defaults']['user_notification']['unban_body'] ;


                    $message = str_replace('%%username%%', $user_info->user_login, $message);
                    $message = str_replace('%%first_name%%', $user_info->first_name, $message);
                    $message = str_replace('%%last_name%%', $user_info->last_name, $message);

                    // define headers
                    // --
                    $headers = array();
                    $headers[] = "Content-Type: text/html; charset=utf-8\r\n";

                    // include bcc and cc if applicable
                    // --
                    if (!empty($notifications['unban_cc_field'])) { $headers[] = 'Cc: '.$notifications['unban_cc_field']; }
                    if (!empty($notifications['unban_bcc_field'])) { $headers[] = 'Bcc: '.$notifications['unban_bcc_field']; }

                    // finally, a quick check to ensure the email is valid
                    // before sending using wp_mail
                    // --
                    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
                        wp_mail( $user_email, $unban_subject_title, $message, $headers );           
                    }

                }

            }

        }

    }

    public function ban_tag_edit( $message, $user_id) {

	if ( $user_id != true )
		$user_id = get_current_user_id();

        // Get date settings
        // --
        $settings       = $this->get_options('settings');
        $notifications  = $this->get_options('notifications','user_notification');

        // Determine if tags exist
        // --
        $find_reason_tag            = strpos( $message, '%%reason%%' );
        $find_unban_date_tag        = strpos( $message, '%%unban_date%%' );

        $unban_indefinite_date_tag = 
            !empty($notifications['unban_indefinite_date_tag']) ? 
            $notifications['unban_indefinite_date_tag'] :
            $notifications['_defaults']['user_notification']['unban_indefinite_date_tag'] ;

        // Determine if the message sent as a parameter is empty
        // Replaces the empty message with a generic one
        // --
        if ( $find_reason_tag !== false ) { 
            $reason = get_user_option( 'w3dev_user_banned_reason', $user_id);
            $message = str_replace('%%reason%%', $reason, $message );
        }

        if ( $find_unban_date_tag !== false ) { 
            $date = wp_next_scheduled('w3dev_unban_user', array( intval($user_id) ));
            $message = str_replace('%%unban_date%%', (!empty($date) ? date($settings['date_format'], $date) : $unban_indefinite_date_tag), $message);
        }			

        return $message;
    }

    public function is_user_banned( $user_id ) {
        return get_user_option( 'w3dev_user_banned', $user_id );
    }

    // purpose of this function is to load and include files
    // that are included in the extensions folder
    public function load_extensions() {

        $directory = '/path/to/my/extensions';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        if (!empty($scanned_directory)) {
            foreach ($scanned_directory as $file) {
                include_once($directory.'/'.file);
            }
        }

    }


    public function get_options($option,$array_key=false) {

        $options = false;

        if ($option == 'settings') {

            $defaults = $this->default_options('settings');

            if (!empty($array_key)) {
                $arr = get_option('w3dev_ban_user_options', $defaults);
                $options = $arr[$array_key];
            } else {
                $options = get_option('w3dev_ban_user_options', $defaults);
            }
            
            $options['_defaults'] = $defaults;

        } elseif ($option == 'notifications') {

            $defaults = $this->default_options('user_notification');

            if (!empty($array_key)) {
                $arr = get_option('w3dev_ban_user_email_templates', $defaults);
                $options = $arr[$array_key];
            } else {
                $options = get_option('w3dev_ban_user_email_templates', $defaults);
            }
            
            $options['_defaults'] = $defaults;

        }

        return $options;

    }

    // gets the current user's role and then compares against the full list
    // of roles in WP. Returns the index of this role to determine it's ranking
    // a role with a lower number has a higher ranking position, i.e. admin = 1
    // --
    public function get_ban_user_access($moderated_user_id=false) {

        $settings               = $this->get_options('settings');

        /* this is the 'admin' user */
        $moderator_user         = wp_get_current_user();
        $moderator_user_data    = get_userdata( $moderator_user->ID );
        $moderator_user_role    = is_array( $moderator_user_data->roles ) ? array_shift( $moderator_user_data->roles ) : 0;

        /* this is the target user */
        $moderated_user_data    = get_userdata( $moderated_user_id );
        $moderated_user_role    = is_array( $moderated_user_data->roles) ? array_shift( $moderated_user_data->roles ) : 0;

        // check to see if admin override is enabled (meaning always allow admin to ban/unban a user)
        // this applies to super admin or any admin account, if true then skip all other checks, user has special powers!
        // --
        if (!empty($settings['security']['enable_admin_override'])) {
            if (is_super_admin( $moderator_user->ID ) || in_array('administrator', $moderator_user->roles)) { 
                return true; 
            }
        }

        // if current user doesn't belong to the ban/user privilaged group, then return
        // default $actions as this user isn't allowed to ban/unban users
        // --
        if (!empty($settings['security']['set_moderator_roles'])) { 
            if (empty($settings['security']['moderator_roles'])) { return false; }
            if (!empty($settings['security']['moderator_roles']) && !in_array($moderator_user_role, $settings['security']['moderator_roles'])) {
                return false;
            }
        }


        // if current user doesn't belong to the ban/user privilaged group, then return
        // default $actions as this user isn't allowed to ban/unban users
        // --
        if (!empty($settings['security']['set_moderated_roles'])) { 
            if (empty($settings['security']['moderated_roles'])) { return false; }
            if (!empty($settings['security']['moderated_roles']) && !in_array($moderated_user_role, $settings['security']['moderated_roles'])) {
                return false;
            }
        }

        return true;

    }


}

?>