<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );


$user_ID = get_current_user_ID();
$clan_ID = get_user_meta($user_ID, 'clan_id_user')[0];
$clanleader = get_post_meta($clan_ID,'clan_leader')[0];

if($user_ID = $clanleader){
	  $my_post = array(
      'ID'           => $_POST['clan'],
      'post_content' => $_POST['publicmessage'],
  );

// Update the post into the database
  wp_update_post( $my_post );
wp_redirect(get_permalink(4506));
	
}