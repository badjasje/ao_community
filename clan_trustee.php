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

$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
$clanleader = get_post_meta($clan_ID, 'clan_leader', true);

if ($clanleader != $_POST['ct_1']) {
    update_post_meta($clan_ID, 'ct_1', $_POST['ct_1']);
}
    
if ($clanleader != $_POST['ct_2']) {
    update_post_meta($clan_ID, 'ct_2', $_POST['ct_2']);
}

if ($clanleader != $_POST['ct_3']) {
    update_post_meta($clan_ID, 'ct_3', $_POST['ct_3']);
}
    
if ($clanleader != $_POST['ct_4']) {
    update_post_meta($clan_ID, 'ct_4', $_POST['ct_4']);
}

wp_redirect(get_permalink(4506));
exit;
