<?php
/**
 * Handles clan deleting
 *
 * @package WordPress
 */

if ('GET' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

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
$clan = $_GET['clan'];

$clan_ID_deleter = get_user_meta($user_ID, 'clan_id_user');
$clan_leader = get_post_meta($clan, 'clan_leader');

if ($user_ID != $clan_leader[0]) {
    wp_redirect(get_permalink(3601));
    exit;
}

if ($clan_ID_deleter[0] == $clan && $user_ID == $clan_leader[0]) {
    $wars_on = get_posts(array(
         'numberposts'  => -1,
         'post_type'    => 'wars',
         'meta_key'     => 'declared_by',
         'post_status'  => 'publish',
         'meta_value'   => $clan
         ));
         
    $wars_by = get_posts(array(
         'numberposts'  => -1,
         'post_type'    => 'wars',
         'meta_key'     => 'declared_on',
         'post_status'  => 'publish',
         'meta_value'   => $clan
         ));
         
    $warcount = count($wars_on)+count($wars_by);
         
    if ($warcount > 0) {
        $_SESSION['status'] = 'Cannot delete clan during a clan war.';
        wp_redirect(get_permalink(3601));
        exit;
    }
         
        $clan_members = get_post_meta($clan, 'clan_members');
    foreach ($clan_members as $member) {
        update_user_meta($member[0], 'clan_id_user', 0);
    }
        wp_trash_post($clan);
        update_user_meta($user_ID, 'clan_id_user', 0);
        $_SESSION['status'] = 'Your clan was deleted';
        wp_redirect(get_permalink(3601));
        
         
        
    foreach ($wars_on as $war) {
        wp_delete_post($war->ID);
    }
        
        
        
    foreach ($wars_by as $war) {
        wp_delete_post($war->ID);
    }
} else {
    $_SESSION['status'] = 'You cannot do that';
    wp_redirect(get_permalink(3601));
}
