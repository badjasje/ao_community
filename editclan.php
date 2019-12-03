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
$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = (isset($clanData['ct_'.$i]) ? $clanData['ct_'.$i][0] : false);
}
$allowed = array_merge($cts, array($clanleader));

$clanMembers = maybe_unserialize( $clanData['clan_members'][0]);

$clanTrustees = array();
if(count($clanMembers) > 1){
	$clanTrustees = isset($data['clantrustees']) ? $data['clantrustees'] : array();
}

$array['imagechanged'] = false;

$wp_upload_dir = wp_upload_dir();
if(!empty($data['newclanimage'])){
	$newclanimg = $wp_upload_dir['url'] . '/' . $data['newclanimage'];
	update_post_meta($clan_ID, 'clan_image', $newclanimg);
	$array['newclanimage'] = $newclanimg;
	$array['imagechanged'] = true;
}
if(!empty($data['newclanavatar'])){
	$newclanavatar = $wp_upload_dir['url'] . '/' . $data['newclanavatar'];
	update_post_meta($clan_ID, 'clan_thumb', $newclanavatar);
	$array['newclanavatar'] = $newclanavatar;
	$array['imagechanged'] = true;
}

if (in_array($userId, $allowed)) {
	$content = wp_strip_all_tags($data['publicmessage']);
	if(preg_match('/^.{1,260}\b/s', $content, $match)) $content = $match[0]; // word break after 260 characters
    $my_post = array('ID' => $clan_ID, 'post_content' => $content);
    wp_update_post($my_post);
}

if ($userId == $clanleader) {
	update_post_meta($clan_ID, 'clan_leader', $data['new_leader']);
	for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
		if($data['new_leader'] == $cts[$i]){
			update_post_meta($clan_ID, 'ct_'.$i, $userId);
		}
	}
}

for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
	update_post_meta($clan_ID, 'ct_'.$i, 0);
}

$clanTrustees = array_slice($clanTrustees, 0, Settings::get('clan_trustee_num'));
$count = 1;
foreach ($clanTrustees as $trustee) {
	update_post_meta($clan_ID, 'ct_'.$count, $trustee);
	$count++;
}
$array['status'] = 'Clan updated';
echo json_encode($array);
exit;