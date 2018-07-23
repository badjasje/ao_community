<?php
    require_once("wp-load.php");
    if (get_field('game_status', 'option') != 'Live') { exit; }
    $user_ID = get_current_user_id();
    $globals = get_user_meta($user_ID, 'new_global_events', true);
    $locals = get_user_meta($user_ID, 'new_events', true);
    $messages = get_user_meta($user_ID, 'new_messages', true);
	$array = array('globals' => $globals,'locals' => $locals, 'messages' => $messages);
    echo json_encode($array);
    exit;