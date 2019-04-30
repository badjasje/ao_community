<?php
require_once("wp-load.php");
$toplistArray = array();

$args = array(
	'orderby'    		=> 'meta_value_num',
	'post_type'			=>	'clan',
	'posts_per_page' 	=> 50,
	'meta_key' 			=> 'clan_networth',
	'order'     	 	=> 'DESC'
);
$clans = get_posts($args);
foreach ($clans as $clan) {
	$toplistArray['clannetworth'][] = $clan->ID;
	$clan_members = get_post_meta($clan->ID,'clan_members');

	$tot_networth = 0;
	$tot_land = 0;
	foreach ($clan_members[0] as $member) {
		$tot_networth += intval(get_user_meta($member, 'networth',true));
		$tot_land     += intval(get_user_meta($member, 'land', true));
	}
	update_post_meta($clan->ID,'clan_networth',ceil($tot_networth));
	update_post_meta($clan->ID,'clan_land',    ceil($tot_land));
}

$args = array(
	'orderby'    		=> 'meta_value_num',
	'post_type'			=>	'clan',
	'posts_per_page' 	=> 50,
	'meta_key' 			=> 'clan_points',
	'order'     	 	=> 'DESC'
);
$clans = get_posts($args);
foreach ($clans as $clan) {
	$toplistArray['clanpoints'][] = $clan->ID;
}

$args = array(
	'orderby'    		=> 'meta_value_num',
	'post_type'			=>	'clan',
	'posts_per_page' 	=> 50,
	'meta_key' 			=> '24h_pts',
	'order'     	 	=> 'DESC'
);
$clans = get_posts($args);
foreach ($clans as $clan) {
	$toplistArray['24h_pts'][] = $clan->ID;
}

$args = array(
	'meta_key' => 'networth',
	'orderby'  => 'meta_value_num',
	'order'    => 'DESC',
	'number'   => 50,
);
$users = get_users($args);
foreach ($users as $user) {
	$status = get_user_meta($user->ID, 'status', true);
	if($status != 'dead'){
		$toplistArray['provnw'][] = $user->ID;
	}
}

$toplistArray = maybe_serialize($toplistArray);
update_field('toplistarray', $toplistArray,'options');