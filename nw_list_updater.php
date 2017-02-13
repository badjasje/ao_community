<?php
require_once("wp-load.php");
$users = get_users();
foreach ($users as $user) {
$user_ID = $user->data->ID;
count_all_stats($user_ID);
	}
	
$args = array(
		
		'post_type'		=>	'clan',
		'posts_per_page' => -1,
		);
	
	$clans = get_posts($args);
	foreach ($clans as $clan) {
	
		$clan_members = get_post_meta($clan->ID,'clan_members');
		
		$tot_networth = 0;
		foreach ($clan_members[0] as $member) {
					
			$networth = get_user_meta($member, 'networth',true);
			$tot_networth+=$networth;
			}
		update_post_meta($clan->ID, 'clan_networth', ceil($tot_networth));
		
	}