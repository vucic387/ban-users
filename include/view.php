<?php
if ( ! defined('ABSPATH' ) ) exit;

add_filter('user_row_actions', 'w3dev_ban_user_action_links', 10, 2);
function w3dev_ban_user_action_links($actions, $user_object) {
    
    if ( get_current_user_id() != $user_object->ID ) {

        $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
        $settings               = $w3dev_ban_user_class->get_options('settings');
        $notifications          = $w3dev_ban_user_class->get_options('notifications');
        
        // check to see if user has permission to administer ban user controls
        // if returns false then return default $actions
        // --
        if (!$w3dev_ban_user_class->get_ban_user_access($user_object->ID)) {
            return $actions;
        }

        $warn = $date = null;

        $accessibility = !empty( $settings['enable_accessibility'] ) ? 1 : 0;

        if (empty($accessibility)) {

            $date = '
                <a style="color:#337ab7" class="cgc_ub_edit_badges icon-warn-user " href="'.get_edit_user_link( $user_object->ID ).'"  aria-label="Edit">
                    <span class="fa-stack fa-lg" aria-hidden="true">
                      <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                      <i class="fa fa-pencil fa-stack-1x fa-inverse" aria-hidden="true"></i>
                    </span>
                </a>
                <style>.row-actions .edit { display:none; }</style>';

        }

        if ( $w3dev_ban_user_class->is_user_banned( $user_object->ID ) ) {
            $text_label = '<span class="banned-user '.(!empty($settings['users_tbl_row_highlighted']) ? 'row-highlight' : null).'">'. __('UnBan User','ban-users') .'</span>';
            $label = '<span class="banned-user '.(!empty($settings['users_tbl_row_highlighted']) ? 'row-highlight' : null).'"></span>';
            $icon_ban_class = 'active';
        } else {
            $text_label = '<span>'. __('Ban User','ban-users') .'</span>';
            $label = '<span></span>';
            $icon_ban_class = null;
        }

        if ( !empty( $settings['warn_user'] ) ) {

            $allow_reason = 
                !empty( $notifications['user_notification']['warn_body_reason'] ) ? 
                $notifications['user_notification']['warn_body_reason'] : 
                $notifications['_defaults']['user_notification']['warn_body_reason'] ;

            if (!empty($accessibility)) {

                $date .='
                 <a class="w3dev-accessibility cgc_ub_edit_badges warn-ban-user '.(!empty($icon_ban_class) ? 'hide' : null).'" href="javascript:void(0)" data-user-id="' . $user_object->ID .'" data-allow-reason="'.( (!empty($allow_reason) && !empty( $settings['warn_user_reason'] )) ? "1" : "0") .'">'. __('Warn user','ban-users') .'</a>';

            } else {

                $date .='
                <a class="cgc_ub_edit_badges icon-warn-user warn-ban-user '.(!empty($icon_ban_class) ? 'hide' : null).'" href="javascript:void(0)" data-user-id="' . $user_object->ID .'" data-allow-reason="'.( (!empty($allow_reason) && !empty( $settings['warn_user_reason'] )) ? "1" : "0") .'" aria-label="Warn user">
                <span class="fa-stack fa-lg" aria-hidden="true">
                  <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                  <i class="fa fa-exclamation-triangle fa-stack-1x fa-inverse" aria-hidden="true"></i>
                </span>
                </a>
                ';

            }
            
        }

        if (!empty($accessibility)) {

            $date .= '
             | <a class="w3dev-accessibility cgc_ub_edit_badges toggle-ban-user ' . $icon_ban_class . '" data-user-id="'.$user_object->ID.'" data-ban-email="'.(($settings['ban_email_default']) ? "1" : "0") .'" href="javascript:void(0)">'.$text_label.'</a>
            ';

        } else {

            $date .= '
            <a class="cgc_ub_edit_badges icon-ban-user toggle-ban-user ' . $icon_ban_class . '" data-user-id="'.$user_object->ID.'" data-ban-email="'.(($settings['ban_email_default']) ? "1" : "0") .'" href="javascript:void(0)">
                <span class="fa-stack fa-lg" aria-hidden="true">
                  <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                  <i class="fa fa-ban fa-stack-1x fa-inverse" aria-hidden="true"></i>
            </span>
            ' . __( $label, 'w3dev' ) . '
                <span class="sr-only">Ban user</span>
            </a>
            ';

        }

        $actions['edit_badges'] = $date;
    }

    return $actions;

}

if (!empty($settings['users_tbl_data_column'])) {

    add_filter('manage_users_columns', 'w3dev_ban_user_column');
    function w3dev_ban_user_column($columns) {
        $columns['geodata'] = __('User Insights');
        return $columns;
    }
     
    add_action('manage_users_custom_column',  'w3dev_show_ban_user_column_content', 10, 3);
    function w3dev_show_ban_user_column_content($value, $column_name, $user_id) {

        $w3dev_ban_user_class   = W3DEV_BAN_USER_CLASS::get_instance();
        $settings               = $w3dev_ban_user_class->get_options('settings');
        $is_user_banned = $w3dev_ban_user_class->is_user_banned( $user_id );

        if ( 'geodata' == $column_name ) {
                
                $banned_modal = '<span data-balloon="Show banned history" data-balloon-pos="up" style="'.($is_user_banned ? null : 'color:#cccccc').'" data-user-id="'.$user_id.'" class="users-info-btn js-w3dev-banned-history"><i class="fa fa-ban" aria-hidden="true"></i></span>';

            return '<span>' . $banned_modal . '</span>';
        }

    }
    
}

