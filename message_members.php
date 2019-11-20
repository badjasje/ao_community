<?php
require_once("wp-load.php");

global $userId;
global $userData;

if (!defined('ABSPATH')) exit;

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

$timestamp = current_time('timestamp');

$clan_id_user = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_id_user);
$clan_leader = $clanData['clan_leader'][0];

$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];

$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clan_leader);

if(!in_array($userId, $allowed)):
 	$array['status'] = 'You are not allowed to do that';
    $array['next'] = false;
    echo json_encode($array);
    exit;
endif;

$array = array();

$users = maybe_unserialize($clanData['clan_members'][0]);
$title = $_POST['title'];
$message = $_POST['message'];

foreach ($users as $user) {
	if($user == $userId) continue;
    $conv = Conversation::create($userId, $user, $title, $message);
    $conv->addMessage($userId, $user, $message);
}

$array['status'] = 'Message sent to all clan members';
$array['next'] = true;
echo json_encode($array);
exit;