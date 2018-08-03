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
global $userId;
global $userData;

$invitekey = $_GET['invite'];
$clan = $_GET['clan'];

$open_invites = get_post_meta($_GET['clan'], 'open_invites');

$clan_ID = $userData['clan_id_user'][0];
if ($clan_ID == 0) {
    foreach ($open_invites[0] as $key => $invite) {
        if ($invite['invite'] == $invitekey) {
            if ($invite['clan'] == $clan) {
                if ($invite['user'] != $userId) {
                    wp_redirect(get_permalink(3656));
                }
                if ($invite['user'] == $userId) {
                    unset($open_invites[0][$key]);

                    update_post_meta($_GET['id'], 'invite_status', 'accept');
                    update_post_meta($clan, 'open_invites', $open_invites[0]);
                    wp_redirect(get_permalink($clan));
                }
            }
        }
    }
} else {
    wp_redirect(get_permalink(3656));
}
