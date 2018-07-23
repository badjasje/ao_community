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
            
            
    $new_order_id = wp_insert_post($args);
    update_post_meta($new_order_id, 'receiver_id', $receiver);
    update_post_meta($new_order_id, 'sender_id', $userId);
    update_post_meta($new_order_id, 'general_status', 'New');
    update_user_meta($receiver, 'new_messages', get_user_meta($receiver, 'new_messages')[0]+1);
            
            $sub_message_args = array(
                'post_title'    => $title,
                'post_content'  => $message,
                'post_status'   => 'publish',
                'post_type'     => 'sub_user_message',
                'post_author'   => $userId
                );
            $new_sub_id = wp_insert_post($sub_message_args);
            update_post_meta($new_sub_id, 'parent_message_id', $new_order_id);
            update_post_meta($new_sub_id, 'sender_id', $userId);
            update_post_meta($new_sub_id, 'receiver_id', $receiver);
            update_post_meta($new_sub_id, 'receiver_status', 'New');
       
    $array['status'] = 'Message sent to '.get_user_name($receiver);
    $array['next'] = false;
    echo json_encode($array);
    exit;
}else{
	

    $sub_message_args = array(
    'post_title'    => 'Submessage for '.get_the_title($_POST['main_message']),
    'post_content'  => $_POST['message'],
    'post_status'   => 'publish',
    'post_type'     => 'sub_user_message',
    'post_author'   => $userId
    );
    $new_sub_id = wp_insert_post($sub_message_args);
    update_post_meta($new_sub_id, 'parent_message_id', $_POST['main_message']);
    update_post_meta($new_sub_id, 'sender_id', $userId);
            
            
    $sender_id = get_post_meta($_POST['main_message'], 'sender_id',true);
    $receiver_id = get_post_meta($_POST['main_message'], 'receiver_id',true);
    
    update_post_meta($new_sub_id, 'receiver_id', $receiver_id);
    if ($userId != $sender_id) {
        update_user_meta($sender_id, 'new_messages', get_user_meta($sender_id, 'new_messages',true)+1);
        update_post_meta($new_sub_id, 'receiver_id', $sender_id[0]);
    }
    if ($userId != $receiver_id) {
        update_user_meta($receiver_id, 'new_messages', get_user_meta($receiver_id, 'new_messages',true)+1);
        update_post_meta($new_sub_id, 'receiver_id', $receiver_id);
    }
    
            
    update_post_meta($new_sub_id, 'receiver_status', 'New');

    $array['status'] = 'Message sent';
    $array['next'] = true;
    echo json_encode($array);
    exit;
}
