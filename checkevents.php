<?php
	
	require_once("wp-load.php");
	$user_ID = get_current_user_id();

	$globals = get_user_meta($user_ID, 'new_global_events', true);	
	if($globals > 0){
	echo '('.$globals . ') global event'.plural_func($globals).' - Assault.Online';
	}else{
	
		echo 'Assault.Online';
	}
	exit;