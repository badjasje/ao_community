<?php
	require_once("wp-load.php");
	
	$users = get_users();
	
	foreach ($users as $user) {

		$status = get_user_meta($user->ID, 'status', true);
		if($status == 'dead'){
			echo $status;
			after_death($user->ID);
			update_user_meta($user->ID, 'networth', 1);
		}
		}