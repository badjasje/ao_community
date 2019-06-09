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

nocache_headers();

if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$userId = get_current_user_id();

if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (strlen(implode("",$_POST)) <= 0) {
    $array['status'] = 'Select 1 or more missiles to buy';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$totalmoney = get_user_meta($userId, 'money', true);

$turns = get_user_meta($userId, 'turns', true);

$missilespace = get_user_meta($userId, 'silo', true);
$tomahawkspace = get_user_meta($userId, 'submarine_owned', true)*2;

include 'missiles_array.php';
include 'count_functions.php';

$totalordercost = 0;
$totalturncost = 0;
foreach ($missiles as $key => $order) {
    $price = $order['price'];
    $ordered_missiles = abs(ceil($_POST["$key"]));

    if (empty($_POST["$key"])) {
        $letter_check = 0;
    } else {
        $letter_check = $_POST["$key"];
    }

    if (!is_numeric($letter_check)) {
        $array['status'] = 'Enter a valid number';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    if ($key != 'tomahawk') {
        $orderamount = $price*$ordered_missiles;
        $totalordercost+=$orderamount;
        $totalturncost+=$ordered_missiles*5;
    }
    if ($key == 'tomahawk') {
        $orderamount = $price*$ordered_missiles;
        $totalordercost += $orderamount;
        $totalturncost += abs(ceil($ordered_missiles/3));
    }
}
if ($totalordercost > $totalmoney) {
    $array['status'] = 'Insufficient funds';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if ($turns < $totalturncost) {
    $array['status'] = 'Not enough turns';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$mis = 0;
$total_missile_ordered = 0;

$startingbonus = get_user_meta($userId, 'starting_bonus', true);
$shipping_speed = 1;
if ($startingbonus == 'shipping') {
    $shipping_speed = 0.5;
}

// CHECK MISSILESPACE //
foreach ($missiles as $key => $order) {
    if ($key != 'tomahawk') {
        $missile_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_missiles = abs(ceil($_POST["$key"]));
        $mis+=$ordered_missiles;

        $owned_missiles = get_user_meta($userId, $key.'_owned', true);
        $missiles_already_on_order = get_user_meta($userId, $key.'_ordered', true);
        $owned_missiles = (!empty($owned_missiles) ? $owned_missiles : 0);
        $missiles_already_on_order = (!empty($missiles_already_on_order) ? $missiles_already_on_order : 0);

        $total_missile_ordered+=$ordered_missiles+$owned_missiles+$missiles_already_on_order;
    }

    if ($mis>0) {
        if ($total_missile_ordered > $missilespace) {
            $array['status'] = 'Build more missile silos';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
    }

    if ($key == 'tomahawk') {
        $owned_tomahawks = get_user_meta($userId, $key.'_owned', true);
        $tomahawks_already_on_order = get_user_meta($userId, $key.'_ordered', true);
        $owned_tomahawks = (!empty($owned_tomahawks) ? $owned_tomahawks : 0);
        $tomahawks_already_on_order = (!empty($tomahawks_already_on_order) ? $tomahawks_already_on_order : 0);

        $ordered_tomahawks = ceil($_POST["$key"]);

        $total_tomahawks_ordered = $ordered_tomahawks + $tomahawks_already_on_order + $owned_tomahawks;

        if ($ordered_tomahawks > 0) {
            if ($total_tomahawks_ordered > $tomahawkspace) {
                $array['status'] = 'You need more submarines to house the ordered amount of tomahawk missiles';
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
    }
}

// BUILD MISSILES //
$total_missiles_ordered = 0;
foreach ($missiles as $key => $order) {
    $missile_name = $key.'_ordered';

    $normalname = $order['normalname'];
    $price = $order['price'];
    $ordered_missiles = ceil($_POST["$key"]);
    $total_missiles_ordered+=$ordered_missiles;

    if ($ordered_missiles > 0) {
        $missiles_on_order = get_user_meta($userId, $missile_name);
        $missiles_on_order = $missiles_on_order[0];
        update_user_meta($userId, 'money', $totalmoney-$totalordercost);
        update_user_meta($userId, 'turns', $turns-$totalturncost);

        update_user_meta($userId, $missile_name, $missiles_on_order+$ordered_missiles);

        $args = array(
            'post_title'    => $order['normalname'],
            'post_status'   => 'publish',
            'post_type'     => 'market_order',
            'post_author'   => $userId
        );
        $timestamp = current_time('timestamp');

        $new_order_id = wp_insert_post($args);
        update_field('unit_type', $key, $new_order_id);
        update_field('user_placed_id', $userId, $new_order_id);
        update_field('time_placed', $timestamp, $new_order_id);
        update_field('delivery_time', $timestamp+(6 * 3600*$shipping_speed), $new_order_id);
        update_field('amount_ordered', $ordered_missiles, $new_order_id);
        update_field('order_type', 'missile', $new_order_id);
        update_field('order_value', $price, $new_order_id);
    }
}

$userData = get_user_meta($userId);

$allOrdered = array();
$newMax = array();

$missilespace = $userData['silo'][0];
$totalMoney = $userData['money'][0];
$totalturns = $userData['turns'][0];
$tomahawkspace = $userData['submarine_owned'][0]*2;
$missileAccLevel = $userData['level_missile_accuracy'][0];

$totalmissiles = count_missilespace($userId);

foreach ($missiles as $key => $missile) {
	if($key != 'tomahawk'){
		$max_money = floor($totalMoney/($missile['price']));
		$max_turns = floor($totalturns*5);
		$max_space = $missilespace-$totalmissiles;
	}else{
		$max_money = floor($totalMoney/($missile['price']));
		$max_turns = round($totalturns/3);
		$max_space = $tomahawkspace-$userData['tomahawk_owned'][0]-$userData['tomahawk_ordered'][0];
	}

    $ordered = (isset($userData[$key.'_ordered']) ? $userData[$key.'_ordered'][0] : 0);

    if($ordered > 0) {
        $allOrdered[$key] = $ordered;
    }

	$newMax[$key] = min($max_money, $max_turns, $max_space);
}

$array['status'] = $total_missiles_ordered.' missile'.plural_func($total_missiles_ordered).' ordered for '.$totalturncost.' turn'.plural_func($totalturncost).' and $ '.number_format($totalordercost, 0, ',', ' ');
$array['next'] = true;
$array['turns'] = $turns-$totalturncost;
turn_spread('missiles',$totalturncost);
$array['money'] = $totalmoney-$totalordercost;
$array['allordered'] = $allOrdered;
$array['newmax'] = $newMax;
echo json_encode($array);
exit;