<?php
	require_once("wp-load.php");
	
	global $wpdb;
// Set max post date and post_type name
$date = date("Y-m-d H:i:s", strtotime('-60 days'));
$post_type = 'event_local';

// Build the query 
// Only select VFB Entries from LFG or LFM Form
$query = "
delete FROM `23zx_posts`
WHERE `post_type` = 'event_local'
AND DATEDIFF(NOW(), `post_date`) > 30
";
$wpdb->get_results($query);
          