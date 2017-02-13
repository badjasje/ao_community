<?php
	
		require( dirname(__FILE__) . '/wp-load.php' );
		
if(get_field('game_status','option') == 'Live'){
	
$timestamp = strtotime(date('Y-m-d H:i:s'));

$args = array(
	'posts_per_page'   => -1,
	'post_status'      => 'publish',
	'post_type'        => 'market_order',
);

$orders = get_posts( $args ); 



foreach ($orders as $order) {
	$user_ID = get_post_meta($order->ID,'user_placed_id',true);
	$delivery_time = get_post_meta($order->ID,'delivery_time',true);
	$timeleft = $delivery_time-$timestamp;
	if($timeleft <= 0){
	
		$unit_type = get_post_meta($order->ID,'unit_type',true);
		
		/* check if order is satellite */
		$sats = array('laser','comsat','stealths','spysat','spysat','amssat','empsat');

		if(!in_array($unit_type, $sats)){
		
		$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		$ownedunits = get_user_meta($user_ID, $unit_type.'_owned',true);
		$total_units_on_order = get_user_meta($user_ID, $unit_type.'_ordered',true);
	
		update_user_meta( $user_ID,$unit_type.'_ordered',$total_units_on_order - $units_in_this_order);
		update_user_meta( $user_ID,$unit_type.'_owned',$units_in_this_order+$ownedunits);
		
		}else{
		
		update_user_meta( $user_ID,'sat_owned',$unit_type);
		update_user_meta( $user_ID,'sat_in_progress',0);
		update_user_meta( $user_ID,'sat_endlife',$timestamp+(10*86400));
		}	
		
		/* trash order post */
		//wp_delete_post($order->ID);
		wp_trash_post($order->ID);
		count_all_stats($user_ID);
	}
	
	}
}