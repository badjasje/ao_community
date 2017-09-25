<?php

/* handles turn income */
require_once("wp-load.php");
include('constants.php');
if (get_field('game_status', 'option') == 'Live') {
    $turns_income = $INCOME_TURNS;
    $users = get_users();
    foreach ($users as $user) {
        $user_ID = $user->data->ID;
        $turnLock = get_user_meta($user_ID, 'turn_lock', true);
        update_user_meta($user_ID, 'turn_lock', 1);
    
        $turns = get_user_meta($user_ID, 'turns', true);
    
        if ($turns < 300) {
            update_user_meta($user_ID, 'turns', $turns + 1);
        } else {
            $turns_lost = get_user_meta($user_ID, 'turns_lost', true);
            update_user_meta($user_ID, 'turns_lost', $turns_lost+1);
        }
            update_user_meta($user_ID, 'turn_lock', 0);
    }
}
