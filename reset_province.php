<?php
/**
 * Handles reset province
 *
 * @package WordPress
 */
require(dirname(__FILE__) . '/wp-load.php');

$userId = get_current_user_ID();
$clanId = get_user_meta($userId, 'clan_id_user',true);

$incomingWars = get_posts(
    [
        'numberposts' => -1,
        'post_type' => 'wars',
        'meta_key' => 'declared_on',
        'meta_value' => $clanId
    ]
);

if (!defined('ABSPATH')) {
    $array['status'] = 'No can do';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$reset_status = get_user_meta($userId, 'reset_status', true);
if(Round::isDev() || Round::isTest()) $reset_status = false; //You may reset more than once
if(!empty($reset_status)) {
    $array['status'] = 'You have already reset this round';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if (count($incomingWars) < 1) {
    update_user_meta($userId, 'status', 'dead');
    update_user_meta($userId, 'reset_status', 1);
    $moneyThieved = get_user_meta( $userId, 'money_gained_thieving', true );

    if(($moneyThieved-20000000) <= 0){
	    $newValue = 0;
    }else{
	    $newValue = $moneyThieved-20000000;
    }


    update_user_meta( $userId, 'money_gained_thieving', $newValue );

    $array['status'] = 'Account has been reset';
	$array['next'] = true;
	echo json_encode($array);
	exit;

} else {
    $array['status'] = 'You cannot reset your account while having incoming clan wars';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
