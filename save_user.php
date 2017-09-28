<?php
    require_once("wp-load.php");
    
    $user_ID = get_current_user_id();
    $toAdd = $_GET['id'];
    $return = $_GET['return'];
    $savedUsers = get_user_meta($user_ID, 'saved_users', true);
    
    $savedUsers = json_decode($savedUsers);
    
if (!in_array($toAdd, $savedUsers)) {
    $savedUsers[] = $toAdd;
    $savedUsers = json_encode($savedUsers);
    update_user_meta($user_ID, 'saved_users', $savedUsers);
}
    
    
    wp_redirect(get_permalink($return).'/?id='.$toAdd);
    exit;
