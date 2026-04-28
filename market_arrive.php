<?php
require(dirname(__FILE__) . '/wp-load.php');

if (get_field('game_status', 'option') != 'Live') { exit; }

$timestamp = current_time('timestamp');
$lock_grace_seconds = 600; // Fallback for stale locks so overdue orders do not get stuck forever
$args = array(
    'posts_per_page'   	=> -1,
    'post_status'      	=> 'publish',
    'meta_key'     		=> 'delivery_time',
    'meta_value'		=> $timestamp,
    'meta_compare'		=> '<',
    'post_type'        	=> 'market_order',
);
$the_query = new WP_Query($args);

if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {
        $the_query->the_post();
        $orderID = get_the_id();

        $orderData = get_post_meta($orderID);
        $user_ID = $orderData['user_placed_id'][0];
        $userData = get_user_meta($user_ID);

        $delivery_time = $orderData['delivery_time'][0];

        $moraleLock = intval(get_user_meta($user_ID, 'morale_lock', true));
        $turnLock = intval(get_user_meta($user_ID, 'turn_lock', true));

        $timeleft = $delivery_time - $timestamp;
        $lockActive = ($moraleLock !== 0 || $turnLock !== 0);
        $staleLock = ($lockActive && abs($timeleft) >= $lock_grace_seconds);

        // Normally wait for active locks, but do not let stale locks block delivery forever.
        if ($staleLock) {
            update_user_meta($user_ID, 'morale_lock', 0);
            update_user_meta($user_ID, 'turn_lock', 0);
            $lockActive = false;
        }

        if ($timeleft <= 0 && !$lockActive) {
            $unit_type = $orderData['unit_type'][0];

            /* check if order is satellite */
            $sats = array('laser','comsat','stealths','spysat','spysat','amssat','empsat');

            if (!in_array($unit_type, $sats)) {

                $units_in_this_order = $orderData['amount_ordered'][0];
                $ownedunits = $userData[$unit_type.'_owned'][0];
                $total_units_on_order = $userData[$unit_type.'_ordered'][0];

                update_field($unit_type.'_ordered', $total_units_on_order - $units_in_this_order, 'user_'.$user_ID);
                update_field($unit_type.'_owned', $units_in_this_order+$ownedunits, 'user_'.$user_ID);

                wp_trash_post($orderID);
            }

            if (get_field('order_type', $orderID) == 'satellite') {
                $sat_level = $userData['level_satellite_construction'][0];
                $days = 11;
                if ($sat_level > 1) {
                    $days = 16;
                }
                update_user_meta($user_ID, 'sat_owned', $unit_type);
                update_user_meta($user_ID, 'sat_in_progress', 0);
                update_user_meta($user_ID, 'sat_endlife', $timestamp+($days*86400));
                wp_trash_post($orderID);
            }

            /* trash order post */
            //wp_delete_post($order->ID);

            count_all_stats($user_ID);
            fcm_send_notification($user_ID, 'orderarrived', $user_ID);
        }
    }

    wp_reset_postdata();
}

$empargs = array(
    'posts_per_page'   => -1,
    'post_status'      => 'publish',
    'post_type'        => 'emp',
);
$emps = get_posts($empargs);
foreach ($emps as $emp) {
    $end_time = get_post_meta($emp->ID, 'timestamp_emp', true);
    $user_emp = get_post_meta($emp->ID, 'defender_emp', true);

    $timeleft = $end_time-$timestamp;

    if ($timeleft <= 0) {
        wp_trash_post($emp->ID);
        count_all_stats($user_emp);
    }
}
