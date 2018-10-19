<?php

/**
 * Handles turn income
 */
require_once("wp-load.php");
include('constants.php');

nocache_headers();
if (get_field('game_status', 'option') != 'Live') { exit; }

    // note: increments are by one. $INCOME_TURNS is assumed to be 1
    // 1) increase all turns_lost for users having > 300 turns at hand

    $wpdb->query("

        UPDATE `${table_prefix}usermeta` t1 
    INNER JOIN `${table_prefix}usermeta` t2
            ON t1.user_id    = t2.user_id
           SET t1.meta_value = t1.meta_value + 1
         WHERE t1.meta_key   = 'turns_lost'
           AND t2.meta_key   = 'turns'
           AND t2.meta_value >= 300
        

    ");

    // 2) increase turns for users having < 300 turns at hand

    $wpdb->query("
        
        UPDATE     `${table_prefix}usermeta` t1
        INNER JOIN `${table_prefix}usermeta` t2
            ON     t1.user_id    = t2.user_id
           SET     t1.meta_value=t1.meta_value+1
         WHERE     t1.meta_key   = 'turns'
           AND     t1.meta_value < 300
           AND     t2.meta_key = 'networth'
           AND     t2.meta_value > 3499
           
    ");