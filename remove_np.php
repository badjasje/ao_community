<?php
/**
 * Handles clan wars
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
$user_ID = get_current_user_ID();


if ($user_ID == $_GET['user']) {
    update_user_meta($user_ID, 'status', 'online');


/* Create nuke protection event */
            $args = array(
                'post_title'    => 'Nukeprotection removed for '.$user_ID,
                'post_status'   => 'publish',
                'post_type'     => 'event_local',
                'post_author'   => $user_ID
                );
                
                
                $new_event_id = wp_insert_post($args);
                update_field('attacktype', 'nukeprotection', $new_event_id);
                update_user_meta($user_ID, 'new_events', get_user_meta($user_ID, 'new_events')[0]+1);
                update_field('defender_id', $user_ID, $new_event_id);
                update_field('attacker_id', $user_ID, $new_event_id);
                update_field('time_attacked', $timestamp, $new_event_id);
    $_SESSION['status'] = 'Assault Protection removed';
    wp_redirect(get_permalink(3486));
    exit; //back to dashboard
} else {
    wp_redirect(get_permalink(3486));
    exit; //back to dashboard
}
