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

$ct_1 = get_post_meta($clan_ID, 'ct_1', true);
$ct_2 = get_post_meta($clan_ID, 'ct_2', true);
$ct_3 = get_post_meta($clan_ID, 'ct_3', true);
$ct_4 = get_post_meta($clan_ID, 'ct_4', true);


$allowed = array($ct_1,$ct_2,$ct_3,$ct_4,$clanLeader);


if (!in_array($user_ID, $allowed)) {
    $_SESSION['status'] = 'Not allowed';
    wp_redirect(get_permalink(4506));
}


update_post_meta($clan_ID, 'autojoin_allowed', $_POST['autojoin']);
update_post_meta($clan_ID, 'autojoin_playstyle', $_POST['playstyle']);
update_post_meta($clan_ID, 'autojoin_description', $_POST['description']);



 
$_SESSION['status'] = 'Autojoin settings saved';
wp_redirect(get_permalink(3601));
