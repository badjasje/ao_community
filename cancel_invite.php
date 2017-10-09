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

$userID = get_current_user_ID();
$inviteKey = isset($_GET['invite']) ? $_GET['invite'] : '';

$clanIds = get_user_meta($userId, 'clan_id_user');
$clanId = array_shift($clanIds);

$openInvites = get_post_meta($clanId, 'open_invites');

if (!is_array($openInvites)) {
    exit();
}

foreach ($openInvites[0] as $key => $invite) {
    if ($invite['invite'] == $inviteKey && $invite['clan'] == $clan) {
        unset($openInvites[0][$key]);
        update_post_meta($clan, 'open_invites', $openInvites[0]);
        wp_redirect(get_permalink(3801));
        wp_delete_post($invite['invite_id']);
    }
}
