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

global $userId;
global $userData;
$timestamp = current_time('timestamp');
$invitee = intval($_GET['user']);
$invitee_data = get_userdata($invitee);
if ($invitee_data === false) {
    exit;
}
$status = get_user_meta($invitee, 'status', true);
if ($status == 'banned') {
    exit;
}
$clanId = $userData['clan_id_user'][0];

// Check if previously invited
$previous_members = maybe_unserialize(get_post_meta($clanId, 'previous_members', true));
if(!is_array($previous_members) || get_field('game_status', 'option') == 'Pause') $previous_members = array(); //
if(in_array($invitee, $previous_members)) {
    $array['status'] = 'Cannot invite, this user has been a member already';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

// Check if invite already sent
$openInvites = maybe_unserialize(get_post_meta($clanId, 'open_invites',true));
if(!is_array($openInvites)) $openInvites = array();
foreach($openInvites as $key => $openInvite) {
    if (!is_array($openInvite)) unset($openInvites[$key]);
    else if($openInvite['user'] == $invitee) {
        $array['status'] = 'Invite already sent';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }
}
if ($openInvites == 0 || empty($openInvites)) $openInvites = array();


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
update_field('receiver_id', $invitee, $newOrderId);
update_field('sender_id', $userId, $newOrderId);
update_field('last_update_stamp', $timestamp, $newOrderId);

$openInvites[] = [
    'user' => $invitee,
    'clan' => $clanId,
    'invite' => $inviteKey,
    'invite_id' => $newOrderId
];

update_post_meta($clanId, 'open_invites', maybe_serialize($openInvites));
$msgs = get_user_meta( $invitee, 'new_messages', true );
update_user_meta($invitee, 'new_messages', $msgs+1);

//$_SESSION['status'] = 'Invite sent';
//wp_redirect(get_permalink(3520).'?id='.$invitee);
$array['status'] = 'Invite sent';
$array['next'] = true;
echo json_encode($array);
exit;