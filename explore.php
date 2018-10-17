<?php
/**
 * Handles exploration
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
nocache_headers();
global $userId;
global $userData;


$array = array();

if (!defined('ABSPATH') || empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$ownedland = $userData['land'][0];
$explored_today = $userData['explored_today'][0];
$perturnm2 = 200-((ceil($ownedland*0.002)));
if (($perturnm2 < 50) && ($perturnm2 > 25)) {
	$perturnm2 = 50;
} elseif ($perturnm2 < 25) {
	$perturnm2 = 25;
}
$postedTurns = floor($_POST['turns']);

$freeland = $userData['builtland'][0]/$ownedland;


if ($postedTurns < 1 || !is_numeric(($postedTurns))) {
 
    $array['status'] = 'Enter a valid number';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if ($perturnm2 < 0) {
    $array['status'] = 'No more exploring possible';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
    
$turns = $userData['turns'][0];
if ((20000-$explored_today) < ($perturnm2*$postedTurns)) {
    $array['status'] = 'You can only explore '. number_format(20000-$userData['explored_today'][0], 0, ',', ' ').' m<sup>2</sup></strong> more land.';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if ($turns < $postedTurns) {
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
} else {
    update_user_meta($userId, 'turns', $turns-$postedTurns);
    update_user_meta($userId, 'land', $ownedland+($perturnm2*$postedTurns));
    update_user_meta($userId, 'explored_today', ($perturnm2*$postedTurns)+$explored_today);
	$exploredToday = ($perturnm2*$postedTurns)+$explored_today;
	count_all_stats($userId);
	
	
	$file = 'explorelog.txt';
    $current = file_get_contents($file);
    $time = current_time('G:i:s | d-m-Y');
    $current .= $time."\n";
    $current .= "ID: ".$userId."\n";
    $current .= "Turns used: ".$postedTurns."\n";
    $current .= "New land: ".$ownedland+($perturnm2*$postedTurns)."\n";
    $current .= "Explored today: ".$exploredToday."\n\n";
    file_put_contents($file, $current);
	
	
	$userData = get_user_meta($userId);
	
	$newperturnm2 = 200-((ceil($userData['land'][0]*0.002)));
	
	if (($newperturnm2 < 50) && ($newperturnm2 > 25)) {
		$newperturnm2 = 50;
	} elseif ($newperturnm2 < 25) {
		$newperturnm2 = 25;
	}
	
	$exploredToday = $userData['explored_today'][0];
	$maxAmount = floor((20000-$exploredToday)/$newperturnm2);
	
	$array['status'] = number_format($perturnm2*$postedTurns, 0, ',', ' ').' m<sup>2</sup> explored';
	$array['next'] = true;
	$array['networth'] = $userData['networth'][0];
	$array['turns'] = $turns-$postedTurns;
	$array['newrate'] = $newperturnm2;
	$array['land'] = $ownedland+($perturnm2*$postedTurns);
	$array['exploredtoday'] = "You have explored <strong>".number_format($exploredToday, 0, ',', ' ')."m<sup>2</sup></strong> today.
		You can explore an additional <strong>".number_format(20000-$exploredToday, 0, ',', ' ')."m<sup>2</sup></strong> <i>(".$maxAmount." turns)</i>";
	$array['maxturns'] = $maxAmount;
	echo json_encode($array);
	exit;
}
