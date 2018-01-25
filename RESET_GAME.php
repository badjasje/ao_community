<?php
    /*
    require_once("wp-load.php");
    include('units_array.php');
	include('building_array.php');
	include('missiles_array.php');
    
	global $wpdb;
	
	$resetArray = array();
	foreach ($units as $key => $unit) {
		$resetArray[] = "'".$key.'_owned'."'";
		$resetArray[] = "'".$key.'_ordered'."'";
		}
	
	foreach ($missiles as $key => $missile) {
		$resetArray[] = "'".$key.'_owned'."'";
		$resetArray[] = "'".$key.'_ordered'."'";
		}
	
	foreach ($buildings as $key => $building) {
		$resetArray[] = "'".$key."'";
		}
		
	$resetArray[] = "'networth'";
	$resetArray[] = "'land'";
	$resetArray[] = "'sat_owned'";
	$resetArray[] = "'new_global_events'";
	$resetArray[] = "'new_events'";
	$resetArray[] = "'land_gained_combat'";
	$resetArray[] = "'points_position'";
	$resetArray[] = "'networth_position'";
	$resetArray[] = "'user_clan_points'";
	$resetArray[] = "'name_change_counter'";
	$resetArray[] = "'clan_create_counter'";
	$resetArray[] = "'special_sold_today'";
	$resetArray[] = "'new_clan_timestamp'";
	$resetArray[] = "'aid_sent_today'";
	$resetArray[] = "'total_aid_sent'";
	$resetArray[] = "'number_of_aids'";
	$resetArray[] = "'aid_received'";
	$resetArray[] = "'attacks_made_current'";
	$resetArray[] = "'attacks_rec_current'";
	$resetArray[] = "'attacks_made'";
	$resetArray[] = "'starting_bonus'";
	
	
	$resetArray[] = "'succesful_attacks'";
	$resetArray[] = "'money'";
	$resetArray[] = "'turns'";
	$resetArray[] = "'nw_damage_attacks'";
	$resetArray[] = "'units_killed'";
	$resetArray[] = "'buildings_killed'";
	$resetArray[] = "'money_gained_combat'";
	$resetArray[] = "'kills_made'";
	$resetArray[] = "'missiles_launched'";
	$resetArray[] = "'missiles_hit'";
	$resetArray[] = "'nw_damage_missiles'";
	$resetArray[] = "'thieving_attempts'";
	$resetArray[] = "'succesful_attempts'";
	$resetArray[] = "'money_gained_thieving'";
	$resetArray[] = "'attacks_received'";
	$resetArray[] = "'attacks_lost'";
	$resetArray[] = "'nw_damage_lost'";
	$resetArray[] = "'units_lost'";
	$resetArray[] = "'buildings_lost'";
	$resetArray[] = "'money_lost_combat'";
	$resetArray[] = "'land_lost_combat'";
	$resetArray[] = "'times_killed'";
	$resetArray[] = "'missiles_received'";
	$resetArray[] = "'missiles_hit_rec'";
	$resetArray[] = "'nw_damage_missiles_rec'";
	$resetArray[] = "'attempts_received'";
	$resetArray[] = "'succesful_attempts_rec'";
	$resetArray[] = "'money_lost_thieving'";
	$resetArray[] = "'buildings_built'";
	$resetArray[] = "'units_built_turns'";
	$resetArray[] = "'units_ordered'";
	$resetArray[] = "'units_sold'";
	$resetArray[] = "'morale_lost'";
	$resetArray[] = "'morale_pool'";
	$resetArray[] = "'morale_used'";
	$resetArray[] = "'turns_lost'";
	$resetArray[] = "'highest_land'";
	$resetArray[] = "'highest_networth'";
	$resetArray[] = "'stealth_sat_time'";
	$resetArray[] = "'sat_morale'";
	$resetArray[] = "'spied_current_clan'";
	$resetArray[] = "'current_clan_points'";
	$resetArray[] = "'new_clan_timestamp'";
	$resetArray[] = "'in_war_attacks'";
	$resetArray[] = "'last_attacked'";
	
	
	$resetArray[] = "'moe_position'";
	$resetArray[] = "'moe_next'";
	$resetArray[] = "'moe_prev'";
	$resetArray[] = "'moh_position'";
	$resetArray[] = "'moh_next'";
	$resetArray[] = "'moh_prev'";
	$resetArray[] = "'mog_position'";
	$resetArray[] = "'mog_next'";
	$resetArray[] = "'mog_prev'";
	$resetArray[] = "'moc_position'";
	$resetArray[] = "'moc_next'";
	$resetArray[] = "'moc_prev'";
	$resetArray[] = "'mod_position'";
	$resetArray[] = "'mod_next'";
	$resetArray[] = "'mod_prev'";
	$resetArray[] = "'mot_position'";
	$resetArray[] = "'mot_next'";
	$resetArray[] = "'mot_prev'";
	$resetArray[] = "'modes_position'";
	$resetArray[] = "'modes_prev'";
	$resetArray[] = "'modes_next'";
	$resetArray[] = "'modev_position'";
	$resetArray[] = "'modev_damage'";
	
	
	$resetArray = implode(',',$resetArray);
	
	$wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 0
			WHERE meta_key IN($resetArray)
            ");
	$wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 'dead'
			WHERE meta_key = 'status'
			AND meta_value != 'banned'
            ");
	$wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 'inactive'
			WHERE meta_key = 'stealth_sat_status'
            ");
	$wpdb->query("
			DELETE FROM `${table_prefix}posts` 
			WHERE `post_type` 
			IN ('event_local','wars','deposit','market_order','research','spy_rep','emp')
			");
	
	// Resetting clans
	// Setting variables to 0
	$wpdb->query("
			UPDATE `${table_prefix}postmeta`
			SET meta_value = 0
			WHERE meta_key IN('clan_points','clan_networth','clan_name_change','ua_total','ub_total','24h_pts')
            ");
	// Setting variables to empty array
	$emptyArray = array();
	$wpdb->query("
			UPDATE `${table_prefix}postmeta`
			SET meta_value = ''
			WHERE meta_key IN('cooldown_list','previous_members','24h_pts_list','open_invites','24h_nw_list','war_array')
            ");
	$wpdb->query("
			UPDATE `${table_prefix}postmeta`
			SET meta_value = $emptyArray
			WHERE meta_key IN('cooldown_list','previous_members','24h_pts_list','open_invites','24h_nw_list','war_array')
            ");
	

	
	/* OLD STUFF BELOW
		
		
		
	include('units_array.php');
	include('building_array.php');
	include('missiles_array.php');
	
			$timestamp = current_time('timestamp');
			$args = array(

				'offset'       => 0,
				'number'       => 2000,

			 ); 
				
				$users = get_users($args);
				
				foreach ($users as $user) {
					
					
					
				$user_ID = $user->ID;
				//update_user_meta($user_ID, 'reset_status', 0);}
				
				
				
				update_user_meta($user_ID, 'status', 'dead');
				update_user_meta($user_ID, 'nuke_protection_timestamp', $timestamp+(48 * 3600));}
				
				$status = get_user_meta($user_ID,'status',true);
				
				if(get_user_meta($user_ID, 'reset_status', true) == 0 && $status != 'banned'){
			
				update_user_meta($user_ID, 'new_global_events', 0);
				update_user_meta($user_ID, 'new_events', 0);
				update_user_meta($user_ID, 'land_gained_combat',0);
				update_user_meta($user_ID, 'points_position', 0);
				update_user_meta($user_ID, 'last_attacked', ''); <-
				update_user_meta($user_ID, 'networth_position', 0);
				update_user_meta($user_ID, 'user_clan_points', 0);
				update_user_meta($user_ID, 'name_change_counter', 0);
				update_user_meta($user_ID, 'clan_create_counter', 0);
				update_user_meta($user_ID, 'special_sold_today', 0);
				
				
				
				
				update_user_meta($user_ID, 'new_clan_timestamp', 0);
				update_user_meta($user_ID, 'aid_sent_today', 0);
				update_user_meta($user_ID, 'total_aid_sent', 0);
				update_user_meta($user_ID, 'number_of_aids', 0);
				update_user_meta($user_ID, 'aid_received', 0);
				update_user_meta($user_ID, 'attacks_made_current', 0);
				update_user_meta($user_ID, 'attacks_rec_current', 0);
				
				
				
				update_user_meta($user_ID,'attacks_made',0);
				update_user_meta($user_ID,'starting_bonus','');
				update_user_meta($user_ID,'succesful_attacks',0);
				update_user_meta($user_ID,'nw_damage_attacks',0);
				update_user_meta($user_ID,'units_killed',0);
				update_user_meta($user_ID,'buildings_killed',0);
				update_user_meta($user_ID,'money_gained_combat',0);
				update_user_meta($user_ID,'kills_made',0);
				update_user_meta($user_ID,'missiles_launched',0);
				update_user_meta($user_ID,'missiles_hit',0);
				update_user_meta($user_ID,'nw_damage_missiles',0);
				update_user_meta($user_ID,'thieving_attempts',0);
				update_user_meta($user_ID,'succesful_attempts',0);
				update_user_meta($user_ID,'money_gained_thieving',0);
				update_user_meta($user_ID,'attacks_received',0);
				update_user_meta($user_ID,'attacks_lost',0);
				update_user_meta($user_ID,'nw_damage_lost',0);
				update_user_meta($user_ID,'units_lost',0);
				update_user_meta($user_ID,'buildings_lost',0);
				update_user_meta($user_ID,'money_lost_combat',0);
				update_user_meta($user_ID,'land_lost_combat',0);
				update_user_meta($user_ID,'times_killed',0);
				update_user_meta($user_ID,'missiles_received',0);
				update_user_meta($user_ID,'missiles_hit_rec',0);
				update_user_meta($user_ID,'nw_damage_missiles_rec',0);
				update_user_meta($user_ID,'attempts_received',0);
				update_user_meta($user_ID,'succesful_attempts_rec',0);
				update_user_meta($user_ID,'money_lost_thieving',0);
				update_user_meta($user_ID,'buildings_built',0);
				update_user_meta($user_ID,'units_built_turns',0);
				update_user_meta($user_ID,'units_ordered',0);
				update_user_meta($user_ID,'units_sold',0);
				update_user_meta($user_ID,'morale_lost',0);
				update_user_meta($user_ID,'morale_used',0);
				update_user_meta($user_ID,'turns_lost',0);
				update_user_meta($user_ID,'highest_land',0);
				update_user_meta($user_ID,'highest_networth',0);
				update_user_meta($user_ID, 'stealth_sat_status', 'inactive'); <-
				update_user_meta($user_ID, 'stealth_sat_time', 0);
				update_user_meta($user_ID, 'sat_morale', 0);
				update_user_meta($user_ID, 'spied_current_clan', 0);
				update_user_meta($user_ID, 'current_clan_points', 0);
				update_user_meta($user_ID, 'new_clan_timestamp', 0);
				update_user_meta($user_ID, 'in_war_attacks', 0);
				
				
				
				foreach ($units as $key => $unit) {
				update_user_meta($user_ID, $key.'_owned', 0);
				update_user_meta($user_ID, $key.'_ordered', 0);

				}
				
				foreach ($units as $key => $unit) {
				update_user_meta($user_ID, $key.'_owned', 0);
				update_user_meta($user_ID, $key.'_ordered', 0);

				}
				
				foreach ($buildings as $key => $building) {
				update_user_meta($user_ID, $key, 0);
				}
				
				update_user_meta($user_ID, 'powerplant', 50);
				
				foreach ($missiles as $key => $missile) {
				update_user_meta($user_ID, $key.'_owned', 0);
				update_user_meta($user_ID, $key.'_ordered', 0);
				}

				// SET STATS after death
				update_user_meta($user_ID, 'money', 450000);
				update_user_meta($user_ID, 'land_sold_today', 0);
				update_user_meta($user_ID, 'explored_today', 0);
				update_user_meta($user_ID, 'turns', 200);
				update_user_meta($user_ID, 'networth', 0);
				update_user_meta($user_ID, 'land', 2000);
				update_user_meta($user_ID, 'power', 0);
				update_user_meta($user_ID, 'builtland', 1000);
				update_user_meta($user_ID, 'morale', 0);
				update_user_meta($user_ID, 'morale_pool', 0);
				update_user_meta($user_ID, 'total_deposits', 0);
				


				// RESET RESEARCH ///
				update_user_meta($user_ID, 'level_money_production', 0);
				update_user_meta($user_ID, 'level_missile_accuracy', 0);
				update_user_meta($user_ID, 'level_satellite_construction', 0);
				update_user_meta($user_ID, 'level_shipping_time', 0);
				update_user_meta($user_ID, 'level_market_discount', 0);
				update_user_meta($user_ID, 'level_thieving_effectiveness', 0);
				update_user_meta($user_ID, 'level_engineering_effectiveness', 0);
				update_user_meta($user_ID, 'level_bank_management', 0);
				update_user_meta($user_ID, 'level_powerplant_efficiency', 0);
				update_user_meta($user_ID, 'research_in_progress', 0);
				update_user_meta($user_ID, 'queued_research', 0);
				update_user_meta($user_ID, 'sat_in_progress', 0);
				update_user_meta($user_ID, 'sat_owned', 0);

				// reset medal positioning //
				
				update_user_meta($user_ID,'moe_position',0);
				update_user_meta($user_ID,'moe_next',0);
				update_user_meta($user_ID,'moe_prev',0);
				update_user_meta($user_ID,'moh_position',0);
				update_user_meta($user_ID,'moh_next',0);
				update_user_meta($user_ID,'moh_prev',0);
				update_user_meta($user_ID,'mog_position',0);
				update_user_meta($user_ID,'mog_next',0);
				update_user_meta($user_ID,'mog_prev',0);
				update_user_meta($user_ID,'moc_position',0);
				update_user_meta($user_ID,'moc_next',0);
				update_user_meta($user_ID,'moc_prev',0);
				update_user_meta($user_ID,'mod_position',0);
				update_user_meta($user_ID,'mod_next',0);
				update_user_meta($user_ID,'mod_prev',0);
				update_user_meta($user_ID,'mot_position',0);
				update_user_meta($user_ID,'mot_next',0);
				update_user_meta($user_ID,'mot_prev',0);
				update_user_meta($user_ID,'modes_position',0);
				update_user_meta($user_ID,'modes_prev',0);
				update_user_meta($user_ID,'modes_next',0);
				update_user_meta($user_ID,'modev_position',0);
				update_user_meta($user_ID,'modev_damage',0);
				update_user_meta($user_ID, 'reset_status', 1);
				} 
				
			
			} 


/*

// Reset clan points and NW 
$args = array(
	'posts_per_page'   => -1,
	'post_type'        => 'clan',
);
$clans = get_posts( $args ); 

foreach ($clans as $clan) {
	$warArray = array();
	update_post_meta($clan->ID, 'clan_points', 0);
	update_post_meta($clan->ID, 'bonus_level', 0);
	update_post_meta($clan->ID, 'cooldown_list', 0); 
	update_post_meta($clan->ID, 'previous_members', '');
	update_post_meta($clan->ID, 'clan_networth', 0);
	update_post_meta($clan->ID, 'clan_name_change', 0);
	update_post_meta($clan->ID, 'ua_total', 0);
	update_post_meta($clan->ID, 'ub_total', 0);
	update_post_meta($clan->ID, '24h_pts_list', '');
	update_post_meta($clan->ID, '24h_pts', 0);
	update_post_meta($clan->ID, 'cooldown_list', '');
	update_post_meta($clan->ID, 'open_invites', '');
	update_post_meta($clan->ID, '24h_nw_list', '');
	update_post_meta($clan->ID, 'war_array', $warArray);

	
	
	
	
	}
	


	
	
	
