<?php
/**
 * Handles clan wars
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}
include('constants.php');
require(dirname(__FILE__) . '/wp-load.php');

if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
global $userId;
global $userData;
$declarer_ID = $userId;

$userLock = get_user_meta($declarer_ID, 'user_lock', true);
if ($userLock == 1) {
    $array['status'] = 'Please refresh the page, and try again';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
update_user_meta($declarer_ID, 'user_lock', 1);

if (!defined('ABSPATH')) {
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
$timestamp = current_time('timestamp');
$def_clan_leader = get_post_meta($_POST['clan'], 'clan_leader', true);

$war_ID = md5(uniqid(rand(), true));

$warcheck = get_posts(
    array(
        'numberposts' => -1, 'post_type' => 'wars',
        'meta_query' => array(
            'relation' => 'AND',
            array('key' => 'declared_on', 'value' => $declarer_clan_ID, 'compare' => '='),
            array('key' => 'declared_by', 'value' => $_POST['clan'], 'compare' => '='),
        ),
    )
);

$wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'post_status'   => 'publish',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID
));
$declared_on = array();
foreach ($wars_on as $war) {
	$defClanID = get_post_meta($war->ID,'declared_on',true);
	$declared_on[] = $defClanID;
}
if (in_array($_POST['clan'], $declared_on)) {
    $array['status'] = 'You already declared war with this clan';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}

if (count($warcheck) == 0) {
    $decNW = get_post_meta($declarer_clan_ID, 'clan_networth', true);
    $recWN = get_post_meta($_POST['clan'], 'clan_networth', true);

    if ($recWN > $decNW/1.4 && $recWN < $decNW*1.4) {
    } else {
        $array['status'] = 'Clan out of range';
		$array['next'] = false;
		echo json_encode($array);
		update_user_meta($declarer_ID, 'user_lock', 0);
		exit;
    }
}

if (count($warcheck) > 0) {
    $war_ID = get_post_meta($warcheck[0]->ID, 'war_array_id', true);
}

$args = array(
    'post_title'    => $timestamp,
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'wars',
    'post_author'   => $declarer_ID
);

$new_war_id = wp_insert_post($args);
update_post_meta($new_war_id, 'declared_by', $declarer_clan_ID);
update_post_meta($new_war_id, 'declared_on', $_POST['clan']);
update_post_meta($new_war_id, 'war_array_id', $war_ID);

/* add globals */

/* create event post */
$args = array(
    'post_title'    => 'NEW WAR',
    'post_status'   => 'publish',
    'post_type'     => 'event_local',
    'post_author'   => 1
);
$new_event_id = wp_insert_post($args);
update_field('attacktype', 'war_declared', $new_event_id);

update_field('attacker_clan_id', $declarer_clan_ID, $new_event_id);
update_field('defender_clan_id', $_POST['clan'], $new_event_id);

update_field('attacker_id', $declarer_ID, $new_event_id);
update_field('defender_id', $def_clan_leader, $new_event_id);
update_field('dec_message', $_POST['dec_msg'], $new_event_id);

update_field('time_attacked', $timestamp, $new_event_id);

$clan_members = get_post_meta($_POST['clan'], 'clan_members');

foreach ($clan_members[0] as $member) {
    $globals = get_user_meta($member, 'new_global_events', true);
    update_user_meta($member, 'new_global_events', $globals+1);
    fcm_send_notification($member, 'wardeclared', $userId); // Notify all clan members they are in a war now
}

$clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');

foreach ($clan_members2[0] as $member2) {
    $globals = get_user_meta($member2, 'new_global_events', true);
    update_user_meta($member2, 'new_global_events', $globals+1);
}

