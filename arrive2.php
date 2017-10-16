<?php
    
require(dirname(__FILE__) . '/wp-load.php');

if (get_field('game_status', 'option') != 'Live') {
    exit();
}

$timestamp = current_time('timestamp');

$args = [
    'posts_per_page'   => -1,
    'post_status'      => 'publish',
    'post_type'        => 'market_order',
];

$orders = get_posts($args);
$ordersQuery = new WP_Query($args);

// Looping through orders
if ($ordersQuery->have_posts()) {
    while ($ordersQuery->have_posts()) {
        $ordersQuery->the_post();
        $orderID = get_the_id();

        $userId = get_field('user_placed_id', $orderID);
        $deliveryTime = get_field('delivery_time', $orderID);

        $timeLeft = $deliveryTime - $timestamp;
        if ($timeLeft <= 0) {
            $unitType = get_field('unit_type', $orderID);

            /* Check if order is satellite */
            $sattelites = [
                'laser',
                'comsat',
                'stealths',
                'spysat',
                'spysat',
                'amssat',
                'empsat'
            ];

            if (!in_array($unitType, $sattelites)) {
                $unitsInOrder = get_field('amount_ordered', $orderID);

                $ownedUnits = get_user_meta($userId, $unitType.'_owned', true);
                $totalUnitsOnOrder = get_user_meta($userId, $unitType.'_ordered', true);

                update_user_meta($userId, $unitType.'_ordered', $totalUnitsOnOrder - $unitsInOrder);
                update_user_meta($userId, $unitType.'_owned', $unitsInOrder + $ownedUnits);
                wp_trash_post($unit->ID);
            }

            if (get_field('order_type', $orderID) == 'satellite') {
                update_user_meta($userId, 'sat_owned', $unitType);
                update_user_meta($userId, 'sat_in_progress', 0);
                update_user_meta($userId, 'sat_endlife', $timestamp+(10*86400));
                wp_trash_post($unit->ID);
            }

            /* trash order post */
            //wp_delete_post($order->ID);
            count_all_stats($userId);
        }
    }

    /* Restore original Post Data */
    wp_reset_postdata();
}

$empArgs = [
    'posts_per_page'   => -1,
    'post_status'      => 'publish',
    'post_type'        => 'emp',
];

$emps = get_posts($empArgs);

foreach ($emps as $emp) {
    $endTime = get_post_meta($emp->ID, 'timestamp_emp', true);
    $userEmp = get_post_meta($emp->ID, 'defender_emp', true);

    $timeLeft = $endTime - $timestamp;

    if ($timeLeft <= 0) {
        wp_trash_post($emp->ID);
        count_all_stats($userEmp);
    }
}
