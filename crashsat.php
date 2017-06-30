<?php
	require_once("wp-load.php");
	
	$user_ID = get_current_user_id();
	
include 'satellite_array.php';




if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); 
	exit;
}

if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); 
	exit;
	}
	
	
	
$sat_owned = get_user_meta($user_ID, 'sat_owned', true);
$demolishCost = $satellites[$sat_owned]['price'] * 0.2;

$totalmoney = get_user_meta($user_ID, 'money',true);


if($demolishCost > $totalmoney){
	$_SESSION['status'] = 'Insufficient funds';
	wp_redirect(get_permalink(8578)); exit;
}

update_user_meta($user_ID, 'sat_owned', 0);
update_user_meta($user_ID, 'sat_endlife', 0);
			
	$args = array(	
		'post_title'    => 'Sat crash: '.$user_ID,
		'post_status'   => 'publish',
		'post_type'		=> 'event_local',
		'post_author'   => $user_ID
		);
		
	$new_event_id = wp_insert_post( $args );
	update_field('attacktype','sat_crash', $new_event_id);



	update_field('attacker_id',0, $new_event_id);
	update_field('defender_id',$user_ID, $new_event_id);
	update_field('time_attacked',$timestamp, $new_event_id);

	/* update event count */
	$event_count = get_user_meta($user_ID, 'new_events',true);
	update_user_meta($user_ID, 'new_events', $event_count + 1);
	update_user_meta($user_ID, 'money', $totalmoney - $demolishCost );
	
	$_SESSION['status'] = 'Satellite demolished';
	wp_redirect(get_permalink(8578));
	exit;