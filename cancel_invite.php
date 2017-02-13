<?php
/**
 * Handles invite cancels
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

$user_ID = get_current_user_ID();
$invitekey = $_GET['invite'];
$clan = $_GET['clan'];




$open_invites = get_post_meta($clan,'open_invites');
foreach ($open_invites[0] as $key => $invite) {
	if($invite['invite'] == $invitekey){
	if($invite['clan'] == $clan){

	unset($open_invites[0][$key]);
		update_post_meta($clan, 'open_invites', $open_invites[0]);
		wp_redirect(get_permalink(3801));
		wp_delete_post( $invite['invite_id']);
		
		
	}}}
	
	
	

