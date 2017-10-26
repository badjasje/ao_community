<?php

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}
    
require(dirname(__FILE__) . '/wp-load.php');
if (get_field('game_status', 'option') != 'Live') {
    exit();
}

include 'units_array.php';
include 'satellite_array.php';
$orderId = $_POST['order'];

$orderStatus = get_post_status($orderId);

if (!defined('ABSPATH')) {
    exit;
}

if (empty($userId) || !is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}


if ($orderStatus == 'trash') {
    $_SESSION['status'] = 'nope';
    wp_redirect(get_permalink(3582));
    exit;
}

// Cancel the order.
$userId = get_current_user_id();

$userLock = get_user_meta($userId, 'user_lock', true);

if ($userLock == 1) {
    exit;
}
update_user_meta($userId, 'user_lock', 1);


$userPlacedId = get_post_meta($orderId, 'user_placed_id', true);
if ($userId != $userPlacedId) {
    wp_redirect(get_permalink(3204));
    exit;
}

$unitType = get_post_meta($orderId, 'unit_type', true);
$orderType = get_post_meta($orderId, 'order_type', true);

$unitsOrdered = get_post_meta($orderId, 'amount_ordered', true);
$ownedUnits = get_user_meta($userId, $unitType.'_owned', true);

$totalUnitsOnOrder = get_user_meta($userId, $unitType.'_ordered', true);
$unitPrice = $units[$unitType]['price']*2.2*$discount;

$orderValue = get_post_meta($orderId, 'order_value', true);


$cashback = $orderValue * 0.75;
$unitOwnerMetaKey = $unitType.'_owned';
update_user_meta($userId, $unitType.'_ordered', $totalUnitsOnOrder - $unitsOrdered);
wp_trash_post($orderId);

if ($orderType == 'satellite') {
    update_user_meta($userId, 'sat_in_progress', 0);
    $cashback = $satellites[$unitType]['price']*0.75;
}
    $totalmoney = get_user_meta($userId, 'money', true);
    update_user_meta($userId, 'money', $totalmoney+$cashback);

    update_user_meta($userId, 'user_lock', 0);
    $_SESSION['status'] = 'Order canceled. You received $ '.number_format($cashback, 0, ',', ' ');
    wp_redirect(get_permalink(3204));
exit;
