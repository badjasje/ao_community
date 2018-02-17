<?php
/**
 * Handles resetting of daily limits
 */
require_once("wp-load.php");

if (get_field('game_status', 'option') == 'Live') {

    $resetArray = array();
    $resetArray[] = "'explored_today'";
    $resetArray[] = "'land_sold_today'";
    $resetArray[] = "'aid_sent_today'";
    $resetArray[] = "'special_sold_today'";
    
    
    $wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 0
			WHERE meta_key IN($resetArray)
            ");
	$wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 'no'
			WHERE meta_key IN('low_power_notified','low_buildings_notified')
            ");
    
    
    $args = [
        'post_type' => 'clan',
        'posts_per_page' => -1
    ];
    
    $clans = get_posts($args);
    foreach ($clans as $clan) {
        $_24Hpts = get_post_meta($clan->ID, '24h_pts', true);
        $_24Hlist = get_post_meta($clan->ID, '24h_pts_list', true);
        $_24HNWlist = get_post_meta($clan->ID, '24h_nw_list', true);

        $clanNW = get_post_meta($clan->ID, 'clan_networth', true);

        $_24Hlist[date("d-m-Y")] = $_24Hpts;
        $_24HNWlist[date("d-m-Y")] = $clanNW;
        
        update_post_meta($clan->ID, '24h_pts_list', $_24Hlist);
        update_post_meta($clan->ID, '24h_nw_list', $_24HNWlist);
        
        update_post_meta($clan->ID, '24h_pts', 0);
    }
}
