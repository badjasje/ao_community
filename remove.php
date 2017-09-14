<?php
	require_once("wp-load.php");
	
	$user_ID = get_current_user_id();
	$toRemove = $_GET['id'];
	$savedUsers = get_user_meta($user_ID, 'saved_users', true);
	
	$savedUsers = json_decode($savedUsers,true);
	
	echo '<pre>';
	print_r($savedUsers);
	echo '</pre>';
	
	if(($key = array_search($toRemove, $savedUsers)) !== false) {
    	unset($savedUsers[$key]);
	}
	
	$savedUsers = json_encode($savedUsers);
	update_user_meta($user_ID, 'saved_users', $savedUsers);
	
	wp_redirect(get_permalink(142940)); 
	exit;