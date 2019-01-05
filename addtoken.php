<?php
	require_once("wp-load.php");
	global $userId;
	$newToken = $_POST['usertoken'];
	$tokens = maybe_unserialize(get_user_meta( $userId, 'device_tokens', true ));
	
	if(!is_array($tokens)){
		$tokens = array();
	}
	
	if(in_array($newToken, $tokens)) {
    	exit;
	}else{
		$tokens[] = $_POST['usertoken'];
		update_user_meta( $userId, 'device_tokens', maybe_serialize( $tokens ));
		exit;
	}