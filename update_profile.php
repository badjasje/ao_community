<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require_once("wp-load.php");

$message = 'Nothing changed';
nocache_headers();

$array['imagechanged'] = false;
$array['usernamechanged'] = false;
$userId = get_current_user_id();
$member_data = get_userdata($userId);
$displayName = $member_data->display_name;
$username = trim(preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST['username'])); // maybe?

if(!empty($_POST['newuserimage'])) {
	$wp_upload_dir = wp_upload_dir();
	$newuserimage = $wp_upload_dir['url'] . '/' . $_POST['newuserimage'];
	update_user_meta($userId, 'avatar_user', $newuserimage);
	$array['newuserimage'] = $newuserimage;
	$array['imagechanged'] = true;
	$message = 'Profile picture updated';
}

if (!empty($username) && $username != $displayName) {
    if (get_user_meta($userId, 'name_change_counter', true) != 1 || get_field('game_status', 'option') == 'Pause' || $username == 'Minion') {

		$args = array('search' => $username, 'search_fields' => array('display_name'), 'meta_query'=> array(array(
			array('key' => 'last_online', 'value' => $timestamp-1728000, 'compare' => ">", 'type' => 'numeric'),
		)));
		$user = new WP_User_Query($args);
	    if (count($user->results) && $username != 'Minion' && $user->results[0]->data->ID != $userId) {
			$message = 'Username already exists';
	    }
		else {
			wp_update_user(array( 'ID' => $userId, 'display_name' => $username ));
			update_user_meta($userId, 'name_change_counter', 1);
			$message = 'Username updated';
		}

    } else $message = 'Username already changed this round ';
}

update_user_meta($userId, 'phone_number', $_POST['phone']);
wp_update_user( array( 'ID' => $userId, 'user_email' => $_POST['email'] ) );

$array['status'] = $message;
echo json_encode($array);
exit;