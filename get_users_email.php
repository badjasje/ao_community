<?php
require_once("wp-load.php");
if(!current_user_can('administrator')) {
		wp_redirect(get_permalink(3582)); exit;
	}
$users = get_users();
foreach ($users as $user) {

$email = $user->data->user_email;
echo $email.'<br/>';
	}