<?php
    

    require_once("wp-load.php");
    if (get_field('game_status', 'option') == 'Live') {
	global $wpdb;
	$timestamp = current_time('timestamp')-259200;
	
	// Money Production research = 0, Starting bonus != finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 15000
        WHERE t1.meta_key = 'money' AND t2.meta_value = 0 AND t3.meta_value != 'finance' AND t4.meta_value > $timestamp "
		);
		
	// Money Production research = 1, Starting bonus != finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 25000
        WHERE t1.meta_key = 'money' AND t2.meta_value = 1 AND t3.meta_value != 'finance' AND t4.meta_value > $timestamp "
		);
	// Money Production research = 2, Starting bonus != finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 35000
        WHERE t1.meta_key = 'money' AND t2.meta_value = 2 AND t3.meta_value != 'finance' AND t4.meta_value > $timestamp "
		);
		
		
	// With Finance Bonus
	// Money Production research = 0, Starting bonus = finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 16500
        WHERE t1.meta_key = 'money' AND t2.meta_value = 0 AND t3.meta_value = 'finance' AND t4.meta_value > $timestamp "
		);
		
	// Money Production research = 1, Starting bonus = finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 27500
        WHERE t1.meta_key = 'money' AND t2.meta_value = 1 AND t3.meta_value = 'finance' AND t4.meta_value > $timestamp "
		);
	// Money Production research = 2, Starting bonus = finance
	$wpdb->query(
    "UPDATE `${table_prefix}usermeta` as t1 
            INNER JOIN `${table_prefix}usermeta` as t2 ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production' 
            INNER JOIN `${table_prefix}usermeta` as t3 ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
            INNER JOIN `${table_prefix}usermeta` as t4 ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
        SET t1.meta_value = t1.meta_value + 38500
        WHERE t1.meta_key = 'money' AND t2.meta_value = 2 AND t3.meta_value = 'finance' AND t4.meta_value > $timestamp "
		);
	}