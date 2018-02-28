<?php
/**
 * Handles clan wars
 *
 * @package WordPress
 */


require(dirname(__FILE__) . '/wp-load.php');

$declarer_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($declarer_ID)) {
    wp_redirect(get_permalink(3582));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}
    

$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user', true);
$clan_leader = get_post_meta($declarer_clan_ID, 'clan_leader', true);

 $ct_1 = get_post_meta($declarer_clan_ID, 'ct_1', true);
 $ct_2 = get_post_meta($declarer_clan_ID, 'ct_2', true);
 $ct_3 = get_post_meta($declarer_clan_ID, 'ct_3', true);
 $ct_4 = get_post_meta($declarer_clan_ID, 'ct_4', true);

if ($declarer_ID == $clan_leader || $ct_1 || $ct_2 || $ct_3 || $ct_4) {
    $declared_on = get_post_meta($_GET['war'], 'declared_on', true);
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
    
    
    
    wp_trash_post($_GET['war']);
    $_SESSION['status'] = 'Peace declared';
    wp_redirect(get_permalink(3842));
    exit;
} else {
    echo 'nope';
}
