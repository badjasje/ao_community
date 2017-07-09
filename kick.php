<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );
$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}




$user = $_GET['id'];
$clan = $_GET['clan'];

 $ct_1 = get_post_meta($clan,'ct_1',true);
 $ct_2 = get_post_meta($clan,'ct_2',true);
 $ct_3 = get_post_meta($clan,'ct_3',true);
 $ct_4 = get_post_meta($clan,'ct_4',true);


$clanleader = get_post_meta($clan,'clan_leader',true);
$allowed_to_kick = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);

if(!in_array($user_ID, $allowed_to_kick)){
wp_redirect(get_permalink($clan));
}

if(in_array($user_ID, $allowed_to_kick)){
$clan_members = get_post_meta($clan,'clan_members');
$clan_members = array_shift($clan_members);	

foreach ($clan_members as $key => $member) {	
	if($member == $user){

unset($clan_members[$key]);	
}}

if($user == $ct_1){
	update_post_meta($clan, 'ct_1', 0);
}
if($user == $ct_2){
	update_post_meta($clan, 'ct_2', 0);
}
if($user == $ct_3){
	update_post_meta($clan, 'ct_3', 0);
}
if($user == $ct_4){
	update_post_meta($clan, 'ct_4', 0);
}
update_post_meta($clan, 'clan_members', $clan_members);
update_user_meta($user,'clan_id_user',0);
$timestamp = strtotime(date('Y-m-d H:i:s'));
update_user_meta($user,'new_clan_timestamp',$timestamp+86400);

$previous_members = get_post_meta($clan,'previous_members');
$previous_members = array_shift($previous_members);
$previous_members[] = $user;

update_post_meta($clan, 'previous_members', $previous_members);

/* user kicked event */
$timestamp = strtotime(date('Y-m-d H:i:s'));

$args = array(	
	'post_title'    => 'Clan member kicked: '.$user,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $user_ID
);
$new_event_id = wp_insert_post( $args );
update_field('attacktype','user_change', $new_event_id);
update_field('outcome','kicked', $new_event_id);


update_field('attacker_id',$user_ID, $new_event_id);
update_field('defender_id',$user, $new_event_id);
update_field('leaving_user',$user, $new_event_id);
update_field('attacker_clan_id',$clan, $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);

/* update event count */
$event_count = get_user_meta($user, 'new_events',true);
update_user_meta($user, 'new_events', $event_count + 1);


$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}

$cp_lost = round(get_user_meta($user, 'current_clan_points', true)*0.25);
$clan_points = get_post_meta($clan, 'clan_points', true);
$new_clanpoints = $clan_points-$cp_lost;
if($new_clanpoints < 0){
	$new_clanpoints = 0;
}
update_user_meta($user, 'current_clan_points', 0);
update_post_meta($clan, 'clan_points', $new_clanpoints);
update_field('clan_points',$cp_lost, $new_event_id);

$_SESSION['status'] = 'Clan member kicked. '.$cp_lost.' clan points lost';
wp_redirect(get_permalink($clan));
}

