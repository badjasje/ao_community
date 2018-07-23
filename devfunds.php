<?php
	require_once("wp-load.php");
	$gameType = get_field('game_type','option');
	if($gameType != 'Development'){
		exit;
	}
	if (empty($userId) || !is_user_logged_in()) {
	    $array['status'] = 'You must log in to perform this action';
	    $array['next'] = false;
	    echo json_encode($array);
	    exit;
	}

	$userId = get_current_user_id();
	$userData = get_user_meta($userId);
	$extraMoney = 250000;
	$extraTurns = 50;

	update_user_meta( $userId, 'money', $userData['money'][0]+$extraMoney);
	update_user_meta( $userId, 'turns', $userData['turns'][0]+$extraTurns);
	update_user_meta( $userId, 'morale', 100);
	update_user_meta( $userId, 'status', 'online');
	
$array['status'] = 'All set! $250 000, full morale and 50 turns received';
$array['money'] = $userData['money'][0]+$extraMoney;
$array['turns'] = $userData['turns'][0]+$extraTurns;
$array['morale'] = 100;
$array['next'] = true;
echo json_encode($array);
exit;