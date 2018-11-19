<?php
	require_once("wp-load.php");


	$ip_array = maybe_unserialize(get_field('login_array_general',139664));
	$isps = array();
	foreach ($ip_array as $ip => $userdata):
	
		foreach ($userdata as $userId => $data):
		
			$geodata = json_decode($userdata[$userId][3]);
			
			$isps[] = $geodata->data->geo->isp.' | '.$geodata->data->geo->rdns;
			
		
		endforeach;
	
	endforeach;

echo '<pre>';
print_r(array_unique($isps));
echo '</pre>';