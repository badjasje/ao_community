<?php


// Load WordPress environment
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/user.php');

// Build a query to find up to 500 users where 'last_online' is either empty or not set
$args = array(
	'number'    => 5000,
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key'     => 'last_online',
			'compare' => 'NOT EXISTS'
		),
	
	)
);

$user_query = new WP_User_Query($args);
$users = $user_query->get_results();

if (!empty($users)) {
	foreach ($users as $user) {
		// Delete the user; you can also specify a reassign user id as second parameter if needed.
		wp_delete_user($user->ID);
		echo "Deleted user: " . esc_html($user->user_login) . " (ID: " . intval($user->ID) . ")<br>";
	}
} else {
	echo "No users found with an empty or missing 'last_online' field.";
}
?>
