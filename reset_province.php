<?php

/**
 * Handles reset province
 *
 * @package WordPress
 */
require( dirname(__FILE__) . '/wp-load.php' );

$userId = get_current_user_ID();
$clanId = get_user_meta($userId, 'clan_id_user');

$incomingWars = get_posts(array(
    'numberposts' => -1,
    'post_type' => 'wars',
    'meta_key' => 'declared_by',
    'meta_value' => $clanId[0]
        ));

if (!defined('ABSPATH'))
    exit;
if (empty($user_ID)) {
    wp_redirect(get_permalink(3582));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}


if (count($incomingWars) < 1) {
    update_user_meta($user_ID, 'status', 'dead');

    $_SESSION['status'] = 'Account has been reset, madafaka';
    wp_redirect(get_permalink(3486));
    exit;
} else {
    $_SESSION['status'] = 'You cannot reset your account while having incoming clan wars';
    wp_redirect(get_permalink(3486));
    exit;
}
    


