<?php
/**
 * Handles clan invite creation
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

$userId = get_current_user_id();

$invitee = $_GET['user'];
$clanId = get_user_meta($userId, 'clan_id_user',true);

$openInvites = maybe_unserialize(get_post_meta($clanId, 'open_invites',true));


if(!is_array($openInvites)){
	$openInvites = array();
}



foreach($openInvites as $key => $openInvite) {
    if (!is_array($openInvite)) {
       unset($openInvites[$key]);
    }
}

if ($openInvites == 0 || empty($openInvites)) {
    $openInvites = [];
}

// Generate a random invite code...
$inviteKey = strtoupper(substr(md5(microtime()),rand(0,26),10));

$args = [
    'post_title'    => sprintf('Clan Invite for: %s (#%d)', get_the_title($clanId), $clanId),
    'post_content'  => $inviteKey,
    'post_status'   => 'publish',
    'post_type'     => 'user_message',
    'post_author'   => $userId
];
            
$newOrderId = wp_insert_post($args);
update_field('invite_hash', $inviteKey, $newOrderId);
update_field('clan_id_invited', $clanId, $newOrderId);
update_field('receiver_id', $_GET['user'], $newOrderId);
            
$subArgs = [
    'post_title'    => sprintf('Clan Invite for: %s (#%d)', get_the_title($clanId), $clanId),
    'post_content'  => $inviteKey,
    'post_status'   => 'publish',
    'post_type'     => 'sub_user_message',
    'post_author'   => $userId
];

$newSubId = wp_insert_post($subArgs);
update_field('parent_message_id', $newOrderId, $newSubId);
update_field('invite_hash', $inviteKey, $newSubId);
update_field('clan_id_invited', $_GET['clan'], $newSubId);
update_field('receiver_id', $_GET['user'], $newSubId);
            
$openInvites[] = [
    'user' => $invitee,
    'clan' => $clanId,
    'invite' => $inviteKey,
    'invite_id' => $newOrderId
];

update_post_meta($_GET['clan'], 'open_invites', maybe_serialize($openInvites));
update_user_meta($invitee, 'new_messages', 1);
            
$_SESSION['status'] = 'Invite sent';
wp_redirect(get_permalink(3520).'?id='.$invitee);
exit;
