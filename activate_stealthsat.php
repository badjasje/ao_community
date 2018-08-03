<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */


require_once("wp-load.php");

$array = array();
global $userId;

if (! defined('ABSPATH')) {
    $array['status'] = 'You cannot do that';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (empty($userId)) {
	$array['status'] = 'Please log in';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (!is_user_logged_in()) {
	$array['status'] = 'Please log in';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$sat_owned = get_user_meta($userId, 'sat_owned', true);
$sat_morale = get_user_meta($userId, 'sat_morale', true);
$turns = get_user_meta($userId, 'turns', true);

if ($turns < 2) {
    $array['status'] = 'Not enough turns. You need 3 turns to activate your stealth satellite';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}


if ($sat_owned != 'stealths') {
    $array['status'] = 'You do not own a stealth satellite, crazy hacker you';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if ($sat_morale < 100) {
	$array['status'] = 'Not enough satellite power';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$timestamp = current_time('timestamp');

update_user_meta($userId, 'stealth_sat_status', 'active');
update_user_meta($userId, 'stealth_sat_time', $timestamp+3600*3.5);
update_user_meta($userId, 'sat_morale', $sat_morale-100);
update_user_meta($userId, 'turns', $turns-3);


$array['status'] = 'Stealth satellite activated';
$array['next'] = true;
echo json_encode($array);
exit;