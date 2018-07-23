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
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$array = array();
nocache_headers();
include 'interest_array.php';

/* get some essential variables */

$userId = get_current_user_id();

if (! defined('ABSPATH')) {
    $array['status'] = 'Nope';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
if (empty($userId)) {
    $array['status'] = 'Please log in.';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'Please log in.';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$deposit = $_POST['deposit'];

if (get_post_status($deposit) == 'trash') {
    $array['status'] = 'Already withdrawn.';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$author = get_post_field('post_author', $deposit);

if ($userId != $author) {
    $array['status'] = "These are not the deposits you're looking for.";
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$userLock = get_user_meta($userId, 'user_lock', true);

if ($userLock == 1) {
    update_user_meta($userId, 'user_lock', 0);
	$array['status'] = "Please try again.";
	$array['next'] = false;
	echo json_encode($array);
	exit;
} else {
    update_user_meta($userId, 'user_lock', 1);

    $money = get_user_meta($userId, 'money', true);


    $deposits = get_user_meta($userId, 'total_deposits', true);
    $timestamp = current_time('timestamp');





    $time_left = get_post_meta($deposit, 'release_date', true)-$timestamp;
    $banklevel = get_user_meta($userId, 'level_bank_management', true);

    if ($banklevel == 0) {
        $extra_interest = 0;
    }
    if ($banklevel == 1) {
        $extra_interest = 0.5;
    }
    if ($banklevel == 2) {
        $extra_interest = 0.75;
        $early_penalty = 0.5;
    }
    if ($banklevel == 3) {
        $extra_interest = 1;
        $early_penalty = 0.75;
    }




/* Checks if duration has passed. If it has, money is updated including interest */
    if ($time_left < 0) {
        $amount = get_post_meta($deposit, 'amount', true);
        $days = get_post_meta($deposit, 'days', true);
        $total_incl_interest = ceil($amount*pow($rates[$days]['interest']+($extra_interest/100), $days));
    
        update_user_meta($userId, 'money', $money+$total_incl_interest);
        update_user_meta($userId, 'total_deposits', $deposits-1);
        wp_trash_post($deposit);
   
        $array['status'] = '$ '.number_format($total_incl_interest, 0, ',', ' ').' withdrawn';
        $array['money'] = number_format($money+$total_incl_interest, 0, ',', ' ');
        $array['deposits'] = count_deposits($userId); 
        $array['removeid'] = $deposit;
		$array['next'] = true;
		echo json_encode($array);
		update_user_meta($userId, 'user_lock', 0);
		exit;

  
    } /* else, someone with a bankmanagement research of 2 and over canceled it's bank deposit */
    else {
        $amount = get_post_meta($deposit, 'amount', true)*$early_penalty;


        update_user_meta($userId, 'money', $money+$amount);
        update_user_meta($userId, 'total_deposits', $deposits-1);
        wp_trash_post($deposit);
    
    
        $array['status'] = 'You canceled your deposit. '.number_format($amount, 0, ',', ' ').' withdrawn';
        $array['money'] = number_format($money+$amount, 0, ',', ' ');
        $array['deposits'] = count_deposits($userId); 
        $array['removeid'] = $deposit;
		$array['next'] = true;
		echo json_encode($array);
		update_user_meta($userId, 'user_lock', 0);
		exit;
		
    }
}
