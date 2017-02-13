<?php
	require_once("wp-load.php");
	$users = get_users();
foreach ($users as $user) {

	$user_ID = $user->ID;
	$networth = get_user_meta($user_ID, 'networth', true);
	echo $user_ID.': '.$networth.'<br/>';
	}