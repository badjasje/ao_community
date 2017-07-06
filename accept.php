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
$invitekey = $_GET['invite'];
$clan = $_GET['clan'];
$clan_members = get_post_meta($_GET['clan'],'clan_members');
$timestamp = current_time('timestamp');
if(count($members[0]) >= 7){ 
						wp_redirect(get_permalink(3601)); exit;
					}

$open_invites = get_post_meta($_GET['clan'],'open_invites');

$clan_ID = get_user_meta($user_ID, 'clan_id_user');
if($clan_ID[0] == 0){
	foreach ($open_invites[0] as $key => $invite) {
		if($invite['invite'] == $invitekey) {
			if($invite['clan'] == $clan) {
				if($invite['user'] != $user_ID) {
					wp_redirect(get_permalink(3601));
				}
				if($invite['user'] == $user_ID) {
					update_user_meta($user_ID,'clan_id_user',$clan);
							
					
					
					$clan_members = array_shift($clan_members);
					$clan_members[] = $user_ID;
			
					unset($open_invites[0][$key]);
			
					update_post_meta($clan, 'clan_members', $clan_members);
					update_post_meta($_GET['id'], 'invite_status', 'accept');
					update_post_meta($clan, 'open_invites', $open_invites[0]);
					update_user_meta($user_ID, 'clan_join_stamp', $timestamp+86400);
					wp_redirect(get_permalink($clan));
					

			
				}
			}
		}
	}
}
else {
	wp_redirect(get_permalink(3601));
}
}
	

