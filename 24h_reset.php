<?php
/**
 * Handles resetting of daily limits
 */
require_once("wp-load.php");

if (get_field('game_status', 'option') != 'Live') { exit; }

    $wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 0
			WHERE meta_key IN('explored_today', 'land_sold_today','aid_sent_today','special_sold_today','treasures_today')
            ");
	$wpdb->query("
			UPDATE ${table_prefix}usermeta
			SET meta_value = 'no'
			WHERE meta_key IN('low_power_notified','low_buildings_notified','high_power_notified','max_turns_notified')
            ");


    $args = [
        'post_type' => 'clan',
        'posts_per_page' => -1
    ];

    $clans = get_posts($args);
    foreach ($clans as $clan) {
        $_24Hpts = get_post_meta($clan->ID, '24h_pts', true);
        $_24Hlist = maybe_unserialize(get_post_meta($clan->ID, '24h_pts_list', true));
        $_24HNWlist = maybe_unserialize(get_post_meta($clan->ID, '24h_nw_list', true));


        if(!is_array($_24Hlist)){
		 	$_24Hlist = array();
		}

		if(!is_array($_24HNWlist)){
		 	$_24HNWlist = array();
		 }


        $clanNW = get_post_meta($clan->ID, 'clan_networth', true);

        $_24Hlist[date("d-m-Y")] = $_24Hpts;
        $_24HNWlist[date("d-m-Y")] = $clanNW;

        update_post_meta($clan->ID, '24h_pts_list', maybe_serialize($_24Hlist));
        update_post_meta($clan->ID, '24h_nw_list', maybe_serialize($_24HNWlist));

        update_post_meta($clan->ID, '24h_pts', 0);
    }