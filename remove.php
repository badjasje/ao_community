<?php
    require_once("wp-load.php");
    
    $user_ID = get_current_user_id();
    $toRemove = $_GET['id'];
    $return = $_GET['return'];
    $savedUsers = get_user_meta($user_ID, 'saved_users', true);
    
    $savedUsers = json_decode($savedUsers, true);
    
    echo '<pre>';
    print_r($savedUsers);
    echo '</pre>';
    
if (($key = array_search($toRemove, $savedUsers)) !== false) {
    unset($savedUsers[$key]);
}
    
    $savedUsers = json_encode($savedUsers);
    update_user_meta($user_ID, 'saved_users', $savedUsers);
    
    $_SESSION['status'] = 'User removed';
    wp_redirect(get_permalink($return));
    exit;
