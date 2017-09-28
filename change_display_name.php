<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

require(dirname(__FILE__) . '/wp-load.php');
$user_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3582));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}
wp_update_user(array( 'ID' => $user_ID, 'display_name' => $_POST['username'] ));
update_user_meta($user_ID, 'name_change_counter', 1);
$_SESSION['status'] = '1337';
wp_redirect(get_permalink(3486));
exit;
