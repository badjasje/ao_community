<?php
    
    
    
    require_once("wp-load.php");
    
$user_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3582));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}
$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
$clan_leader = get_post_meta($clan_ID, 'clan_leader', true);



$users = get_post_meta($clan_ID, 'clan_members');
$title = $_POST['title'];
$message = $_POST['message'];



foreach ($users[0] as $user) {
    $receiver = $user;


    $args = array(
                'post_title'    => $title,
                'post_content'  => $message,
                'post_status'   => 'publish',
                'post_type'     => 'user_message',
                'post_author'   => $user_ID
                );
            
            
            $new_order_id = wp_insert_post($args);
            update_post_meta($new_order_id, 'receiver_id', $receiver);
            update_post_meta($new_order_id, 'sender_id', $user_ID);
            update_post_meta($new_order_id, 'general_status', 'New');
            update_user_meta($receiver, 'new_messages', get_user_meta($receiver, 'new_messages')[0]+1);
            
            $sub_message_args = array(
                'post_title'    => $title,
                'post_content'  => $message,
                'post_status'   => 'publish',
                'post_type'     => 'sub_user_message',
                'post_author'   => $user_ID
                );
            $new_sub_id = wp_insert_post($sub_message_args);
            update_post_meta($new_sub_id, 'parent_message_id', $new_order_id);
            update_post_meta($new_sub_id, 'sender_id', $user_ID);
            update_post_meta($new_sub_id, 'receiver_id', $receiver);
            update_post_meta($new_sub_id, 'receiver_status', 'New');
}
        
                
    $_SESSION['status'] = 'Message sent to all members';
wp_redirect(get_permalink(3601));
