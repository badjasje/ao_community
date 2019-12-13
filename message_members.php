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

$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = (isset($clanData['ct_'.$i]) ? $clanData['ct_'.$i][0] : 0);
}
$allowed = array_merge($cts, array($clan_leader));

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