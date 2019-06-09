<?php
require_once("wp-load.php");
$gameType = get_field('game_type','option');
if(!in_array($gameType, array('Development','Test'))) {
	exit;
}
if (empty($userId) || !is_user_logged_in()) {
	$array['status'] = 'You must log in to perform this action';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

global $userId;
global $userData;
$extraMoney = 250000;
$extraTurns = 50;
$timestamp = current_time('timestamp');

update_user_meta( $userId, 'money', $userData['money'][0]+$extraMoney);
update_user_meta( $userId, 'turns', $userData['turns'][0]+$extraTurns);
update_user_meta( $userId, 'morale', 100);
update_user_meta( $userId, 'morale_pool', 100);
update_user_meta( $userId, 'sat_morale', 100);
$status = get_user_meta($userId, 'status', true);
if($status == 'dead' || $status == 'nukeprotection') update_user_meta( $userId, 'status', 'online');

// Research done right away
if(!empty($userData['research_in_progress'][0])) {
	$args = array('posts_per_page' => 1, 'author' => $userId, 'post_type' => 'research');
	$researches = get_posts($args);
	foreach ($researches as $research) {
		$research_in_progress = $research->post_content;
		update_user_meta($userId, 'research_in_progress', 0);
		$current_level = get_user_meta($userId, 'level_'.$research_in_progress);
		update_user_meta($userId, 'level_'.$research_in_progress, $current_level[0]+1);
		wp_trash_post($research->ID);
	}

	$queued_research = get_user_meta($userId, 'queued_research', true);
	if (!empty($queued_research) || $queued_research != 0) {
		include 'research_array.php';
		$time = $researches[$queued_research]['duration'];
		$args = array(
			'post_title' => $timestamp+($time*60*60), 'post_status' => 'publish', 'post_content' => $queued_research,
			'post_type' => 'research', 'post_author' => $userId
		);
		$new_research_id = wp_insert_post($args);
		update_user_meta($userId, 'research_in_progress', $queued_research);
		update_user_meta($userId, 'queued_research', 0);
	}
}

// Market orders
$args = array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => 'market_order', 'author' => $userId);
$orders = get_posts($args);
foreach ($orders as $order) {
	$orderData = get_post_meta($order->ID);
	$unit_type = $orderData['unit_type'][0];
	$sats = array('laser','comsat','stealths','spysat','spysat','amssat','empsat');
	if (!in_array($unit_type, $sats)) {
		$units_in_this_order = $orderData['amount_ordered'][0];
		$ownedunits = $userData[$unit_type.'_owned'][0];
		$total_units_on_order = $userData[$unit_type.'_ordered'][0];
		update_field($unit_type.'_ordered', $total_units_on_order - $units_in_this_order, 'user_'.$userId);
		update_field($unit_type.'_owned', $units_in_this_order+$ownedunits, 'user_'.$user_ID);
		wp_trash_post($order->ID);
	}
	if (get_field('order_type', $order->ID) == 'satellite') {
		$sat_level = $userData['level_satellite_construction'][0];
		$days = 11;
		if ($sat_level > 1) {
			$days = 16;
		}
		update_user_meta($userId, 'sat_owned', $unit_type);
		update_user_meta($userId, 'sat_in_progress', 0);
		update_user_meta($userId, 'sat_endlife', $timestamp+($days*86400));
		wp_trash_post($order->ID);
	}
	count_all_stats($userId);
}

wp_reset_postdata();

$array['status'] = 'All set: $250 000, full morale, orders, research and 50 turns received';
$array['money'] = $userData['money'][0]+$extraMoney;
$array['turns'] = $userData['turns'][0]+$extraTurns;
$array['morale'] = 100;
$array['next'] = true;
echo json_encode($array);
exit;