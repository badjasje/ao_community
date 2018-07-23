<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}


$userId = get_current_user_ID();
$slug = strtolower($_POST['clanname']);

$args = array(
    'post_type'     => 'clan',
    'posts_per_page'   => -1,
    'name'    => $slug
);

$posts = get_posts($args);

if (count($posts) != 0) {
  $array['status'] = 'This clan name already exists';
  $array['next'] = false;
  echo json_encode($array);
  exit;
}


$args = array(
    'post_title'    => $_POST['clanname'],
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_type'     => 'clan',
    'post_author'   => $userId
    );
$timestamp = current_time('timestamp');
            
$new_order_id = wp_insert_post($args);
update_field('clan_tag', $_POST['clantag'], $new_order_id);
update_field('clan_leader', $userId, $new_order_id);
            update_user_meta($userId, 'clan_id_user', $new_order_id);
            update_user_meta($userId, 'clan_message', $new_order_id);
    
$clan_membersnew = array();
$clan_membersnew[] = $userId;
update_field('clan_members', $clan_membersnew, $new_order_id);
update_field('ct_1', 0, $new_order_id);
update_field('ct_2', 0, $new_order_id);
update_field('ct_3', 0, $new_order_id);
update_field('ct_4', 0, $new_order_id);
update_post_meta($new_order_id, 'bonus_level', 0);
update_post_meta($new_order_id, 'clan_points', 0);
 $array['status'] = 'Clan successfully created';
$array['next'] = true;
echo json_encode($array);
exit;