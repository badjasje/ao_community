<?php

/* handles morale income */

include('constants.php');

require_once("wp-load.php"); 
if(get_field('game_status','option') == 'Live'){
$users = get_users();
foreach ($users as $user) {
	$user_ID = $user->data->ID;

	
	$morale = get_user_meta($user_ID, 'morale')[0];
	
	$sat_morale = get_user_meta($user_ID, 'sat_morale',true);
	
	if($sat_morale < 100){
	update_user_meta($user_ID, 'sat_morale', $sat_morale+5);
	}
	
	
	$moralepool = get_user_meta($user_ID, 'morale_pool')[0];
	$additional_morale = 0;
	
	if($moralepool > 0){
		$additional_morale = 5;
	}

	$morale_income = $INCOME_MORALE;
	$morale_new = $morale + $morale_income;
	
	if($morale < 100){
		update_user_meta($user_ID, 'morale', min($morale_new+$additional_morale,100));	
		update_user_meta($user_ID, 'morale_pool', $moralepool-$additional_morale);	
	}
	if($morale == 100 && $moralepool < 100){
		update_user_meta($user_ID, 'morale_pool', $moralepool+5);	
	}
	
	if($morale == 100 && $moralepool == 100){
		$morale_lost = get_user_meta($user_ID, 'morale_lost', true);
		update_user_meta($user_ID, 'morale_lost', $morale_lost+5);	
	}
	
	
}}