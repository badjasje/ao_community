<?php
/**
 * Handles clan invites
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
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}

$clan = $_GET['clan'];

/* Check if clan isn't full */
$clan_members = get_post_meta($clan,'clan_members');
$clan_leader = get_post_meta($clan,'clan_leader',true);

if(count($members[0]) >= 6){ 
	$_SESSION['status'] = 'Clan is full';
	wp_redirect(get_permalink(3601)); exit;
}

$timestamp = current_time('timestamp');
/* check if autojoin allowed */
$autojoin = get_post_meta($clan, 'autojoin_allowed', true);

if($autojoin == 'no'){
	$_SESSION['status'] = 'Auto join not allowed';
	wp_redirect(get_permalink(3601)); exit;
}

/* check if player is already part of clan */
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

if($clan_ID != 0){
	$_SESSION['status'] = 'Cannot do that';
	wp_redirect(get_permalink(3601)); exit;
	
}
$timestamp = current_time('timestamp');
$endDate = get_field('end_date','option');
$endStamp = strtotime($endDate);
$timeLeft = $endStamp-$timestamp;
$marketClose = $timeLeft - 172800;
if($timeLeft<172800){
	$_SESSION['status'] = 'Cannot join a clan the last 48 hours of a round';
	wp_redirect(get_permalink(3601)); exit;
}

$args = array(	
	'post_title'    => 'Clan member joined a clan: '.$user,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $clan_leader
);
$new_event_id = wp_insert_post( $args );
update_field('attacktype','user_change', $new_event_id);
update_field('outcome','joined', $new_event_id);


update_field('attacker_id',$clan_leader, $new_event_id);
update_field('defender_id',$user_ID, $new_event_id);
update_field('attacker_clan_id',$clan, $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);



if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}

/* Update clan ID user */
update_user_meta($user_ID,'clan_id_user',$clan);
/* Update timestamp for joining */
update_user_meta($user_ID, 'clan_join_stamp', $timestamp+86400);
/* Update clan members */						
$clan_members = array_shift($clan_members);
	$clan_members[] = $user_ID;
			
	update_post_meta($clan, 'clan_members', $clan_members);
					
	$_SESSION['status'] = 'Successfully joined this clan';
	wp_redirect(get_permalink($clan));exit;
			