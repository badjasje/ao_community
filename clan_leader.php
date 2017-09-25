<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();


$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
$clan_leader = get_post_meta($clan_ID, 'clan_leader', true);
if ($clan_leader != $user_ID) {
    wp_redirect(get_permalink(3601));
    exit;
}
update_post_meta($clan_ID, 'clan_leader', $_POST['new_leader']);


$_SESSION['status'] = 'New clan leader set';
wp_redirect(get_permalink(3601));
exit;
