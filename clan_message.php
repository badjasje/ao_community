<?php
/**
 * Handles clan creation
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
global $userId;
global $userData;
$array = array();
if (! defined('ABSPATH')) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (empty($userId)) {
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
$clan_ID = $userData['clan_id_user'][0];
$clanleader = get_post_meta($clan_ID, 'clan_leader')[0];

 $ct_1 = get_post_meta($clan_ID, 'ct_1', true);
 $ct_2 = get_post_meta($clan_ID, 'ct_2', true);
 $ct_3 = get_post_meta($clan_ID, 'ct_3', true);
 $ct_4 = get_post_meta($clan_ID, 'ct_4', true);


$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);


if (in_array($userId, $allowed)) {
    update_post_meta($clan_ID, 'clan_message', $_POST['new_message']);
    $array['status'] = 'Clan message updated';
    $array['clanmessage'] = $_POST['new_message'];
    $array['next'] = true;
    echo json_encode($array);
    exit;
} else {
    $array['status'] = 'Not allowed';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
