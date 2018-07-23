<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
$user_ID = get_current_user_id();
$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
$clanleader = get_post_meta($clan_ID, 'clan_leader', true);

if ($user_ID == $clanleader && $clan_ID == $_GET['id']) {
    $my_post = array(
      'ID'           => $clan_ID,
      'post_title'   => $_POST['clanname'],
    );

// Update the post into the database
    wp_update_post($my_post);
    update_post_meta($clan_ID, 'clan_tag', $_POST['clantag']);
    update_post_meta($clan_ID, 'clan_name_change', 1);
}
    

$_SESSION['status'] = 'Clan name changed';
wp_redirect(get_permalink(3601));
exit;
