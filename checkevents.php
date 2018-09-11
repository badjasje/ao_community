<?php
    require(dirname(__FILE__) . '/wp-load.php');
    nocache_headers();
	
   // if (get_field('game_status', 'option') != 'Live') { exit; }
    $userId = get_current_user_id();
    $userDataFresh = get_user_meta($userId);

    $globals = $userDataFresh['new_global_events'][0];
    $locals = $userDataFresh['new_events'][0];
    $messages = $userDataFresh['new_messages'][0];
	
	$turns = $userDataFresh['turns'][0];
	$networth = $userDataFresh['networth'][0];
	$money = $userDataFresh['money'][0];
	$morale = $userDataFresh['morale'][0];
	$land = $userDataFresh['land'][0];
	$power = $userDataFresh['power'][0];
	
	$array = array(
		'globals' 	=> $globals,
		'locals' 	=> $locals, 
		'messages' 	=> $messages,
		'turns' 	=> $turns,
		'networth' 	=> $networth,
		'money' 	=> $money,
		'morale' 	=> $morale,
		'land' 		=> $land,
		'power' 	=> $power
	);
    echo json_encode($array);
    exit;