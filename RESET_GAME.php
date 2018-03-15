<?php
    /*
    require_once("wp-load.php");
	require_once("coinhive-api.php");
	$coinhive->post('/user/reset-all');
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
	$resetArray[] = "'research_in_progress'";
	
	
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
	$resetArray[] = "'explored_today'";
	$resetArray[] = "'empmis_ordered'";
	$resetArray[] = "'empmis_owned'";
	$resetArray[] = "'tomahawk_ordered'";
	$resetArray[] = "'tomahawk_owned'";
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
	$resetArray[] = "'sat_endlife'";
	
	
	
	
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
	$resetArray[] = "'modev_prev'";
	$resetArray[] = "'modev_next'";
	
	
	
	
	
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
			WHERE meta_key IN('bonus_level','clan_points','clan_networth','clan_name_change','ua_total','ub_total','24h_pts')
            ");
	// Setting variables to empty array
	$emptyArray = maybe_serialize(array());
	$wpdb->query("
			UPDATE `${table_prefix}postmeta`
			SET meta_value = ''
			WHERE meta_key IN('cooldown_list','previous_members','24h_pts_list','open_invites','24h_nw_list','war_array')
            ");
	

	
	/* OLD STUFF BELOW
		
	