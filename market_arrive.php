<?php
require(dirname(__FILE__) . '/wp-load.php');
        
if (get_field('game_status', 'option') == 'Live') {
    $timestamp = current_time('timestamp');
    $args = array(
    'posts_per_page'   	=> -1,
    'post_status'      	=> 'publish',
    'meta_key'     		=> 'delivery_time',
    'meta_value'		=> $timestamp,
	'meta_compare'		=> '<',
    'post_type'        	=> 'market_order',
    );

    $orders = get_posts($args);


// The Query
    $the_query = new WP_Query($args);

// The Loop
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $orderID = get_the_id();
            
            $orderData = get_post_meta($orderID);
            $user_ID = $orderData['user_placed_id'][0];
            $userData = get_user_meta($user_ID);
            
            $delivery_time = $orderData['delivery_time'][0];
            
            $moraleLock = get_user_meta($user_ID, 'morale_lock', true);
            $turnLock = get_user_meta($user_ID, 'turn_lock', true);

            $timeleft = $delivery_time-$timestamp;
            if ($timeleft <= 0 && $moraleLock == 0 && $turnLock == 0) {
                $unit_type = $orderData['unit_type'][0];
        
                /* check if order is satellite */
                $sats = array('laser','comsat','stealths','spysat','spysat','amssat','empsat');
    
                if (!in_array($unit_type, $sats)) {
                   
                    $units_in_this_order = $orderData['amount_ordered'][0];
                    $ownedunits = $userData[$unit_type.'_owned'][0];
                    $total_units_on_order = $userData[$unit_type.'_ordered'][0];
    
                    update_field($unit_type.'_ordered', $total_units_on_order - $units_in_this_order, 'user_'.$user_ID);
                    update_field($unit_type.'_owned', $units_in_this_order+$ownedunits, 'user_'.$user_ID);
        
                    wp_trash_post($orderID);
                }
            
                if (get_field('order_type', $orderID) == 'satellite') {
                    $sat_level = $userData['level_satellite_construction'][0];
                    $days = 10;
                    if ($sat_level >= 1) {
                        $days = 15;
                    }
                    update_user_meta($user_ID, 'sat_owned', $unit_type);
                    update_user_meta($user_ID, 'sat_in_progress', 0);
                    update_user_meta($user_ID, 'sat_endlife', $timestamp+($days*86400));
                    wp_trash_post($orderID);
                }
        
                /* trash order post */
                //wp_delete_post($order->ID);
        
                count_all_stats($user_ID);
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();
    } else {
        // no posts found
    }

/*

foreach ($orders as $order) {
	$user_ID = get_post_meta($order->ID,'user_placed_id',true);
	$delivery_time = get_post_meta($order->ID,'delivery_time',true);
	$timeleft = $delivery_time-$timestamp;
	if($timeleft <= 0){
	
		$unit_type = get_post_meta($order->ID,'unit_type',true);
		
		// check if order is satellite 
		$sats = array('laser','comsat','stealths','spysat','spysat','amssat','empsat');
	
		if(!in_array($unit_type, $sats)){
		
		$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		$ownedunits = get_user_meta($user_ID, $unit_type.'_owned',true);
		$total_units_on_order = get_user_meta($user_ID, $unit_type.'_ordered',true);
	
		update_user_meta( $user_ID,$unit_type.'_ordered',$total_units_on_order - $units_in_this_order);
		update_user_meta( $user_ID,$unit_type.'_owned',$units_in_this_order+$ownedunits);
		wp_trash_post($order->ID);
		}
			
		if(get_post_meta($order->ID,'order_type',true) == 'satellite'){ 
		
		update_user_meta( $user_ID,'sat_owned',$unit_type);
		update_user_meta( $user_ID,'sat_in_progress',0);
		update_user_meta( $user_ID,'sat_endlife',$timestamp+(10*86400));
		wp_trash_post($order->ID);
		}	
		
		// trash order post 
		//wp_delete_post($order->ID);
		
		count_all_stats($user_ID);
	}
	
	}
*/
    $empargs = array(
    'posts_per_page'   => -1,
    'post_status'      => 'publish',
    'post_type'        => 'emp',
    );

    $emps = get_posts($empargs);

    foreach ($emps as $emp) {
        $end_time = get_post_meta($emp->ID, 'timestamp_emp', true);
        $user_emp = get_post_meta($emp->ID, 'defender_emp', true);

        $timeleft = $end_time-$timestamp;

        if ($timeleft <= 0) {
            wp_trash_post($emp->ID);
            count_all_stats($user_emp);
        }
    }
} /* end game live
