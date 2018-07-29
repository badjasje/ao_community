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

nocache_headers();

$userId = get_current_user_id();
$array = array();

if (!defined('ABSPATH') || empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}


$ownedland = get_user_meta($userId, 'land', true);
$money = get_user_meta($userId, 'money', true);
$sold_land_today = get_user_meta($userId, 'land_sold_today', true);
$builtLand = get_user_meta($userId, 'builtland', true);
$freeland = $ownedland-$builtLand;

if ($freeland < 0) {
    $array['status'] = 'Cannot sell! Not enough free land';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
if ($_POST['land'] < 0 || !is_numeric($_POST['land']) || $_POST['land'] > $freeland){
    $array['status'] = 'Enter a valid number';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if ((20000-$sold_land_today) >= $_POST['land']) {
	
    update_user_meta($userId, 'land', $ownedland-$_POST['land']);
    update_user_meta($userId, 'land_sold_today', $sold_land_today+($_POST['land']));
    update_user_meta($userId, 'money', $money+($_POST['land']*75));

	$soldLandToday = $sold_land_today+($_POST['land']);
	$freeLand = ($ownedland-$_POST['land']) - $builtLand;
	$maxSell = $freeLand < (20000 - $soldLandToday) ? $freeLand : (20000 - $soldLandToday);

    count_all_stats($userId);
    $userData = get_user_meta($userId);
    $array['status'] = 'You sold '.number_format($_POST['land'], 0, ',', ' ').' m<sup>2</sup> for a total sum of $ '.number_format($money+($_POST['land']*75), 0, ',', ' ');
	$array['next'] = true;
	$array['networth'] = $userData['networth'][0];
	$array['land'] = $userData['land'][0];
	$array['money'] = $userData['money'][0];
	$array['soldtoday'] = "1 m<sup>2</sup> has a value of $ 75. You have $freeLand m<sup>2</sup> of free land.
    You have sold <strong> $soldLandToday m<sup>2</sup></strong> today. You can sell an additional <strong> $maxSell m<sup>2</sup></strong>";
  
	echo json_encode($array);
	exit;
    
}

if ((20000-$sold_land_today) < $_POST['land']) {
    $array['status'] = 'Cannot sell any more land';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