if (count($warcheck) == 0) {
    // Set an array for tracking statistics - Declaring clan
    $war_array = maybe_unserialize(get_post_meta($declarer_clan_ID, 'war_array', true));

    if(!is_array($war_array)){
		$war_array = array();
	}

    $war = array(
        'date'              =>  $timestamp,
        'mutual_date'       =>  0,
        'end_date'          =>  0,
        'declarer_id'       =>  $declarer_clan_ID,
        'receiver_id'       =>  $_POST['clan'],
        'id_outgoing'       =>  $new_war_id,
        'id_incoming'       =>  0,
        'attacks_received'  =>  0,
        'successfull_def'   =>  0,
        'attacks_made'      =>  0,
        'successfull_att'   =>  0,
        'missiles_received' =>  0,
        'missiles_hit_def'  =>  0,
        'missiles_sent'     =>  0,
        'missiles_hit_att'  =>  0,
        'nw_dmg_done'       =>  0,
        'nw_dmg_rec'        =>  0,
        'highest_nw_dmg'    =>  0,
        'bds_killed'        =>  0,
        'bds_lost'          =>  0,
        'units_killed'      =>  0,
        'units_lost'        =>  0,
        'land_gained'       =>  0,
        'land_lost'         =>  0,
        'money_gained'      =>  0,
        'clan_points'       =>  0,
        'money_lost'        =>  0,
        'kills'             =>  0,
        'deaths'            =>  0,
    );
    $war_array[$war_ID] = $war;

    update_post_meta($declarer_clan_ID, 'war_array', maybe_serialize($war_array));

    // Set an array for tracking statistics - Declared clan
    $war_array_def = maybe_unserialize(get_post_meta($_POST['clan'], 'war_array', true));

    if(!is_array($war_array_def)){
		$war_array_def = array();
	}

    $war = array(
        'date'              =>  $timestamp,
        'mutual_date'       =>  0,
        'end_date'          =>  0,
        'declarer_id'       =>  $declarer_clan_ID,
        'receiver_id'       =>  $_POST['clan'],
        'id_outgoing'       =>  0,
        'id_incoming'       =>  $new_war_id,
        'attacks_received'  =>  0,
        'successfull_def'   =>  0,
        'attacks_made'      =>  0,
        'successfull_att'   =>  0,
        'missiles_received' =>  0,
        'missiles_hit_def'  =>  0,
        'missiles_sent'     =>  0,
        'missiles_hit_att'  =>  0,
        'nw_dmg_done'       =>  0,
        'nw_dmg_rec'        =>  0,
        'highest_nw_dmg'    =>  0,
        'highest_dmg_id'    =>  0,
        'bds_killed'        =>  0,
        'bds_lost'          =>  0,
        'units_killed'      =>  0,
        'units_lost'        =>  0,
        'land_gained'       =>  0,
        'land_lost'         =>  0,
        'money_gained'      =>  0,
        'money_lost'        =>  0,
        'clan_points'       =>  0,
        'kills'             =>  0,
        'deaths'            =>  0,
    );
    $war_array_def[$war_ID] = $war;

    update_post_meta($_POST['clan'], 'war_array', maybe_serialize($war_array_def));
}

// If war already exists, update war array
if (count($warcheck) > 0) {
    $war_array = maybe_unserialize(get_post_meta($declarer_clan_ID, 'war_array', true));

    $war_array[$war_ID]['id_outgoing'] = $new_war_id;
    $war_array[$war_ID]['mutual_date'] = $timestamp;
    update_post_meta($declarer_clan_ID, 'war_array', maybe_serialize($war_array));

    $war_array_def = maybe_unserialize(maybe_unserialize(get_post_meta($_POST['clan'], 'war_array', true)));
    $war_array_def[$war_ID]['id_incoming'] = $new_war_id;
    $war_array_def[$war_ID]['mutual_date'] = $timestamp;
    update_post_meta($_POST['clan'], 'war_array', maybe_serialize($war_array_def));
}

$array['status'] = 'War declared on '.get_the_title($_POST['clan']);;
$array['next'] = true;
echo json_encode($array);
update_user_meta($declarer_ID, 'user_lock', 0);
exit;