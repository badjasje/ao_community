<?php
	/*
	require_once("wp-load.php");
	
/*
$args = array(

				'offset'       => 0,
				'number'       => 1500,
				'meta_key'     => 'total_deposits',
				'meta_value'   => 0,
				'meta_compare' => '>'

			 ); 
				
				$users = get_users($args);
				
				foreach ($users as $user) {
					
					$args = array(
						'posts_per_page'   => -1,
						'author'	=> $user->ID,
						'post_type'        => 'deposit',
						'meta_key' => 'release_date',
						'orderby'    => 'meta_value_num',
						);
					
					$depositsCurrent = count(get_posts( $args )); 
					
					
					$deposits = get_user_meta($user->ID, 'total_deposits', true);
					echo '<a target="_blank"href="http://assault.online/wp-admin/user-edit.php?user_id='.$user->ID.'">'.$user->ID .' Deposits old: '.$deposits.'</a><br/>';
					echo $depositsCurrent.'<br/><br/>';
					
					
					
					
					
					
					}