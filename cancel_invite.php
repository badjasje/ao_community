<?php
/**
 * Handles invite cancellations
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

$userId = get_current_user_ID();
$inviteKey = isset($_GET['invite']) ? $_GET['invite'] : '';

$clanId = get_user_meta($userId, 'clan_id_user',true);


$openInvites = maybe_unserialize(get_post_meta($clanId, 'open_invites',true));

if (!is_array($openInvites)) {
    exit();
}

foreach ($openInvites as $key => $invite) {
    if ($invite['invite'] == $inviteKey && $invite['clan'] == $clanId) {
        unset($openInvites[$key]);
        update_post_meta($clanId, 'open_invites', maybe_serialize($openInvites));
        wp_redirect(get_permalink(3801));
        wp_delete_post($invite['invite_id']);
    }
}