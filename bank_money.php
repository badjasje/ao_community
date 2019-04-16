<?php
/**
 * Handles bank deposits
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

include 'interest_array.php';
require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

nocache_headers();

if (!is_numeric($_POST['amount'])) {
    $array['status'] = 'Enter a valid number';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if ($_POST['amount'] <= 0) {
    $array['status'] = 'Enter a valid number';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

/* Get some important variables */
global $userId;
global $userData;

$userLock = get_user_meta($userId, 'user_lock', true);

if ($userLock == 1) {
    update_user_meta($userId, 'user_lock', 0);
    $array['status'] = 'Please try again';
    $array['next'] = false;
    echo json_encode($array);
    exit;
} else {
    update_user_meta($userId, 'user_lock', 1);

    if (! defined('ABSPATH')) {
        exit;
    }

    if (empty($userId)) {
        $array['status'] = 'No user ID set. WHO ARE YOU!?';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    if (!is_user_logged_in()) {
        wp_redirect(get_permalink(3582));
        exit;
    }

    $money = $userData['money'][0];

    /* check if user actually has enough cash */
    if ($money < $_POST['amount']) {
        $array['status'] = 'Insufficient funds';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    $deposits = $userData['total_deposits'][0];
    if (empty($deposits)) {
        $deposits = 0;
    }

    $args = array(
        'posts_per_page'   => -1,
        'author'       => $userId,
        'post_type'        => 'deposit'
    );
    $_deposits = get_posts($args);

    $tot_deposited = 0;

    /* Get total amount of deposited money */
    foreach ($_deposits as $_deposit) {
        $tot_deposited+=get_post_meta($_deposit->ID, 'amount')[0];
    }

    /* Get banking level and max values */
    $bankLevel = $userData['level_bank_management'][0];
    $startingBonus = $userData['starting_bonus'][0];
    $finance_multi = 1;
    $extra_interest = 0;
    if ($startingBonus == 'finance') {
        $finance_multi = 1.5;
    }

    if ($bankLevel == 1) {
        $extra_interest = 0.5;
        $max_dep = 350000*$finance_multi;
        //$max_tot = 3500000;
    } elseif ($bankLevel == 2) {
        $extra_interest = 0.5;
        $max_dep = 450000*$finance_multi;
        //$max_tot = 4500000;
    } elseif ($bankLevel == 3) {
        $extra_interest = 0.75;
        $max_dep = 500000*$finance_multi;
        //$max_tot = 5000000*$finance_multi;
    } else { // BankLevel == null/empty
        $extra_interest = 0;
        $max_dep = 250000*$finance_multi;
        //$max_tot = 2500000*$finance_multi;
    }

    /* check for minimum value */
    if ($_POST['amount'] < 5000) {
        $array['status'] = 'Deposit at least $ 5000';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    /* check amount of deposits made, max 10 */
    if ($deposits >= 10) {
        $array['status'] = 'You already made 10 deposits';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    /* check if the sum of the amount + the amount already deposited doesn't exceed the max set by research * /
    if ($tot_deposited+$_POST['amount'] > $max_tot) {
        $array['status'] = 'The total sum exceeds the amount of deposited money you can have at this time';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }*/


    /* check if deposit doesn't exceed the max deposit based on research */
    if ($_POST['amount'] > $max_dep) {
        $array['status'] = "Your research doesn't allow you to deposit this much";
        $array['next'] = false;
        echo json_encode($array);
        exit;
    } else {
        /* Create the actual deposit */
        $timestamp = current_time('timestamp');
        $RELEASE_DATE = $timestamp+($_POST['days']*86400);

        $args = array(
            'post_title'    => $RELEASE_DATE,
            'post_status'   => 'publish',
            'post_type'     => 'deposit',
            'post_author'   => $userId
        );

        $new_order_id = wp_insert_post($args);
        update_post_meta($new_order_id, 'release_date', $RELEASE_DATE);
        update_post_meta($new_order_id, 'deposit_placed', $timestamp);
        update_post_meta($new_order_id, 'days', $_POST['days']);
        update_post_meta($new_order_id, 'amount', $_POST['amount']);
        update_user_meta($userId, 'money', $money-$_POST['amount']);
        update_user_meta($userId, 'total_deposits', $deposits+1);

        /* return to banking page succesful */
        update_user_meta($userId, 'user_lock', 0);

        $userData = get_user_meta($userId);

        $banklevel = $userData['level_bank_management'][0];
        $money = $userData['money'][0];
        $startingbonus = $userData['starting_bonus'][0];
        $finance_multi = 1;
        if($startingbonus == 'finance'){
            $finance_multi = 1.5;
        }

        if($banklevel == 0){
            $extra_interest = 0;
            $max_dep = 250000*$finance_multi;
            //$max_tot = 2500000*$finance_multi;
        }
        if($banklevel == 1){
            $extra_interest = 0.5;
            $max_dep = 350000*$finance_multi;
            //$max_tot = 3500000;
        }
        if($banklevel == 2){
            $extra_interest = 0.5;
            $max_dep = 450000*$finance_multi;
            //$max_tot = 4500000;
        }
        if($banklevel == 3){
            $extra_interest = 0.75;
            $max_dep = 500000*$finance_multi;
            //$max_tot = 5000000*$finance_multi;
        }
        $maxDepositAmount = floor(min($max_dep,$money));

        $array['status'] = '$ '.$_POST['amount'].' deposited for '.$_POST['days'].' days';
        $array['newmaxdep'] = $maxDepositAmount;
        $array['next'] = true;
        $array['money'] = number_format($money, 0, ',', ' ');
        $array['depid'] = $new_order_id;
        $array['deposited'] = $_POST['amount'];
        $array['releasedate'] = date('H:i | d-m-Y', $RELEASE_DATE);
        $array['inclinterest'] = $_POST['amount']*pow($rates[$_POST['days']]['interest']+($extra_interest/100),$_POST['days']);
        $array['deposits'] = count_deposits($userId);
        echo json_encode($array);
        exit;
    }
}