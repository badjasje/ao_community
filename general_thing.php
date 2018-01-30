<?php
    
    $time_start = microtime(true);
    require_once("wp-load.php");
	global $wpdb;
	
		// SELECT * FROM `23zx_usermeta`
		// WHERE `meta_key` = 'land' 
		// ORDER BY `23zx_usermeta`.`meta_value` + 0 DESC
	$users = $wpdb->get_results("
	
		 
		 SELECT 23zx_users.ID
		 FROM 23zx_users INNER JOIN 23zx_usermeta 
		 ON 23zx_users.ID = 23zx_usermeta.user_id 
		 WHERE 23zx_usermeta.meta_key = 'land' 
		 ORDER BY `23zx_usermeta`.`meta_value` + 0 DESC
	
	");

	$position = 0;
    foreach ($users as $key => $user) {
        $position += 1;
        $user_ID = $user->ID;
        
        $next_ID = $users[$key-1]->ID;
        $prev_ID = $users[$key+1]->ID;
        $userData = get_user_meta($user_ID);
        $land = get_user_meta($user_ID, 'land', true);
        $next_land = get_user_meta($next_ID, 'land', true);
        $prev_land = get_user_meta($prev_ID, 'land', true);
        
        update_user_meta($user_ID, 'moe_position', $position);
        update_user_meta($user_ID, 'moe_prev', round($land-$prev_land));
        
        if ($position == 1) {
            update_user_meta($user_ID, 'moe_next', 0);
        } else {
            update_user_meta($user_ID, 'moe_next', round($next_land-$land));
        }
    }
	$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';