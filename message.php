<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */



require(dirname(__FILE__) . '/wp-load.php');

$userId = get_current_user_id();
$receiver = $_POST['receiver'];

if (! defined('ABSPATH')) {
    exit;
}
if (empty($userId)) {
	$array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$timestamp = current_time('timestamp');

if($_POST['main_message'] == 'first'){
	$title = $_POST['title'];
	$message = $_POST['message'];
	
	
    $slug = md5(uniqid(rand(), true));
    $args = array(
        'post_title'    => $title,
        'post_content'  => $message,
        'post_status'   => 'publish',
        'post_name'     => $slug,
        'post_type'     => 'user_message',
        'post_author'   => $userId
        );
            
            
    $newMessageId = wp_insert_post($args);
    update_post_meta($newMessageId, 'receiver_id', $receiver);
    update_post_meta($newMessageId, 'sender_id', $userId);
    update_post_meta($newMessageId, 'general_status', 'New');
    update_user_meta($receiver, 'new_messages', get_user_meta($receiver, 'new_messages',true)+1);
            
    $row = array(
		'field_5b5ef267154f1'	=> $userId,
		'field_5b5ef27b154f2'	=> $_POST['message'],
		'field_5b5f0429b56ca'	=> $receiver
	);

	$i = add_row('field_5b5ef246154f0', $row, $newMessageId);
	update_post_meta($newMessageId, 'last_update_stamp', $timestamp);

       
    $array['status'] = 'Message sent to '.get_user_name($receiver);
    $array['next'] = false;
    echo json_encode($array);
    exit;
}else{
	
	$row = array(
		'field_5b5ef267154f1'	=> $userId,
		'field_5b5ef27b154f2'	=> $_POST['message'],
		'field_5b5f0429b56ca'	=> $_POST['receiver']
	);

	$i = add_row('field_5b5ef246154f0', $row, $_POST['main_message']);
	
	update_post_meta($_POST['main_message'], 'last_update_stamp', $timestamp);
	update_post_meta($_POST['main_message'], 'general_status', 'New');
	
	$messages = get_user_meta( $_POST['receiver'], 'new_messages', true );
	update_user_meta( $_POST['receiver'], 'new_messages', $messages+1);
	
	$array['status'] = 'Message sent';
    $array['next'] = true;
    $array['newmsg'] = "
    
<div class='row fw-row userRow row-no-padding' style='background-color: rgba(45, 67, 81, 0.74);'>
	<div class='col-md-1 col-no-padding sea_heading allUsersAvatarCol'>".small_avatar($userId,'allUsersAvatar')."<span class='mobileUserName'>".get_user_name($userId)."</span></div>
	<div class='col-md-11 celBlock allUsersNameCol'>".get_user_name($userId)."</div>
</div>

<div class='row fw-row row-no-padding' style='background-color: rgba(45, 67, 81, 0.325);'>
	
<div class='col-md-12 celBlock'>".str_replace("\r", "<br />", $_POST['message'])."</div>
</div>
<div class='pageSpacer'></div>	


"; 
    
    
    
    echo json_encode($array);
    exit;
}