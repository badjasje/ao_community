<?php
/**
 * Handles attacks
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
if (get_field('game_status', 'option') == 'Live') {
    if (! defined('ABSPATH')) {
        exit;
    }
    include('attack_functions.php');
    nocache_headers();

    $_SESSION['attack_array'] = $_POST;

    $userId = get_current_user_ID();
    $userData = get_user_meta($userId);
    
    $attack_nw = $userData['networth'][0];
    $attack_clan_id = $userData['clan_id_user'][0];

    $target_id = $_SESSION['target_id'];
    $defend_nw = get_user_meta($target_id, 'networth')[0];
    $defend_clan_id = get_user_meta($target_id, 'clan_id_user')[0];

    $attack_type = $_SESSION['attacktype'];

/* determine war type */
    $war_type = get_war_type($attack_clan_id, $defend_clan_id);

/* check if target in range */
    $in_range = target_in_range($attack_type, $attack_nw, $defend_nw, $war_type);

    if (!$in_range) {
        wp_redirect(get_permalink(3360).'?fail=9');
        exit;
    } else {
        $_SESSION['target_id'] = $target_id;
        wp_redirect(get_permalink(3366));   //step 3
        exit;
    }
}