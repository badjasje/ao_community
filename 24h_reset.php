<?php
/**
 * Handles resetting of daily limits
 */
require_once("wp-load.php");

if (get_field('game_status', 'option') != 'Live') { exit; }


// Add 25 XP for above 500k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+25
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 500000 AND 749999
");

// Add 75 XP for above 750k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+75
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 750000 AND 999999
");

// Add 100 XP for above 1000k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+100
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 1000000 AND 1499999
");
    

// Add 100 XP for above 1000k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+100
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 1000000 AND 1499999
");

// Add 150 XP for above 1500k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+150
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 1500000 AND 1999999
");
    
// Add 200 XP for above 2000k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+200
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 2000000 AND 2499999
");

// Add 250 XP for above 2500k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+200
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value between 2500000 AND 2999999
");

// Add 300 XP for above 3000k NW
$wpdb->query("
    UPDATE     `${table_prefix}usermeta` t1
    INNER JOIN `${table_prefix}usermeta` t2
        ON     t1.user_id    = t2.user_id
       SET     t1.meta_value=t1.meta_value+200
     WHERE     t1.meta_key   = 'player_xp'
       AND     t1.meta_value < 9999999999999999999999
       AND     t2.meta_key = 'networth'
       AND     t2.meta_value > 3000000
");



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