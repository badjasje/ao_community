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

$declarer_ID = get_current_user_id();

$userLock = get_user_meta($declarer_ID, 'user_lock', true);
if ($userLock == 1) {
    exit;
}
update_user_meta($declarer_ID, 'user_lock', 1);

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
$timestamp = current_time('timestamp');
$def_clan_leader = get_post_meta($_GET['clan'], 'clan_leader', true);

$war_ID = md5(uniqid(rand(), true));

$warcheck = get_posts(
    array(
            'numberposts'   => -1,
            'post_type'     => 'wars',
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'declared_on',
                    'value'     => $declarer_clan_ID,
                    'compare'   => '=',
                ),
                array(
                    'key'       => 'declared_by',
                    'value'     => $_GET['clan'],
                    'compare'   => '=',
                ),
            ),
        )
);

if (count($warcheck) == 0) {
    $decNW = get_post_meta($declarer_clan_ID, 'clan_networth', true);
    $recWN = get_post_meta($_GET['clan'], 'clan_networth', true);

    if ($recWN > $decNW/1.4 && $recWN < $decNW*1.4) {
    } else {
        $_SESSION['status'] = 'You cannot do that.';
        wp_redirect(get_permalink($_GET['clan']));
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
update_post_meta($new_war_id, 'declared_on', $_GET['clan']);
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
update_field('defender_clan_id', $_GET['clan'], $new_event_id);

update_field('attacker_id', $declarer_ID, $new_event_id);
update_field('defender_id', $def_clan_leader, $new_event_id);

update_field('time_attacked', $timestamp, $new_event_id);




$clan_members = get_post_meta($_GET['clan'], 'clan_members');

foreach ($clan_members[0] as $member) {
    $globals = get_user_meta($member, 'new_global_events', true);
    update_user_meta($member, 'new_global_events', $globals+1);
}


$clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');

foreach ($clan_members2[0] as $member2) {
    $globals = get_user_meta($member2, 'new_global_events', true);
    update_user_meta($member2, 'new_global_events', $globals+1);
}

if (count($warcheck) == 0) {
// Set an array for tracking statistics - Declaring clan
    $war_array = get_post_meta($declarer_clan_ID, 'war_array', true);

    $war = array(
    'date'              =>  $timestamp,
    'mutual_date'       =>  0,
    'declarer_id'       =>  $declarer_clan_ID,
    'receiver_id'       =>  $_GET['clan'],
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

    update_post_meta($declarer_clan_ID, 'war_array', $war_array);


// Set an array for tracking statistics - Declared clan
    $war_array_def = get_post_meta($_GET['clan'], 'war_array', true);

    $war = array(
    'date'              =>  $timestamp,
    'mutual_date'       =>  0,
    'declarer_id'       =>  $declarer_clan_ID,
    'receiver_id'       =>  $_GET['clan'],
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

    update_post_meta($_GET['clan'], 'war_array', $war_array_def);
}

// If war already exists, update war array
if (count($warcheck) > 0) {
    $war_array = get_post_meta($declarer_clan_ID, 'war_array', true);
    $war_array[$war_ID]['id_outgoing'] = $new_war_id;
    $war_array[$war_ID]['mutual_date'] = $timestamp;
    update_post_meta($declarer_clan_ID, 'war_array', $war_array);
    
    $war_array_def = get_post_meta($_GET['clan'], 'war_array', true);
    $war_array_def[$war_ID]['id_incoming'] = $new_war_id;
    $war_array_def[$war_ID]['mutual_date'] = $timestamp;
    update_post_meta($_GET['clan'], 'war_array', $war_array_def);
}


update_user_meta($declarer_ID, 'user_lock', 0);
wp_redirect(get_permalink($_GET['clan']));
exit;
