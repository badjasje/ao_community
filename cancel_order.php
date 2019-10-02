<?php

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

$units = Units::get();
include 'satellite_array.php';
$orderId = $_POST['order'];
$array = array();
$orderStatus = get_post_status($orderId);

if (!defined('ABSPATH')) {
    exit;
}

if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}


if ($orderStatus == 'trash') {
   	$array['status'] = 'NEIN NEIN NEIN';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

// Cancel the order.
global $userId;
global $userData;

$userLock = get_user_meta($userId, 'user_lock', true);

if ($userLock == 1) {
    exit;
}
update_user_meta($userId, 'user_lock', 1);

// Determine market discount multiplier
$marketDiscountLevel = $userData['level_market_discount'][0];
$discount = 1.0;
if($marketDiscountLevel == 1){
    $discount = $discount - 0.15;
} elseif($marketDiscountLevel >= 2){
    $discount = $discount - 0.3;
}



$userPlacedId = get_post_meta($orderId, 'user_placed_id', true);
if ($userId != $userPlacedId) {
    $array['status'] = "These are not the orders you're looking for";
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$unitType = get_post_meta($orderId, 'unit_type', true);
$orderType = get_post_meta($orderId, 'order_type', true);

$unitsOrdered = get_post_meta($orderId, 'amount_ordered', true);
$ownedUnits = (isset($userData[$unitType.'_owned']) ? $userData[$unitType.'_owned'][0] : 0);
$totalUnitsOnOrder = (isset($userData[$unitType.'_ordered']) ? $userData[$unitType.'_ordered'][0] : 0);
$unitPrice = (isset($units[$unitType]) ? $units[$unitType]['price']*2.2*$discount : 0);

$orderValue = get_post_meta($orderId, 'order_value', true);


$cashback = $orderValue * 0.75;
$unitOwnerMetaKey = $unitType.'_owned';
update_user_meta($userId, $unitType.'_ordered', $totalUnitsOnOrder - $unitsOrdered);
wp_trash_post($orderId);
if ($orderType == 'satellite') {
    update_user_meta($userId, 'sat_in_progress', 0);
    $cashback = $satellites[$unitType]['price']*0.75;
}
$totalmoney = $userData['money'][0];
update_user_meta($userId, 'money', $totalmoney+$cashback);

update_user_meta($userId, 'user_lock', 0);
$array['status'] = 'Order canceled. You received $ '.number_format($cashback, 0, ',', ' ');
$array['remove'] = $orderId;
$array['money'] = $totalmoney+$cashback;
echo json_encode($array);
exit;