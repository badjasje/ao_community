<?php
	
	// DELETE FROM `assauu_db1`.`23zx_posts` WHERE `23zx_posts`.`post_type` = 'shop_order'   <- USE AS SQL COMMAND
	/*
	require_once("wp-load.php");
	
	include('units_array.php');
	include('building_array.php');
	include('missiles_array.php');
	

			$args = array(

				'offset'       => 0,
				'number'       => 1500,

			 ); 
				
				$users = get_users($args);
				
				foreach ($users as $user) {
					
					
					
				$user_ID = $user->ID;
				//update_user_meta($user_ID, 'reset_status', 0);}
				
				
				
				
				update_user_meta($user_ID, 'nuke_protection_timestamp', $timestamp+(48 * 3600));}
				/*
				if(get_user_meta($user_ID, 'reset_status', true) == 0){
			
				update_user_meta($user_ID, 'new_global_events', 0);
				update_user_meta($user_ID, 'new_events', 0);
				update_user_meta($user_ID, 'land_gained_combat',0);
				update_user_meta($user_ID, 'points_position', 0);
				update_user_meta($user_ID, 'last_attacked', '');
				update_user_meta($user_ID, 'networth_position', 0);
				update_user_meta($user_ID, 'user_clan_points', 0);
				update_user_meta($user_ID, 'name_change_counter', 0);
				update_user_meta($user_ID, 'clan_create_counter', 0);
				update_user_meta($user_ID, 'special_sold_today', 0);
				
				
				
				
				update_user_meta($user_ID, 'new_clan_timestamp', 0);
				update_user_meta($user_ID, 'aid_sent_today', 0);
				update_user_meta($user_ID, 'status', 'dead');
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
				update_user_meta($user_ID, 'stealth_sat_status', 'inactive');
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
				update_user_meta($user_ID, 'sold_land_today', 0);
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
	update_post_meta($clan->ID, 'war_array', '');

	
	
	
	
	}
	



	
	
	