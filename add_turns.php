<?php
/**
 * Handles turn income
 */
require_once("wp-load.php");
include('constants.php');

if (get_field('game_status', 'option') == 'Live') {
    $turnsIncome = $INCOME_TURNS;
    $users = get_users();

    foreach ($users as $user) {
        $userID = $user->data->ID;
        $status = get_user_meta($userID,'status',true);
       
        if($status == 'banned' ){
        	continue;
        }
    
        $turnLock = get_user_meta($userID, 'turn_lock', true);

        update_user_meta($userID, 'turn_lock', 1);
    
        $turns = get_user_meta($userID, 'turns', true);
        if ($turns < 300) {
            update_user_meta($userID, 'turns', $turns + 1);
        } else {
            $turnsLost = get_user_meta($userID, 'turns_lost', true);
            update_user_meta($userID, 'turns_lost', $turnsLost+1);
        }

        update_user_meta($userID, 'turn_lock', 0);
    }
}
