<?php
/**
 * Handles turn income
 */
require_once("wp-load.php");
include('constants.php');

if (get_field('game_status', 'option') == 'Live') {
	$timestamp = current_time('timestamp');
    $turnsIncome = $INCOME_TURNS;
    $args = array(

		'meta_key'     	=> 'last_online',
		'orderby'      	=> 'meta_value_num',
		'meta_value'	=> $timestamp-362880,
		'meta_compare'	=> '>',

	 ); 


 $users = get_users($args);
    foreach ($users as $user) {
        $userId = $user->data->ID;
        $userData = get_user_meta($userId);
        
        $status = $userData['status'][0];
       
		 if($status == 'banned' ){
        	continue;
        }
        
        update_user_meta($userId, 'turn_lock', 1);
        AddTurns($userId);
        update_user_meta($userId, 'turn_lock', 0);
    }
}

function AddTurns($userId)
{	
	$userData = get_user_meta($userId);
    $currentTurns = $userData['turns'][0];

    if ($currentTurns < 300) {
        update_user_meta($userId, 'turns', $currentTurns + 1);
    }
}