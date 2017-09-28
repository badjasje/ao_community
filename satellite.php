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


$user_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3582));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}
$totalmoney = get_user_meta($user_ID, 'money', true);
$totalturns = get_user_meta($user_ID, 'turns', true);
$sat_level = get_user_meta($user_ID, 'level_satellite_construction', true);
if ($sat_level == 0) {
    $_SESSION['status'] = 'Research satellite construction you tool. And stop abusing. Found a bug? REPORT IT.';
    wp_redirect(get_permalink(8578));
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
    $_SESSION['status'] = 'Insufficient funds';
    wp_redirect(get_permalink(8578));
    exit;
}
if ($totalturns < 25) {
    $_SESSION['status'] = 'Not enough turns';
    wp_redirect(get_permalink(8578));
    exit;
}

$args = array(
                'post_title'    => $satellites[$ordered]['name'],
                'post_status'   => 'publish',
                'post_type'     => 'market_order',
                'post_author'   => $user_ID
                );
                $timestamp = current_time('timestamp');
            
            $new_order_id = wp_insert_post($args);
            update_field('unit_type', $ordered, $new_order_id);
            update_field('user_placed_id', $user_ID, $new_order_id);
            update_field('time_placed', $timestamp, $new_order_id);
            update_field('delivery_time', $timestamp+(12 * 3600), $new_order_id);
            update_field('amount_ordered', 1, $new_order_id);
            
            update_field('order_type', 'satellite', $new_order_id);


            update_user_meta($user_ID, 'sat_in_progress', $ordered);
            update_user_meta($user_ID, 'money', $totalmoney-$satcost);
            update_user_meta($user_ID, 'turns', $totalturns-25);
$_SESSION['status'] = 'Satellite ordered';
wp_redirect(get_permalink(8578));
exit;
