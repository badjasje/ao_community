<?php
/**
 * Handles market orders
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
include 'satellite_array.php';
nocache_headers();


$userId = get_current_user_id();

if (! defined('ABSPATH')) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (empty($userId)) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (!is_user_logged_in()) {
	$array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$userData = get_user_meta($userId);

$totalmoney = $userData['money'][0];
$totalturns = $userData['turns'][0];
$satInProgress = (string)$userData['sat_in_progress'][0];
$sat_level = $userData['level_satellite_construction'][0];


if ($sat_level == 0) {
	$array['status'] = 'Research satellite construction you tool. And stop abusing. Found a bug? REPORT IT.';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$satdeduct = 1;
if ($sat_level >= 2) {
    $satdeduct = 0.8;
}

/* get key of satellite */
$ordered = $_POST['satellite'];

/* calculate order cost */
$satcost = $satellites[$ordered]['price']*$satdeduct;

/* Check if user has enough cash */
if ($totalmoney < $satcost) {
    $array['status'] = 'Insufficient funds';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if($satInProgress != '0'){
	$array['status'] = $satellites[$satInProgress]['name'].' already on order';
    $array['next'] = false;
    echo json_encode($array);
    exit;
	
}
if ($totalturns < 25) {
    $array['status'] = 'Not enough turns';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$args = array(
                'post_title'    => $satellites[$ordered]['name'],
                'post_status'   => 'publish',
                'post_type'     => 'market_order',
                'post_author'   => $userId
                );
                $timestamp = current_time('timestamp');
            
            $new_order_id = wp_insert_post($args);
            update_field('unit_type', $ordered, $new_order_id);
            update_field('user_placed_id', $userId, $new_order_id);
            update_field('time_placed', $timestamp, $new_order_id);
            update_field('delivery_time', $timestamp+(12 * 3600), $new_order_id);
            update_field('amount_ordered', 1, $new_order_id);
            update_field('order_value', $satcost, $new_order_id);
            update_field('order_type', 'satellite', $new_order_id);


            update_user_meta($userId, 'sat_in_progress', $ordered);
            update_user_meta($userId, 'money', $totalmoney-$satcost);
            update_user_meta($userId, 'turns', $totalturns-25);
            
$array['status'] = $satellites[$ordered]['name'].' ordered';
$array['money'] = $totalmoney-$satcost;
$array['turns'] = $totalturns-25;
$array['next'] = true;
echo json_encode($array);
exit;
