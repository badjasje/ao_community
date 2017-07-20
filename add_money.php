<?php 

/* handles hourly monetary income */
/* this is a test comment for git */
include('constants.php');

require_once("wp-load.php"); 

if(get_field('game_status','option') == 'Live'){
$users = get_users();
foreach ($users as $user) {
	$user_ID = $user->data->ID;


	
	$money_production_level = get_user_meta($user_ID, 'level_money_production')[0];
	$money = get_user_meta($user_ID, 'money')[0];
	
	$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
	$finance_multi = 1;
	if($startingbonus == 'finance'){
		$finance_multi = 1.1;
	}
	
	if($money_production_level == 0 || empty($money_production_level)){
		$money_income = $INCOME_MONEY*$finance_multi;
	}
	if($money_production_level == 1){
		$money_income = $INCOME_MONEY+10000*$finance_multi;
	}
	if($money_production_level == 2){
		$money_income = $INCOME_MONEY+20000*$finance_multi;
	}
	
	$money_new = $money + $money_income;

	error_log("new money:".$money_new);
	$timestamp = current_time('timestamp');
	$last_online = get_user_meta($user_ID, 'last_online', true);
	$difference = $timestamp-$last_online;
	if($difference < 259200){
	update_user_meta($user_ID, 'money', $money_new);	
	}
}
}
/* Updating nw position */
				$args = array(
					'meta_key' => 'networth',
					'orderby'    => 'meta_value_num',
					'order'      => 'DESC',
					'cache_results'  => false);
				
				 $user_query = new WP_User_Query($args);
				 	$position = 0;
				 	foreach ( $user_query->results as $user ) {
					 	//$user_NW = get_user_meta($user->ID, 'networth');
						update_user_meta($user->ID, 'networth_position', $position+=1);
					 	 //count_all_stats($user->ID);
				 	}

/* Updating pts position */
				$position = 0;
				$args = array(
					'orderby'    => 'meta_value_num',
					'meta_key' => 'user_clan_points',
					'order'      => 'DESC');
				$users = get_users($args);
				
				foreach ($users as $user) {
					$user_NW = get_user_meta($user->ID, 'user_clan_points');
				update_user_meta($user->ID, 'points_position', $position+=1);
				}