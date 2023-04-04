<?php
class BANNED_HISTORY
{
  const w3dev_user_banned = 'w3dev_user_banned';
  const w3dev_user_unbanned = 'w3dev_user_unbanned';
  const w3dev_user_warn = 'w3dev_user_warn';
  static function on_load()
  {
    add_action('init', array(__CLASS__, 'init'));
    add_action('wp_insert_post', array(__CLASS__, 'wp_insert_post'), 10, 2);
    add_action('before_delete_post', array(__CLASS__, 'before_delete_post'), 10, 2);
    add_action('profile_update', array(__CLASS__, 'profile_update'), 10, 2);
    add_action('user_register', array(__CLASS__, 'profile_update'));
    add_filter('author_link', array(__CLASS__, 'author_link'), 10, 2);
    add_filter('get_the_author_url', array(__CLASS__, 'author_link'), 10, 2);
    add_filter('post_type_link', array(__CLASS__, 'post_type_link'), 10, 2);
  }
  static function init()
  {
    register_post_type(
      'banned-history',
      array(
        'labels'          => array('name' => 'BAN Users History', 'singular_name' => 'BAN Users History', 'edit_item' => 'Banned History', 'add_new' => 'Add Banned History'),
        "description"     => "BAN Users banned history",
        'public'          => true,
        "publicly_queryable" => false,
        'show_ui'         => true,
        'show_in_menu'    => false,
        "show_in_rest"    => false,
        "has_archive"     => false,
        "menu_icon"       => 'dashicons-dismiss',
        "show_in_nav_menus" => false,
        "delete_with_user" => false,
        "exclude_from_search" => true,
        "capability_type" => "post",
        "can_export"      => true,
        'rewrite'         => array('slug' => 'banned-history'),
        'hierarchical'    => false,
        'supports'        => array('comments'),
      )
    );
  }
  static function get_email_key()
  {
    return apply_filters('banned_history_email_key', '_email');
  }
  static function profile_update($user_id, $old_user_data = null)
  {
    global $wpdb;
    $is_new_banned_history = false;
    $user = get_userdata($user_id);
    $user_email = ($old_user_data ? $old_user_data->user_email : $user->user_email);
    $email_key = self::get_email_key();
    $banned_history_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='%s' AND meta_value='%s'", $email_key, $user_email));
    if (!is_numeric($banned_history_id)) {
      $banned_history_id = $is_new_banned_history = wp_insert_post(array(
        'post_type' => 'banned-history',
        'post_status' => 'private',
        'post_title' => $user->user_login,
        'comment_status' => 'open'
      ));
    }
    update_user_meta($user_id, '_banned_history_id', $banned_history_id);
    update_post_meta($banned_history_id, '_user_id', $user_id);
    if ($is_new_banned_history || ($old_user_data && $user->user_email != $old_user_data->user_email)) {
      update_post_meta($banned_history_id, $email_key, $user->user_email);
    }
  }
  static function wp_insert_post($banned_history_id, $banned_history)
  {
    if ($banned_history->post_type == 'banned-history') {
      $email = get_post_meta($banned_history_id, self::get_email_key(), true);
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user = get_user_by('email', $email);
        if ($user) { // Associate the user IF there is an user with the same email address
          update_user_meta($user->ID, '_banned_history_id', $banned_history_id);
          update_post_meta($banned_history_id, '_user_id', $user->ID);
        } else {
          delete_post_meta($banned_history_id, '_user_id');
        }
      }
    }
  }
  static function before_delete_post( $post_id, $post ) {
    // For a specific post type banned-history
    if ( 'banned-history' !== $post->post_type ) {
      return;
    }
    $user_id = self::get_user_id($post_id);
    // error_log('USERID: ' . $user_id . ', POSTID: ' . $post_id);
    // Delete user and post meta, relationship between user and cpt banned-history
    delete_user_meta($user_id, '_banned_history_id');
    delete_post_meta($post_id, '_user_id');
  }
  static function get_user_id($banned_history_id)
  {
    return get_post_meta($banned_history_id, '_user_id', true);
  }
  static function get_user($banned_history_id)
  {
    $user_id = self::get_user_id($banned_history_id);
    return get_userdata($user_id);
  }
  static function get_banned_history_id($user_id)
  {
    return get_user_meta($user_id, '_banned_history_id', true);
  }
  static function get_banned_history($user_id)
  {
    $banned_history_id = self::get_banned_history_id($user_id);
    return get_post($banned_history_id);
  }
  static function author_link($permalink, $user_id)
  {
    $author_id = get_user_meta($user_id, '_banned_history_id', true);
    if ($author_id) // If an associate is found, use it
      $permalink = get_edit_post_link($author_id);
    return $permalink;
  }
  static function post_type_link($url, $post, $leavename = false)
  {
    if ($post->post_type == 'banned-history') {
        $url = get_edit_post_link($post->ID);
    }
    return $url;
  }
  static function add_comment(int $user_ID, string $message, string $type, string $date_format = '', $date_to = ''){
    self::profile_update($user_ID);
    $date = date("Y-m-d H:i:s");
    $comment_karma = 0;
    if (!empty($date_to)) {
        $comment_karma = (new DateTime($date_to))->diff(new DateTime($date))->format("%a");
        if (!empty($date_format)) $date_to = date($date_format, strtotime($date_to));
    }elseif ($date_to === null && $type == self::w3dev_user_banned) {
      $comment_karma = 999;
    }
    if (!empty($date_format)) $date = date($date_format, strtotime($date));
    $comment_msg = implode("; ", [$date, $type, $date_to, $message]);
    $user = get_user_by('ID', $user_ID);
    $comment_post_ID = self::get_banned_history_id($user_ID);
    $comment_author_url = self::author_link(get_home_url(), $user_ID);
    wp_insert_comment(array('comment_post_ID' => $comment_post_ID, 'comment_author' => $user->user_login, 'comment_author_email' => $user->user_email, 'comment_author_url' => $comment_author_url, 'comment_author_IP' => '127.0.0.1', 'comment_content' => $comment_msg, 'comment_karma' => $comment_karma, 'user_id' => $user_ID, 'comment_type' => 'banned-history' ));
    wp_update_post( [ 'ID' => $comment_post_ID ] );
  }
}
BANNED_HISTORY::on_load();
