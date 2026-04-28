<?php
require_once(dirname(__FILE__) . '/wp-load.php');

if (get_field('game_status', 'option') !== 'Live') {
    exit;
}

$timestamp = current_time('timestamp');
global $wpdb;
$orders_query = $wpdb->prepare(
    "SELECT * FROM {$wpdb->posts} 
    LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id 
    WHERE {$wpdb->posts}.post_type = 'market_order' 
    AND {$wpdb->posts}.post_status = 'publish' 
    AND {$wpdb->postmeta}.meta_key = 'delivery_time' 
    AND {$wpdb->postmeta}.meta_value < %d",
    $timestamp
);

$orders = $wpdb->get_results($orders_query);

foreach ($orders as $order) {
    $orderID = $order->ID;
    $orderData = get_post_meta($orderID);
    $user_ID = $orderData['user_placed_id'][0];
    $userData = get_user_meta($user_ID);
    $delivery_time = $orderData['delivery_time'][0];
    $moraleLock = get_user_meta($user_ID, 'morale_lock', true);
    $turnLock = get_user_meta($user_ID, 'turn_lock', true);
    $timeleft = $delivery_time - $timestamp;

    if ($timeleft <= 0 && $moraleLock == 0 && $turnLock == 0) {
        $unit_type = $orderData['unit_type'][0];
        $sats = ['laser', 'comsat', 'stealths', 'spysat', 'spysat', 'amssat', 'empsat'];

        if (!in_array($unit_type, $sats)) {
            $units_in_this_order = $orderData['amount_ordered'][0];
            $ownedunits = $userData[$unit_type.'_owned'][0];
            $total_units_on_order = $userData[$unit_type.'_ordered'][0];
            
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = %d WHERE user_id = %d AND meta_key = %s",
                $total_units_on_order - $units_in_this_order,
                $user_ID,
                $unit_type.'_ordered'
            ));
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = %d WHERE user_id = %d AND meta_key = %s",
                $units_in_this_order + $ownedunits,
                $user_ID,
                $unit_type.'_owned'
            ));
        } elseif (get_field('order_type', $orderID) == 'satellite') {
            $sat_level = $userData['level_satellite_construction'][0];
            $days = $sat_level > 1 ? 16 : 11;
            update_user_meta($user_ID, 'sat_owned', $unit_type);
            update_user_meta($user_ID, 'sat_in_progress', 0);
            update_user_meta($user_ID, 'sat_endlife', $timestamp + ($days * 86400));
            
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = %s WHERE user_id = %d AND meta_key = 'sat_owned'",
                $unit_type,
                $user_ID
            ));
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = %d WHERE user_id = %d AND meta_key = 'sat_in_progress'",
                0,
                $user_ID
            ));
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->usermeta} SET meta_value = %d WHERE user_id = %d AND meta_key = 'sat_endlife'",
                $timestamp + ($days * 86400),
                $user_ID
            ));
        }

        wp_trash_post($orderID);
        count_all_stats($user_ID);
        fcm_send_notification($user_ID, 'orderarrived', $user_ID);
        //marketOrderFallback($user_ID);
    }
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

    $timeleft = $end_time - $timestamp;

    if ($timeleft <= 0) {
        wp_trash_post($emp->ID);
        count_all_stats($user_emp);
    }
}