<?php
if (!defined('ABSPATH')) exit;

add_filter('user_row_actions', 'w3dev_ban_user_action_links', 10, 2);
function w3dev_ban_user_action_links($actions, $user_object)
{

    if (get_current_user_id() != $user_object->ID) {

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

        $accessibility = !empty($settings['enable_accessibility']) ? 1 : 0;

        if (empty($accessibility)) {

            $date = '
                <a style="color:#337ab7" class="cgc_ub_edit_badges icon-warn-user " href="' . get_edit_user_link($user_object->ID) . '"  aria-label="Edit">
                    <span class="fa-stack fa-lg" aria-hidden="true">
                      <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                      <i class="fa fa-pencil fa-stack-1x fa-inverse" aria-hidden="true"></i>
                    </span>
                </a>
                <style>.row-actions .edit { display:none; }</style>';

            $date .= '
                <a style="color:#337ab7" class="cgc_ub_edit_badges icon-warn-user " href="edit-comments.php?author=' . $user_object->ID . '&comment_status=all&per_page=' . get_option('comments_per_page') . '" aria-label="Comments">
                    <span class="fa-stack fa-lg" aria-hidden="true">
                        <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                        <i class="fa fa-comment fa-stack-1x fa-inverse" aria-hidden="true"></i>
                    </span>
                </a>';
        } else {
            $date = '
                <a style="color:#337ab7" class="cgc_ub_edit_badges warn-user " href="' . get_edit_user_link($user_object->ID) . '"  aria-label="Edit">' . __('Edit User') . '</a> |';
            $date .= '
                <a style="color:#337ab7" class="cgc_ub_edit_badges warn-user " href="edit-comments.php?author=' . $user_object->ID . '&comment_status=all&per_page=' . get_option('comments_per_page') . '" aria-label="Comments">' . __('Show comments') . '</a> |';
        }

        if ($w3dev_ban_user_class->is_user_banned($user_object->ID)) {
            $text_label = '<span class="banned-user ' . (!empty($settings['users_tbl_row_highlighted']) ? 'row-highlight' : null) . '">' . __('UnBan User', 'ban-users') . '</span>';
            $label = '<span class="banned-user ' . (!empty($settings['users_tbl_row_highlighted']) ? 'row-highlight' : null) . '"></span>';
            $icon_ban_class = 'active';
        } else {
            $text_label = '<span>' . __('Ban User', 'ban-users') . '</span>';
            $label = '<span></span>';
            $icon_ban_class = null;
        }

        if (!empty($settings['warn_user'])) {

            $allow_reason =
                !empty($notifications['user_notification']['warn_body_reason']) ?
                $notifications['user_notification']['warn_body_reason'] :
                $notifications['_defaults']['user_notification']['warn_body_reason'];

            if (!empty($accessibility)) {

                $date .= '
                 <a class="w3dev-accessibility cgc_ub_edit_badges warn-ban-user ' . (!empty($icon_ban_class) ? 'hide' : null) . '" href="javascript:void(0)" data-user-id="' . $user_object->ID . '" data-allow-reason="' . ((!empty($allow_reason) && !empty($settings['warn_user_reason'])) ? "1" : "0") . '">' . __('Warn user', 'ban-users') . '</a>';
            } else {

                $date .= '
                <a class="cgc_ub_edit_badges icon-warn-user warn-ban-user ' . (!empty($icon_ban_class) ? 'hide' : null) . '" href="javascript:void(0)" data-user-id="' . $user_object->ID . '" data-allow-reason="' . ((!empty($allow_reason) && !empty($settings['warn_user_reason'])) ? "1" : "0") . '" aria-label="Warn user">
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
             | <a class="w3dev-accessibility cgc_ub_edit_badges toggle-ban-user ' . $icon_ban_class . '" data-user-id="' . $user_object->ID . '" data-ban-email="' . (($settings['ban_email_default']) ? "1" : "0") . '" href="javascript:void(0)">' . $text_label . '</a>
            ';
        } else {

            $date .= '
            <a class="cgc_ub_edit_badges icon-ban-user toggle-ban-user ' . $icon_ban_class . '" data-user-id="' . $user_object->ID . '" data-ban-email="' . (($settings['ban_email_default']) ? "1" : "0") . '" href="javascript:void(0)">
                <span class="fa-stack fa-lg" aria-hidden="true">
                  <i class="fa fa-square fa-stack-2x" aria-hidden="true"></i>
                  <i class="fa fa-ban fa-stack-1x fa-inverse" aria-hidden="true"></i>
            </span>
            ' . __($label, 'w3dev') . '
                <span class="sr-only">Ban user</span>
            </a>
            ';
        }

        $actions['edit_badges'] = $date;
    }

    return $actions;
}

if (!empty($settings['users_tbl_data_column'])) {

    function w3dev_ban_user_column($columns)
    {
        $columns['registration_date'] = 'Registration date';
        $columns['geodata'] = __('Banned Status');
        return $columns;
    }
    add_filter('manage_users_columns', 'w3dev_ban_user_column');

    function w3dev_show_ban_user_column_content($value, $column_name, $user_id)
    {
        if ('geodata' == $column_name) {
            $banned_modal = '<span data-balloon="Show banned status" data-balloon-pos="up" data-user-id="' . $user_id . '" class="users-info-btn js-w3dev-banned-history w3dev-banned-status"><i class="fa fa-ban" aria-hidden="true"></i></span>';
            $value = '<span>' . $banned_modal . '</span>';
        } elseif ('registration_date' == $column_name){
            $date_format = get_option('date_format') . ' ' . get_option('time_format');
            $value = date($date_format, strtotime(get_the_author_meta('registered', $user_id)));
        }
        return $value;
    }
    // add_action('manage_users_custom_column',  'w3dev_show_ban_user_column_content', 10, 3); // according to code reference it is a filter, not an action
    add_filter('manage_users_custom_column',  'w3dev_show_ban_user_column_content', 10, 3);

        /*
    * Make our "Registration date" column sortable
    * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param} 
    */
    function bu_make_registered_column_sortable($columns)
    {
        return wp_parse_args(array('registration_date' => 'registered'), $columns);
    }
    add_filter('manage_users_sortable_columns', 'bu_make_registered_column_sortable');
    
    /* Add banned status column to edit comments table */
    function bu_ban_user_comments_column($columns)
    {
        $columns['geodata'] = __('Banned Status');
        return $columns;
    }
    add_filter('manage_edit-comments_columns', 'bu_ban_user_comments_column');

    function bu_show_ban_user_comments_column_content($column, $comment_ID)
    {
        global $comment;
        // $comment = get_comment($comment_ID);
        $user_id = $comment->user_id;
        if ('geodata' == $column) {
            $banned_modal = '<span data-balloon="Show banned status" data-balloon-pos="up" data-user-id="' . $user_id . '" class="users-info-btn js-w3dev-banned-history w3dev-banned-status"><i class="fa fa-ban" aria-hidden="true"></i></span>';
            echo '<span>' . $banned_modal . '</span>';
        }
    }
    add_action('manage_comments_custom_column', 'bu_show_ban_user_comments_column_content', 10, 2);
}

/* Add row actions to comment rows */
function bu_action_links_comment_row($actions, $comment)
{
    $user_object = get_user_by('id', $comment->user_id);
    if (!empty($user_object)) {
        $actions = apply_filters('user_row_actions', $actions, $user_object);
    }
    return $actions;
}
add_filter('comment_row_actions', 'bu_action_links_comment_row', 10, 2);

/* Add row actions to banned-history CPT */
function bu_action_links_post_row($actions, $post)
{
    // Check for post type.
    if ($post->post_type == "banned-history") {

        $user_object = get_user_by('login', $post->post_title);
        if (!empty($user_object)) {

            // The default $actions passed has the Edit, Quick-edit and Trash links.
            $edit_link = get_edit_post_link($post->ID);
            $edit = sprintf(
                '<a href="%1$s">%2$s</a>',
                esc_url($edit_link),
                esc_html(__('History'))
            );

            $actions = apply_filters('user_row_actions', $actions, $user_object);

            // Add the new Copy quick link.
            $actions = array_merge(array($edit), $actions);
        }
    }
    return $actions;
}
add_filter('post_row_actions', 'bu_action_links_post_row', 10, 2);

if ( isset( $settings['extensions']['woocommerce'] ) && $settings['extensions']['woocommerce'] ) {
    /* Add row actions to woocommerce product reviews */
    function bu_wc_product_reviews_column_content($output, $item)
    {

        $user_object = get_user_by('id', $item->user_id);
        if (!empty($user_object)) {
            $actions['edit_badges'] = '';
            $actions = apply_filters('user_row_actions', $actions, $user_object);
            if (!empty($actions['edit_badges'])) {
                $output .= '<div class="row-actions"><span class="edit_badges">|' . $actions['edit_badges'] . '|</span></div>';
            }
        }

        return $output;
    }
    add_filter('woocommerce_product_reviews_table_column_comment_content', 'bu_wc_product_reviews_column_content', 10, 2);
}

/* Custom orderby comments by 'In response to' column */
function bu_comments_orderby($comments_query)
{
    $orderby = $comments_query->query_vars['orderby'];
    if ('comment_post_ID' == $orderby) {
        // Modify the ordering
        if ($comments_query->query_vars['order'] == 'asc') {
            $comments_query->query_vars['meta_key'] = 'comment_orderby';
            $comments_query->query_vars['orderby'] = [
                'comment_post_ID'      => 'DESC',
                'meta_value_num' => 'ASC',
                'comment_date_gmt'        => 'ASC'
            ];
        } else {
            $comments_query->query_vars['meta_key'] = 'comment_orderby';
            $comments_query->query_vars['orderby'] = [
                'comment_post_ID'      => 'ASC',
                'meta_value_num' => 'ASC',
                'comment_date_gmt'        => 'ASC'
            ];
        }
    }
}
add_action('pre_get_comments', 'bu_comments_orderby');

/* Comment meta field used for custom comment sort */
function bu_comment_post($comment_id, $comment_approved, $commentdata)
{
    $ordeby = (($commentdata['comment_parent'] == 0) && isset($commentdata['comment_ID'])) ? $commentdata['comment_ID'] : $commentdata['comment_parent'];
    add_comment_meta($comment_id, 'comment_orderby', $ordeby, true);
}
add_action('comment_post', 'bu_comment_post', 10, 3);

/* Comment meta field used for custom comment sort */
function bu_wp_insert_comment($id, $comment)
{
    $ordeby = ($comment->comment_parent == 0) ? $comment->comment_ID : $comment->comment_parent;
    add_comment_meta($id, 'comment_orderby', $ordeby, true);
}
add_action('wp_insert_comment', 'bu_wp_insert_comment', 10, 2);

// add user list to comment list table filter
add_action('restrict_manage_comments', 'custom_filter_by_the_author');
function custom_filter_by_the_author()
{

	$selected = isset($_GET['author']) && $_GET['author'] ? $_GET['author'] : '';

	wp_dropdown_users(
		array(
			// 'role__in' => array(
			// 	'administrator',
			// 	'editor',
			// 	'author',
			// 	'contributor',
			// 	'customer'
			// ),
			'name' => 'author',
			'show_option_all' => 'All authors',
			'selected' => $selected
		)
	);
}

/**
 * Filter comments table by author.
 */
add_filter('comments_list_table_query_args', function ($args) {
	global $pagenow;

	if ($pagenow == 'edit-comments.php') {
		$user = isset($_GET['author']) && $_GET['author'] ? $_GET['author'] : 0;
		$value = !empty($args['author__in']) ? $args['author__in'] : [];

		if (is_array($value) && !in_array($user, $value) && !empty($user)) {
			$value[] = $user;
		}

		$args['author__in'] = $value;
	}

	return $args;
}, 10, 1);

// CPT banned-history, column headers
add_filter('manage_banned-history_posts_columns', function ($columns) {
    unset($columns['date']);
    $columns['modified'] = __('Last Modified');
    $columns['mydate'] = __('Date');
    $columns['karma'] = __('Karma');
    $columns['geodata'] = __('Banned Status');
    return $columns;
});

// CPT banned-history, columns content
add_action('manage_banned-history_posts_custom_column', function ($column, $post_id) {
    if (in_array($column, array('modified', 'mydate', 'geodata', 'karma'))) {
        // $post = get_post($post_id);
        global $post; // global post has calculated column karma
        $date_format = get_option('date_format') . ' ' . get_option('time_format');
        if ('modified' === $column || 'mydate' === $column) {
            if ('modified' === $column) {
                // echo get_the_modified_time($date_format, $post);
                echo date($date_format, strtotime($post->post_modified));
            } else {
                // echo get_post_time($date_format, false, $post);
                echo date($date_format, strtotime($post->post_date));
            }
        } elseif ('karma' === $column) {
            $karma = (($post && isset($post->karma)) ? $post->karma : 0);
            echo $karma;
        } elseif ('geodata' == $column) {
            $user = get_user_by('login', $post->post_title);
            $banned_modal = '<span data-balloon="Show banned status" data-balloon-pos="up" data-user-id="' . (isset ($user->ID) ? $user->ID : 0) . '" class="users-info-btn js-w3dev-banned-history w3dev-banned-status"><i class="fa fa-ban" aria-hidden="true"></i></span>';
            echo '<span>' . $banned_modal . '</span>';
        }
    };
}, 10, 2);

/* Post date column format by wp options date and time format */
function bu_post_date_column_time($h_time, $post)
{
    $date_format = get_option('date_format') . ' ' . get_option('time_format');
    $h_time = get_post_time($date_format, false, $post);
    return $h_time;
}
add_filter('post_date_column_time', 'bu_post_date_column_time', 10, 2);

/* Comment date column 'Published on' format by wp options date and time format 
* Possible error in wp-admin/comment.php line 223 workarround
* Maybe? line 223, wp v6.2.0 should be according to docs:
* get_comment_time( __( 'g:i a' ), $comment );
*/
add_filter('get_comment_date', function ($date, $format, $comment) {
    // __( 'Y/m/d' ) wp-admin/comment.php line 221
    // __( 'g:i a' ) wp-admin/comment.php line 223
    if (__('Y/m/d') == $format) {
        $date = (date(get_option('date_format'), strtotime($comment->comment_date)));
    } elseif (__('g:i a') == $format) {
        $date = (date(get_option('time_format'), strtotime($comment->comment_date)));
    }
    return $date;
}, 10, 3);

// CPT banned-history, Column Sorting
add_filter('manage_edit-banned-history_sortable_columns', function ($columns) {
    $columns['modified'] = 'modified';
    $columns['mydate'] = 'date';
    $columns['karma'] = 'karma';
    return $columns;
});

/* Add comments type banned-history to edit comments dropdown filter by type */
add_filter('admin_comment_types_dropdown', function ($comment_types) {
    $comment_types['banned-history'] = __('Banned History');
    return $comment_types;
});

/* Order banned-history posts by calculated field karma 
* Karma is number of banned days per user total
* Greater spammers has greater comment_karma
*/
function orderby_banned_history_queries($query)
{
    $orderby = (isset($query->query_vars['orderby']) ? $query->query_vars['orderby'] : '');
    $post_type = (isset($query->query_vars['post_type']) ? $query->query_vars['post_type'] : '');

    if (is_admin() && $query->is_main_query() && 'banned-history' == $post_type) {
        add_filter('posts_fields', 'edit_posts_fields');
        add_filter('posts_join_paged', 'edit_posts_join_paged');
        add_filter('posts_groupby', 'edit_posts_groupby');

        function edit_posts_fields($fields)
        {
            remove_filter('edit_posts_fields', __FUNCTION__);
            global $wpdb;
            $analytics = $wpdb->prefix . "comments";
            $fields = implode(',', array($wpdb->prefix . "posts.*", "(SUM($analytics.comment_karma)) AS karma"));
            return $fields;
        };

        function edit_posts_groupby($groupby)
        {
            remove_filter('edit_posts_groupby', __FUNCTION__);
            global $wpdb;
            $analytics = $wpdb->prefix . "comments";
            $groupby = "$analytics.comment_post_ID";
            return $groupby;
        }

        function edit_posts_join_paged($join_paged_statement)
        {
            remove_filter('edit_posts_join_paged', __FUNCTION__);
            global $wpdb;
            $analytics = $wpdb->prefix . "comments";
            $join_paged_statement .= "LEFT JOIN $analytics ON $analytics.comment_post_ID = " . $wpdb->prefix . "posts.ID";
            return $join_paged_statement;
        }

        if ('karma' == $orderby) {
            add_filter('posts_orderby', 'edit_posts_orderby');
            function edit_posts_orderby($orderby_statement)
            {
                remove_filter('posts_orderby', __FUNCTION__);
                global $wpdb;
                $analytics = $wpdb->prefix . "comments";
                $orderby_statement = "(SUM($analytics.comment_karma)) " . (str_contains($orderby_statement, 'DESC') ? 'ASC' : 'DESC');
                return $orderby_statement;
            }
        }

        $paged = get_query_var('paged');
        $posts_per_page = get_query_var('posts_per_page');

        $args = array(
            'post_type' => 'banned-history',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
        );

        $query = new WP_Query($args);
    }
}
add_action('pre_get_posts', 'orderby_banned_history_queries');

// Hide and edit some controls for the CPT banned-history
add_action('admin_head', function () {
    global $pagenow;

    if ($pagenow == 'post.php') {
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#wpbody .wrap a[href*="post-new.php?post_type=banned-history"]').remove();
                var $form_post = $('form#post input[type="hidden"][name="post_type"][value="banned-history"]').parent();
                if ($form_post.length > 0) {
                    $form_post.parent().find('#add-new-comment').remove();
                    $form_post.find('#publishing-action').remove();
                    // $form_post.find('#delete-action').remove();
                    var post_title= $('form#post input[type="hidden"][name="original_post_title"]').val();
                    $form_post.find('#commentsdiv .postbox-header h2').text('<?php _e("User"); ?>' + ': ' + post_title);
                }
            });
        </script>
    <?php
    } elseif ($pagenow == 'edit.php') {
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#wpbody .wrap a[href*="post-new.php?post_type=banned-history"]').remove();
            });
        </script>
<?php
    }
});

// Hide Edit from bulk actions for the CPT banned-history
add_filter( 'bulk_actions-edit-banned-history', function ( $actions ) {
    
    if( isset( $actions[ 'edit' ] ) ) {
        unset( $actions[ 'edit' ] );
    }
    return $actions;
}, 99);

