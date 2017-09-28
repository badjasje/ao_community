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
include 'interest_array.php';

/* get some essential variables */

$user_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3953));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3953));
    exit;
}
$deposit = $_POST['deposit'];

if (get_post_status($deposit) == 'trash') {
    wp_redirect(get_permalink(3953));
    exit;
}

$author = get_post_field('post_author', $deposit);

if ($user_ID != $author) {
    wp_redirect(get_permalink(3953));
    exit;
}

$userLock = get_user_meta($user_ID, 'user_lock', true);

if ($userLock == 1) {
    update_user_meta($user_ID, 'user_lock', 0);
    $_SESSION['status'] = 'Please try again.';
    wp_redirect(get_permalink(3582));
    exit;
} else {
    update_user_meta($user_ID, 'user_lock', 1);

    $money = get_user_meta($user_ID, 'money', true);


    $deposits = get_user_meta($user_ID, 'total_deposits', true);
    $timestamp = current_time('timestamp');





    $time_left = get_post_meta($deposit, 'release_date', true)-$timestamp;
    $banklevel = get_user_meta($user_ID, 'level_bank_management', true);

    if ($banklevel == 0) {
        $extra_interest = 0;
    }
    if ($banklevel == 1) {
        $extra_interest = 0.5;
    }
    if ($banklevel == 2) {
        $extra_interest = 0.75;
        $early_penalty = 0.75;
    }
    if ($banklevel == 3) {
        $extra_interest = 1;
        $early_penalty = 1;
    }




/* Checks if duration has passed. If it has, money is updated including interest */
    if ($time_left < 0) {
        $amount = get_post_meta($deposit, 'amount', true);
        $days = get_post_meta($deposit, 'days', true);
        $total_incl_interest = ceil($amount*pow($rates[$days]['interest']+($extra_interest/100), $days));
    

    

            update_user_meta($user_ID, 'money', $money+$total_incl_interest);
            update_user_meta($user_ID, 'total_deposits', $deposits-1);
            wp_trash_post($deposit);
        $_SESSION['status'] = '$ '.number_format($total_incl_interest, 0, ',', ' ').' withdrawn';

        wp_redirect(get_permalink(3953));
        exit;
    } /* else, someone with a bankmanagement research of 2 and over canceled it's bank deposit */
    else {
        $amount = get_post_meta($deposit, 'amount', true)*$early_penalty;


        update_user_meta($user_ID, 'money', $money+$amount);
        update_user_meta($user_ID, 'total_deposits', $deposits-1);
        wp_trash_post($deposit);
    
        $_SESSION['status'] = 'You canceled your deposit. '.number_format($amount, 0, ',', ' ').' withdrawn';
        update_user_meta($user_ID, 'user_lock', 0);
        wp_redirect(get_permalink(3953));
        exit;
    }
}
