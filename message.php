<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */



require( dirname(__FILE__) . '/wp-load.php' );

$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
if(empty($_POST['main_message'])){
$title = $_POST['title'];
$message = $_POST['message'];
$receiver = $_POST['receiver'];
if(empty($title)){$_SESSION['status'] = '1';wp_redirect(get_permalink(4020).'?id='.$_POST['receiver']);exit;}

	$args = array(
				'post_title'    => $title,
				'post_content'	=> $message,
				'post_status'   => 'publish',
				'post_type'		=> 'user_message',
				'post_author'   => $user_ID
				);
			
			
			$new_order_id = wp_insert_post( $args );
			update_post_meta($new_order_id, 'receiver_id', $receiver);
			update_post_meta($new_order_id, 'sender_id', $user_ID);
			update_post_meta($new_order_id, 'general_status', 'New');
			update_user_meta($receiver,'new_messages',get_user_meta($receiver, 'new_messages')[0]+1);
			
			$sub_message_args = array(
				'post_title'    => $title,
				'post_content'	=> $message,
				'post_status'   => 'publish',
				'post_type'		=> 'sub_user_message',
				'post_author'   => $user_ID
				);
			$new_sub_id = wp_insert_post( $sub_message_args );
			update_post_meta($new_sub_id, 'parent_message_id', $new_order_id);
			update_post_meta($new_sub_id, 'sender_id', $user_ID);
			update_post_meta($new_sub_id, 'receiver_id', $receiver);
			update_post_meta($new_sub_id, 'receiver_status', 'New');
			$_SESSION['status'] = '1';wp_redirect(get_permalink(3656));exit;}
			else{
			
			
			$sub_message_args = array(
				'post_title'    => 'Submessage for '.get_the_title($_POST['main_message']),
				'post_content'	=> $_POST['message'],
				'post_status'   => 'publish',
				'post_type'		=> 'sub_user_message',
				'post_author'   => $user_ID
				);
			$new_sub_id = wp_insert_post( $sub_message_args );
			update_post_meta($new_sub_id, 'parent_message_id', $_POST['main_message']);
			update_post_meta($new_sub_id, 'sender_id', $user_ID);
			
			
			$sender_id = get_post_meta($_POST['main_message'], 'sender_id');
			$receiver_id = get_post_meta($_POST['main_message'], 'receiver_id');
			update_post_meta($new_sub_id, 'receiver_id', $receiver_id);
			if($user_ID != $sender_id[0]){
				update_user_meta($sender_id[0],'new_messages',get_user_meta($sender_id[0], 'new_messages')[0]+1);
				update_post_meta($new_sub_id, 'receiver_id', $sender_id[0]);
				}
			if($user_ID != $receiver_id[0]){
				update_user_meta($receiver_id[0],'new_messages',get_user_meta($receiver_id[0], 'new_messages')[0]+1);
				update_post_meta($new_sub_id, 'receiver_id', $receiver_id[0]);
				}
	
			
			update_post_meta($new_sub_id, 'receiver_status', 'New');
			$_SESSION['status'] = '1';wp_redirect(get_permalink(3656));exit;	
			
				
				
				
			}
		