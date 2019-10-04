<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH')) {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
global $userId;
global $userData;

$clan_ID = $userData['clan_id_user'][0];
$clanData = get_post_meta($clan_ID);
$clanleader = $clanData['clan_leader'][0];
$changecount = $clanData['clan_name_change'][0];
if(get_field('game_status', 'option') != 'Live') $changecount = 0;
$data = maybe_unserialize( $_POST );
$clanTag = ctype_space($data['clantag']);
$clanName = ctype_space($data['clanname']);
$array = array('clan_updated' => false, 'status' => 'Unknown error');

if ($userId == $clanleader && $clan_ID == $data['id'] && (empty($changecount) || $changecount != 1)) {
    if($clanTag == $clanData['clan_tag'][0] && $clanName == get_the_title($clan_ID)) {
        $array['status'] = 'Please change tag or name';
    }
    else {
        // Check if tag or name exists.
        $clans = get_posts(['numberposts' => -1, 'post_type' => 'clan', 'meta_key' => 'clan_tag', 'meta_value' => $clanTag]);
        if(count($clans) > 0 && $clans[0]->ID != $clan_ID) {
            $array['status'] = 'This clan tag already exists';
        }
        else {
            $slug = strtolower($clanName);
            $clans = get_posts(array('post_type' => 'clan', 'posts_per_page' => -1, 'name' => $slug));
            if(count($clans) > 0&& $clans[0]->ID != $clan_ID) {
                $array['status'] = 'This clan name already exists';
            }
            else {
                $my_post = array('ID' => $clan_ID, 'post_title' => $clanName);
                wp_update_post($my_post);
                update_post_meta($clan_ID, 'clan_tag', $clanTag);
                update_post_meta($clan_ID, 'clan_name_change', 1);
                $array['status'] = 'Clan name and tag updated';
                $array['clan_updated']  = true;
            }
        }
    }
}
//wp_redirect(get_permalink(3601));
echo json_encode($array);
exit;
