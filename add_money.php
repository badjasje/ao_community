<?php
/**
 * Handles hourly monetary income – Optimized version
 */
require_once("wp-load.php");
nocache_headers();
if ( get_field('game_status', 'option') != 'Live' ) { 
    exit;
}
include('constants.php');
global $wpdb;
$timestamp = current_time('timestamp') - 259200;

$update_query = "
UPDATE `{$table_prefix}usermeta` AS t1
INNER JOIN `{$table_prefix}usermeta` AS t2 
    ON t2.user_id = t1.user_id AND t2.meta_key = 'level_money_production'
INNER JOIN `{$table_prefix}usermeta` AS t3 
    ON t3.user_id = t1.user_id AND t3.meta_key = 'starting_bonus'
INNER JOIN `{$table_prefix}usermeta` AS t4 
    ON t4.user_id = t1.user_id AND t4.meta_key = 'last_online'
INNER JOIN `{$table_prefix}usermeta` AS t5 
    ON t5.user_id = t1.user_id AND t5.meta_key = 'networth'
SET t1.meta_value = t1.meta_value + (
    CASE 
        WHEN t3.meta_value = 'finance' AND t2.meta_value = 0 THEN 16500
        WHEN t3.meta_value = 'finance' AND t2.meta_value = 1 THEN 27500
        WHEN t3.meta_value = 'finance' AND t2.meta_value >= 2 THEN 38500
        WHEN t3.meta_value != 'finance' AND t2.meta_value = 0 THEN 15000
        WHEN t3.meta_value != 'finance' AND t2.meta_value = 1 THEN 25000
        WHEN t3.meta_value != 'finance' AND t2.meta_value >= 2 THEN 35000
        ELSE 0
    END
)
WHERE t1.meta_key = 'money'
  AND t4.meta_value > {$timestamp}
  AND t4.meta_value <> ''
  AND t5.meta_value > 3499
";

// Execute the update query
$result = $wpdb->query($update_query);

if ( false === $result ) {
    echo "Error executing update: " . $wpdb->last_error;
} else {
    echo "Monetary income updated for {$wpdb->rows_affected} rows.";
}
?>