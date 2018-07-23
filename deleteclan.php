<?php
/**
 * Handles clan deleting
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

if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$userId = get_current_user_id();


if (empty($userId)) {
    $array['status'] = 'Log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if (!is_user_logged_in()) {
    $array['status'] = 'Log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$clan = $_POST['clan'];

$clan_ID_deleter = get_user_meta($userId, 'clan_id_user',true);
$clan_leader = get_post_meta($clan, 'clan_leader',true);

if ($userId != $clan_leader) {
	$array['status'] = 'You are not the clan leader';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if ($clan_ID_deleter == $clan && $userId == $clan_leader) {
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
        $array['status'] = 'Cannot delete clan during a clan war';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
         
        $clan_members = get_post_meta($clan, 'clan_members');
		foreach ($clan_members as $member) {
        	update_user_meta($member[0], 'clan_id_user', 0);
    	}
        wp_trash_post($clan);
        update_user_meta($userId, 'clan_id_user', 0);
        update_user_meta($userId, 'clan_create_counter', 1);
        
        $array['status'] = 'Your clan was deleted';
		$array['next'] = true;
		echo json_encode($array);

        
        
         
        
    foreach ($wars_on as $war) {
        wp_delete_post($war->ID);
    }
        
        
        
    foreach ($wars_by as $war) {
        wp_delete_post($war->ID);
    }
    exit;
}