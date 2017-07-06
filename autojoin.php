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
if(get_field('game_status','option') == 'Live'){
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

if(count($members[0]) >= 7){ 
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
			

}
