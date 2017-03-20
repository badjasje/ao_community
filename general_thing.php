<?php
	
	require_once("wp-load.php");

$def_NW_lost = 4000;

$clan_points = 8 * log($def_NW_lost/1.4 / 400); 

echo $clan_points;