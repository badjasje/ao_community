<?php
	require_once("wp-load.php");
	include 'weather_array.php';
	$weather = array_rand($weather);
	update_field('weather', $weather, 'options');