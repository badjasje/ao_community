<?php
    require_once("wp-load.php");

global $userId;
global $userData;

$satellites = Satellites::get();

if (empty($userId)) {
    wp_redirect(get_permalink(3582));
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}



$sat_owned = get_user_meta($userId, 'sat_owned', true);
$demolishCost = $satellites[$sat_owned]['price'] * 0.2;

$totalmoney = get_user_meta($userId, 'money', true);


if ($demolishCost > $totalmoney) {
    $_SESSION['status'] = 'Insufficient funds';
    wp_redirect(get_permalink(8578));
    exit;
}

update_user_meta($userId, 'sat_owned', 0);
update_user_meta($userId, 'sat_endlife', 0);

    $args = array(
        'post_title'    => 'Sat crash: '.$userId,
        'post_status'   => 'publish',
        'post_type'     => 'event_local',
        'post_author'   => $userId
        );

    $new_event_id = wp_insert_post($args);
    update_field('attacktype', 'sat_crash', $new_event_id);



    update_field('attacker_id', 0, $new_event_id);
    update_field('defender_id', $userId, $new_event_id);
    update_field('time_attacked', $timestamp, $new_event_id);

    /* update event count */
    $event_count = get_user_meta($userId, 'new_events', true);
    update_user_meta($userId, 'new_events', $event_count + 1);
    update_user_meta($userId, 'money', $totalmoney - $demolishCost);

    $_SESSION['status'] = 'Satellite demolished';
    wp_redirect(get_permalink(8578));
    exit;
