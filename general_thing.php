<?php
	
	require_once("wp-load.php");
	$user_ID = 1;
	update_field('m270_rocket_owned',99,'user_'.$user_ID);
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