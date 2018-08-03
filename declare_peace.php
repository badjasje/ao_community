<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
global $userId;
global $userData;
$declarer_ID = $userId;

if (! defined('ABSPATH')) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
if (empty($declarer_ID)) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
    

$declarer_clan_ID = $userData['clan_id_user'][0];
$clan_leader = get_post_meta($declarer_clan_ID, 'clan_leader', true);

 $ct_1 = get_post_meta($declarer_clan_ID, 'ct_1', true);
 $ct_2 = get_post_meta($declarer_clan_ID, 'ct_2', true);
 $ct_3 = get_post_meta($declarer_clan_ID, 'ct_3', true);
 $ct_4 = get_post_meta($declarer_clan_ID, 'ct_4', true);

if ($declarer_ID == $clan_leader || $ct_1 || $ct_2 || $ct_3 || $ct_4) {
    $declared_on = get_post_meta($_POST['war'], 'declared_on', true);
    $def_clan_leader = get_post_meta($declared_on, 'clan_leader', true);

    $timestamp = current_time('timestamp');
    $args = array(
    'post_title'    => 'PEACE',
    'post_status'   => 'publish',
    'post_type'     => 'event_local',
    'post_author'   => 1
    );
    $new_event_id = wp_insert_post($args);


    update_field('attacktype', 'peace_declared', $new_event_id);

    update_field('attacker_clan_id', $declarer_clan_ID, $new_event_id);
    update_field('defender_clan_id', $declared_on, $new_event_id);

    update_field('attacker_id', $declarer_ID, $new_event_id);
    update_field('defender_id', $def_clan_leader, $new_event_id);
	update_field('dec_message', $_POST['dec_msg'], $new_event_id);
    update_field('time_attacked', $timestamp, $new_event_id);

/* add clan to cooldown list */
    $cooldownlist = maybe_unserialize(get_post_meta($declarer_clan_ID, 'cooldown_list', true));
    
    if(!is_array($cooldownlist)){
		$cooldownlist = array();
	}
		

    $clan_ID = $declared_on;

    $cooldownlist[$clan_ID] = $timestamp+(48 * 3600);
    update_post_meta($declarer_clan_ID, 'cooldown_list', maybe_serialize($cooldownlist));


/* update events */

    $clan_members = get_post_meta($declared_on, 'clan_members');

    foreach ($clan_members[0] as $member) {
        $globals = get_user_meta($member, 'new_global_events', true);
        update_user_meta($member, 'new_global_events', $globals+1);
    }


    $clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');

    foreach ($clan_members2[0] as $member2) {
        $globals = get_user_meta($member2, 'new_global_events', true);
        update_user_meta($member2, 'new_global_events', $globals+1);
    }
    
    
    
    wp_trash_post($_POST['war']);
    
    $array['status'] = 'Peace declared on '.get_the_title($_POST['clan']);;
	$array['next'] = true;
	echo json_encode($array);
	update_user_meta($declarer_ID, 'user_lock', 0);
	exit;
} 
