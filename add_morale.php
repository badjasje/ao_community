<?php
/**
 * Handles morale income
 */
include('constants.php');
require_once("wp-load.php");
nocache_headers();

$moraleIncome = $INCOME_MORALE;

if (get_field('game_status', 'option') != 'Live') exit;

$timestamp = current_time('timestamp');
$args = array(
    'meta_key'     	=> 'last_online',
    'orderby'      	=> 'meta_value_num',
    'meta_value'	=> $timestamp-86400,
    'meta_compare'	=> '>',
);
$users = get_users($args);
foreach ($users as $user) {
    $userId = $user->data->ID;
    $userData = get_user_meta($userId);
    
   
        
	if($userData['player_xp'][0] < 0){
		update_user_meta($userId, 'player_xp', 0);
	}
        

    $status = $userData['status'][0];
    if($status == 'banned' || $status == 'postmortem') continue;

    update_user_meta($userId, 'morale_lock', 1);
    AddSatPower($userId);
    AddMorale($userId, $moraleIncome);
    update_user_meta($userId, 'morale_lock', 0);
}

function AddSatPower($userId) {
	$userData = get_user_meta($userId);
    $currentSatPower = $userData['sat_morale'][0];
    if ($currentSatPower < 100) {
        update_user_meta($userId, 'sat_morale', $currentSatPower + 5);
        if($currentSatPower+5 >= 100) fcm_send_notification($userId, 'maxsatmorale');
        else update_user_meta($userId, 'max_satmorale_notified', 'no');
    }
}

function AddMorale($userId, $moraleIncome) {
	$userData = get_user_meta($userId);
    $currentMorale = $userData['morale'][0];
    $moralePool = $userData['morale_pool'][0];
    $takeFromPool = $moralePool > 0 && $currentMorale < 95;
    $moraleToAdd = $takeFromPool ? $moraleIncome + 5 : $moraleIncome;

    $currentNW = $userData['networth'][0];
    if ($currentNW > 3499) {

        if ($currentMorale < 100) {
            update_user_meta($userId, 'morale', min($currentMorale + $moraleToAdd, 100));
            if($currentMorale+$moraleToAdd >= 100 && $userData['status'][0]=='online') fcm_send_notification($userId, 'maxmorale');
            else update_user_meta($userId, 'max_morale_notified', 'no');
        }

        if ($takeFromPool) {
            update_user_meta($userId, 'morale_pool', $moralePool - 5);
        }

        if ($takeFromPool === false && $currentMorale > 95 && $moralePool < 100) {
            update_user_meta($userId, 'morale_pool', min($moralePool+5, 100));
        }

        if ($currentMorale == 100 && $moralePool == 100) {
            $morale_lost = $userData['morale_lost'][0];
            update_user_meta($userId, 'morale_lost', $morale_lost + 5);
        }
    }
}