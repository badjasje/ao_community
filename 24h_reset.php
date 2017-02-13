<?php 
/* handles resetting of daily limits */
require_once("wp-load.php"); 
if(get_field('game_status','option') == 'Live'){
$users = get_users();
foreach ($users as $user) {
	$user_ID = $user->data->ID;
	update_user_meta($user_ID,'explored_today',0);	
	update_user_meta($user_ID,'land_sold_today',0);
	update_user_meta($user_ID,'aid_sent_today',0);
	update_user_meta($user_ID,'special_sold_today',0);
	}
$args = array(
		
		'post_type'		=>	'clan',
		'posts_per_page' => -1,
		);
	
	$clans = get_posts($args);
	foreach ($clans as $clan) {
	
		$_24Hpts = get_post_meta($clan->ID, '24h_pts', true);
		$_24Hlist = get_post_meta($clan->ID, '24h_pts_list', true);
		

		
		$_24Hlist[date("d-m-Y")] = $_24Hpts;
		update_post_meta($clan->ID, '24h_pts_list',$_24Hlist );
		
		
		update_post_meta($clan->ID, '24h_pts', 0);
		
	}
}