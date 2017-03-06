<?php
	
	require_once("wp-load.php");
$defender_networth_lost = 20000;
$clan_points = 3 * log($defender_networth_lost/2.4 / 400); 

echo $clan_points;