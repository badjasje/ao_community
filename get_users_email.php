<?php
	/*
require_once("wp-load.php");
if (!current_user_can('administrator')) {
        wp_redirect(get_permalink(3582));
    exit;
}
$timestamp = current_time('timestamp');
	$args = array(

		'meta_key'     	=> 'last_online',
		'orderby'      	=> 'meta_value_num',
		'meta_value'	=> $timestamp-1728000,
		'meta_compare'	=> '>',

	 ); 
    $users = get_users($args);
foreach ($users as $user) {
    $email = $user->data->user_email;
    echo $email.'<br/>';
}
*/