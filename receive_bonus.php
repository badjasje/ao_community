<?php

require_once("wp-load.php");
    
$user_ID = get_current_user_id();
$turnLock = get_user_meta($user_ID, 'turn_lock', true);

if ($turnLock == 1) {
    $_SESSION['status'] = 'Please try again in a few minutes.';
    wp_redirect(get_permalink(3582));
    exit;
}


if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3486));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3486));
    exit;
}
$event_ID = $_GET['id'];

$receiver_ID = get_post_meta($event_ID, 'defender_id', true);

if ($user_ID == $receiver_ID) {
    $used = get_post_meta($event_ID, 'bonus_used', true);
    if ($used == 'yes') {
        wp_redirect(get_permalink(7643));
        exit;
    }
    /* Add bonus money */
    $bonus_money = get_post_meta($event_ID, 'bonus_money', true);
    $money = get_user_meta($user_ID, 'money', true);
    $money_new = $money + $bonus_money;
    update_user_meta($user_ID, 'money', $money_new);
    
    /* Add bonus turns */
    $turns = get_user_meta($user_ID, 'turns', true);
    $bonus_turns = get_post_meta($event_ID, 'bonus_turns', true);
    
    $turns_new = $turns + $bonus_turns;

    update_user_meta($user_ID, 'turns', $turns_new);
    update_post_meta($event_ID, 'bonus_used', 'yes');
    
    $file = 'bonuslog.txt';
    // Open the file to get existing content
    $current = file_get_contents($file);
    // Append a new person to the file
    
    $turns_newest = get_user_meta($user_ID, 'turns', true);
    $time = current_time('G:i:s | d-m-Y');
    $current .= $time."\n";
    $current .= "User ID: ".$user_ID." Event ID: ".$event_ID."\n";
	$current .= "IP Address: ". get_user_ip_address()."\n";
    $current .= "New Money: ".$money_new." Old turns: ".$turns." | New Turns: ".$turns_newest."\n\n";
    // Write the contents back to the file
    file_put_contents($file, $current);
    
    
    
    
    
    $_SESSION['status'] = 'Bonus received';
    wp_redirect(get_permalink(3486));
    exit;
} else {
    wp_redirect(get_permalink(3486));
    exit;
}
