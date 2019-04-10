<?php
require_once("wp-load.php");

if (!defined('ABSPATH')) {
    $array['status'] = 'Error';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$data = maybe_unserialize( $_POST );
$array = array();
global $userId;
global $userData;
$clan_ID = $userData['clan_id_user'][0];

$clanData = get_post_meta($clan_ID);

$clanleader = $clanData['clan_leader'][0];
$ct_1 = $clanData['ct_1'][0];
$ct_2 = $clanData['ct_2'][0];
$ct_3 = $clanData['ct_3'][0];
$ct_4 = $clanData['ct_4'][0];

$clanMembers = maybe_unserialize( $clanData['clan_members'][0]);

$clanTrustees = array();
if(count($clanMembers) > 1){
	$clanTrustees = $data['clantrustees'];
}

$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanleader);

$array['imagechanged'] = false;

if(!empty($data['newclanimage'])){
	$wp_upload_dir = wp_upload_dir();
	$newclanimg = $wp_upload_dir['url'] . '/' . $data['newclanimage'];
	update_post_meta($clan_ID, 'clan_image', $newclanimg);
	$array['newclanimage'] = $newclanimg;
	$array['imagechanged'] = true;
}

if (in_array($userId, $allowed)) {
    $my_post = array('ID' => $clan_ID, 'post_content' => wp_strip_all_tags($data['publicmessage']));
    wp_update_post($my_post);
}

if ($userId == $clanleader) {
	update_post_meta($clan_ID, 'clan_leader', $data['new_leader']);
	if($data['new_leader'] == $ct_1){
		update_post_meta($clan_ID, 'ct_1', $userId);
	}
	if($data['new_leader'] == $ct_2){
		update_post_meta($clan_ID, 'ct_2', $userId);
	}
	if($data['new_leader'] == $ct_3){
		update_post_meta($clan_ID, 'ct_3', $userId);
	}
	if($data['new_leader'] == $ct_4){
		update_post_meta($clan_ID, 'ct_4', $userId);
	}
}

update_post_meta($clan_ID, 'ct_1', 0);
update_post_meta($clan_ID, 'ct_2', 0);
update_post_meta($clan_ID, 'ct_3', 0);
update_post_meta($clan_ID, 'ct_4', 0);

$clanTrustees = array_slice($clanTrustees, 0, 4);
$count = 1;
foreach ($clanTrustees as $trustee) {
	update_post_meta($clan_ID, 'ct_'.$count, $trustee);
	$count++;
}
$array['status'] = 'Clan updated';
echo json_encode($array);
exit;