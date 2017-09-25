<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

if ('GET' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3491));
    exit;
}
$user_ID = get_current_user_ID();

$invitekey = $_GET['invite'];
$user = $_GET['user'];
$clan = $_GET['clan'];





$open_invites = get_post_meta($_GET['clan'], 'open_invites');
if ($open_invites == 0 || empty($open_invites)) {
    $open_invites = array();
}
//$open_invites = array_shift($open_invites);



    $args = array(
                'post_title'    => 'Clan Invite for: '.get_the_title($clan).' (#'.$clan.')',
                'post_content'  => $_GET['invite'],
                'post_status'   => 'publish',
                'post_type'     => 'user_message',
                'post_author'   => $user_ID
                );
            
            
            $new_order_id = wp_insert_post($args);
            update_field('invite_hash', $_GET['invite'], $new_order_id);
            update_field('clan_id_invited', $_GET['clan'], $new_order_id);
            update_field('receiver_id', $_GET['user'], $new_order_id);
            
    $subargs = array(
                'post_title'    => 'subm Clan Invite for: '.get_the_title($clan).' (#'.$clan.')',
                'post_content'  => $_GET['invite'],
                'post_status'   => 'publish',
                'post_type'     => 'sub_user_message',
                'post_author'   => $user_ID
                );
            
            
            $new_sub_id = wp_insert_post($subargs);
            update_field('parent_message_id', $new_order_id, $new_sub_id);
            update_field('invite_hash', $_GET['invite'], $new_sub_id);
            update_field('clan_id_invited', $_GET['clan'], $new_sub_id);
            update_field('receiver_id', $_GET['user'], $new_sub_id);
            
            
    
            
            $open_invites[] = array('user'=> $user, 'clan'=>$clan, 'invite'=>$invitekey, 'invite_id'=>$new_order_id);


            update_post_meta($_GET['clan'], 'open_invites', $open_invites);
            update_user_meta($user, 'new_messages', 1);
            
            $_SESSION['status'] = 'Invite sent';
            wp_redirect(get_permalink(3520).'?id='.$_GET['user']);
    exit;
