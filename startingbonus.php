<?php
/**
 * Handles starting bonus
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

/* initialize core variables */
$user_ID = get_current_user_id();

$userLock = get_user_meta($user_ID, 'user_lock', true);

if ($userLock == 1) {
    $array['status'] = 'Please reload the page and try again';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
update_user_meta($user_ID, 'user_lock', 1);

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$user_data = get_user_meta($user_ID);


$bonustype = $_POST['bonustype'];
$bonus = get_user_meta($user_ID, 'starting_bonus', true);
if (!empty($bonus) || $bonus != 0) {
    wp_redirect(get_permalink(3486));
    exit;
}
if ($bonustype == 'offensive') {
    $turns = $user_data['turns'][0];
    update_user_meta($user_ID, 'turns', $turns+75);
    update_user_meta($user_ID, 'starting_bonus', 'offensive');
}


if ($bonustype == 'defensive') {
    $land = $user_data['land'][0];
    update_user_meta($user_ID, 'land', $land+3500);
    update_user_meta($user_ID, 'starting_bonus', 'defensive');
}


if ($bonustype == 'finance') {
    $money = $user_data['money'][0];
    update_user_meta($user_ID, 'money', $money+400000);
    update_user_meta($user_ID, 'starting_bonus', 'finance');
}


if ($bonustype == 'shipping') {
    $land = $user_data['land'][0];
    update_user_meta($user_ID, 'land', $land+2500);
    
    $money = $user_data['money'][0];
    update_user_meta($user_ID, 'money', $money+250000);
    
    update_user_meta($user_ID, 'starting_bonus', 'shipping');
}

$userData = get_user_meta($user_ID);

update_user_meta($user_ID, 'user_lock', 0);
$array['status'] = 'Starting bonus picked';
$array['money'] = $userData['money'][0];
$array['turns'] = $userData['turns'][0];
$array['networth'] = $userData['networth'][0];
$array['next'] = true;
echo json_encode($array);
exit;
