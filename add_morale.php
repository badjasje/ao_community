<?php

/* handles morale income */

include('constants.php');
require_once("wp-load.php");

// Global.
$moraleIncome = $INCOME_MORALE;

if (get_field('game_status', 'option') == 'Live') {
    $users = get_users();
    foreach ($users as $user) {
        $userId = $user->data->ID;
        update_user_meta($userId, 'morale_lock', 1);
            AddSatPower($userId);
            AddMorale($userId, $moraleIncome);
        update_user_meta($userId, 'morale_lock', 0);
    }
}

function AddSatPower($userId)
{
    $currentSatPower = get_user_meta($userId, 'sat_morale', true);

    if ($currentSatPower < 100) {
        update_user_meta($userId, 'sat_morale', $currentSatPower + 5);
    }
}

function AddMorale($userId, $moraleIncome)
{
    $currentMorale = get_user_meta($userId, 'morale', true);
    $moralePool = get_user_meta($userId, 'morale_pool', true);
    $takeFromPool = $moralePool > 0 && $currentMorale < 95;
    $moraleToAdd = $takeFromPool ? $moraleIncome + 5 : $moraleIncome;

    if ($currentMorale < 100) {
        update_user_meta($userId, 'morale', min($currentMorale + $moraleToAdd, 100));
    }

    if ($takeFromPool) {
        update_user_meta($userId, 'morale_pool', $moralePool - 5);
    }
    if ($takeFromPool === false && $currentMorale > 95 && $moralePool < 100) {
        update_user_meta($userId, 'morale_pool', min($moralePool+5, 100));
    }

    if ($currentMorale == 100 && $moralePool == 100) {
        $morale_lost = get_user_meta($userId, 'morale_lost', true);
        update_user_meta($userId, 'morale_lost', $morale_lost + 5);
    }
}
