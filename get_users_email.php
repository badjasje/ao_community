<?php
require_once("wp-load.php");
$users = get_users();
foreach ($users as $user) {

$email = $user->data->user_email;
echo $email.'<br/>';
	}