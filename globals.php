<?php
	require_once("wp-load.php");

$user_ID = get_current_user_id();
$clan = get_user_meta($user_ID, 'clan_id_user', true);

$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	echo '<pre>';
	print_r($member);
	echo '</pre>';
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}