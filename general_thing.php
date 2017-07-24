<?php
	
	require_once("wp-load.php");
	
	/*
	$users = get_users();
	foreach ($users as $user) {
		$user_ID = $user->data->ID;
		$currentCP = get_user_meta($user_ID, 'user_clan_points', true);
		update_user_meta($user_ID, 'current_clan_points',$currentCP);
		
		}

	
	/*

$args = array(

				'offset'       => 0,
				'number'       => 1500,
				'meta_key'     => 'level_bank_management',
			

			 ); 
				
				$users = get_users($args);
				
				foreach ($users as $user) {
					
				
					
					$levelBM = get_user_meta($user->ID, 'level_bank_management', true);			
				
					echo '<a target="_blank"href="http://assault.online/wp-admin/user-edit.php?user_id='.$user->ID.'"> Player: '.$levelBM.'</a><br/>';
					echo $levelBM.'<br/><br/>';
					
					
					
					
					
					
					}