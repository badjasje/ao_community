<?php
/**
 * Handles hourly monetary income
 */
include('constants.php');

require_once("wp-load.php");

if (get_field('game_status', 'option') == 'Live') {
    $users = get_users();
    foreach ($users as $user) {
        $userId = $user->data->ID;
		$status = get_user_meta($userId,'status',true);
       
        if($status == 'banned' ){
        	continue;
        }
	        $money_production_level = get_user_meta($userId, 'level_money_production')[0];
	        $money = get_user_meta($userId, 'money')[0];
    
	        $startingBonus = get_user_meta($userId, 'starting_bonus', true);
	        $finance_multi = 1;
	        if ($startingBonus == 'finance') {
	            $finance_multi = 1.1;
	        }
    
	        if ($money_production_level == 0 || empty($money_production_level)) {
	            $moneyIncome = 15000*$finance_multi;
	        }
	        if ($money_production_level == 1) {
	            $moneyIncome = 25000*$finance_multi;
	        }
	        if ($money_production_level == 2) {
	            $moneyIncome = 35000*$finance_multi;
	        }
    
			$moneyNew = $money + $moneyIncome;

	        $timestamp = current_time('timestamp');
	        $lastOnline = (int)get_user_meta($userId, 'last_online', true);
	        $difference = $timestamp - $lastOnline;
	        if ($difference < 259200) {
	            update_user_meta($userId, 'money', $moneyNew);
	        }
	    }
    }

/* Updating nw position */
$args = [
    'meta_key' => 'networth',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'cache_results' => false
];

$user_query = new WP_User_Query($args);
$position = 0;

foreach ($user_query->results as $user) {
    //$user_NW = get_user_meta($user->ID, 'networth');
    update_user_meta($user->ID, 'networth_position', $position+=1);
    //count_all_stats($user->ID);
}

/* Updating pts position */
$position = 0;
$args = [
    'orderby' => 'meta_value_num',
    'meta_key' => 'user_clan_points',
    'order' => 'DESC'
];
$users = get_users($args);

foreach ($users as $user) {
    //$user_NW = get_user_meta($user->ID, 'user_clan_points'); This value is unused...
    update_user_meta($user->ID, 'points_position', $position+=1);
}