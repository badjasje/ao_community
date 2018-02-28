<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

if ('GET' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

$userID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}

if (empty($userID) || !is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}

$user = $_GET['id'];
$clan = $_GET['clan'];

$ct1 = get_post_meta($clan, 'ct_1', true);
$ct2 = get_post_meta($clan, 'ct_2', true);
$ct3 = get_post_meta($clan, 'ct_3', true);
$ct4 = get_post_meta($clan, 'ct_4', true);


$clanLeader = get_post_meta($clan, 'clan_leader', true);
$allowedToKick = array($ct1,$ct2,$ct3,$ct4,$clanLeader);

if (!in_array($userID, $allowedToKick)) {
    wp_redirect(get_permalink($clan));
}

if (in_array($userID, $allowedToKick)) {
    $clanMembers = get_post_meta($clan, 'clan_members');
    $clanMembers = array_shift($clanMembers);

    foreach ($clanMembers as $key => $member) {
        if ($member == $user) {
            unset($clanMembers[$key]);
        }
    }

    if ($user == $ct1) {
        update_post_meta($clan, 'ct_1', 0);
    }
    if ($user == $ct2) {
        update_post_meta($clan, 'ct_2', 0);
    }
    if ($user == $ct3) {
        update_post_meta($clan, 'ct_3', 0);
    }
    if ($user == $ct4) {
        update_post_meta($clan, 'ct_4', 0);
    }

    update_post_meta($clan, 'clan_members', $clanMembers);
    update_user_meta($user, 'clan_id_user', 0);
    $timestamp = current_time('timestamp');
    update_user_meta($user, 'new_clan_timestamp', $timestamp+86400);

    $previousMembers = maybe_unserialize(get_post_meta($clan, 'previous_members',true));
    
    if(!is_array($previousMembers)){
		 	$previousMembers = array();
		}
   
    $previousMembers[] = $user;

    update_post_meta($clan, 'previous_members', maybe_serialize($previousMembers));

    /* user kicked event */
    $timestamp = current_time('timestamp');

    $args = [
        'post_title'    => 'Clan member kicked: '.$user,
        'post_status'   => 'publish',
        'post_type'     => 'event_local',
        'post_author'   => $userID
    ];

    $newEventId = wp_insert_post($args);
    update_field('attacktype', 'user_change', $newEventId);
    update_field('outcome', 'kicked', $newEventId);


    update_field('attacker_id', $userID, $newEventId);
    update_field('defender_id', $user, $newEventId);
    update_field('leaving_user', $user, $newEventId);
    update_field('attacker_clan_id', $clan, $newEventId);
    update_field('time_attacked', $timestamp, $newEventId);

    /* update event count */
    $eventCount = get_user_meta($user, 'new_events', true);
    update_user_meta($user, 'new_events', $eventCount + 1);

    $clanMembers = get_post_meta($clan, 'clan_members');

    if (!empty($clan) || $clan != 0) {
        foreach ($clanMembers[0] as $member) {
            $globals = get_user_meta($member, 'new_global_events', true);
            update_user_meta($member, 'new_global_events', $globals+1);
        }
    }

    $cpLost = round(get_user_meta($user, 'current_clan_points', true)*0.25);
    $clanPoints = get_post_meta($clan, 'clan_points', true);
    $newClanPoints = $clanPoints-$cpLost;

    if ($newClanPoints < 0) {
        $newClanPoints = 0;
    }

    update_user_meta($user, 'current_clan_points', 0);
    update_post_meta($clan, 'clan_points', $newClanPoints);
    update_field('clan_points', $cpLost, $newEventId);

    // Resetting some stats
    update_user_meta($user, 'attacks_rec_current', 0);
    update_user_meta($user, 'attacks_made_current', 0);

    update_user_meta($user, 'total_aid_sent', 0);
    update_user_meta($user, 'number_of_aids', 0);
    update_user_meta($user, 'aid_received', 0);

    $_SESSION['status'] = 'Clan member kicked. '.$cpLost.' clan points lost';
    wp_redirect(get_permalink($clan));
}
