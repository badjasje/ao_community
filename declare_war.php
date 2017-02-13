<?php
/**
 * Handles clan wars
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

$declarer_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($declarer_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user');
$timestamp = strtotime(date('Y-m-d H:i:s'));
$def_clan_leader = get_post_meta($_GET['clan'], 'clan_leader', true);

$args = array(
				'post_title'    => $timestamp,
				'post_content'	=> '',
				'post_status'   => 'publish',
				'post_type'		=> 'wars',
				'post_author'   => $declarer_ID
				);
			
			
			$new_war_id = wp_insert_post( $args );
			update_post_meta($new_war_id, 'declared_by', $declarer_clan_ID[0]);
			update_post_meta($new_war_id, 'declared_on', $_GET['clan']);
			

    /* add globals */

/* create event post */

$args = array(	
	'post_title'    => 'NEW WAR',
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => 1
);
$new_event_id = wp_insert_post( $args );
update_field('attacktype','war_declared', $new_event_id);

update_field('attacker_clan_id',$declarer_clan_ID[0], $new_event_id);
update_field('defender_clan_id',$_GET['clan'], $new_event_id);

update_field('attacker_id',$declarer_ID, $new_event_id);
update_field('defender_id',$def_clan_leader, $new_event_id);

update_field('time_attacked',$timestamp, $new_event_id);



$clan_members = get_post_meta($_GET['clan'],'clan_members');

foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}


$clan_members2 = get_post_meta($declarer_clan_ID[0],'clan_members');

foreach ($clan_members2[0] as $member2) {
	$globals = get_user_meta($member2, 'new_global_events', true);
	update_user_meta($member2, 'new_global_events', $globals+1);
}








			wp_redirect(get_permalink($_GET['clan']));exit;