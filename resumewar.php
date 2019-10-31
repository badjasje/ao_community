<?php
require_once("wp-load.php");

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

if (!is_user_logged_in()) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

global $userId;
global $userData;

$declaredonID = $_POST['declaredon'];
$declaredbyID = $userData['clan_id_user'][0];

$def_clan_leader = get_post_meta($declaredonID, 'clan_leader', true);

$posts = get_posts(array(
    'numberposts' => 1, 'post_type' => 'wars', 'post_status' => 'trash',
    'meta_query' => array(
        'relation' => 'AND',
        array('key' => 'declared_by', 'value' => $declaredbyID),
        array('key' => 'declared_on', 'value' => $declaredonID),
    ),
));

if (count($posts) == 0) {
    $array['status'] = 'War not found';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

// Look for peace event to get peace-time
$peacetime = 0;
$eventposts = get_posts(array(
    'numberposts' => 1, 'post_title' => 'PEACE', 'post_status'   => 'publish', 'post_type'     => 'event_local',
    'meta_query' => array(
        'relation' => 'AND',
        array('key' => 'attacker_clan_id', 'value' => $declaredbyID),
        array('key' => 'defender_clan_id', 'value' => $declaredonID),
    ),
));
if(count($eventposts)) {
    $peacetime = get_post_meta($eventposts[0]->ID,'time_attacked', true);
}

$timestamp = current_time('timestamp');
$resume_time = Settings::get('resume_after_hours');
if($timestamp - $peacetime < (60*60* $resume_time ) ) {
    $array['status'] = 'You can only resume after '.$resume_time.' hours of peace';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$my_post = array('ID' => $posts[0]->ID,'post_status' => 'publish','post_title' => $timestamp);

// Update the post into the database
wp_update_post($my_post);

$cooldownlist = maybe_unserialize(get_post_meta($declaredbyID, 'cooldown_list', true));
if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
if(!is_array($cooldownlist)) $cooldownlist = array();
unset($cooldownlist[$declaredonID]);
update_post_meta($declaredbyID, 'cooldown_list', $cooldownlist);

$args = array('post_title' => 'WAR RESUMED', 'post_status' => 'publish', 'post_type' => 'event_local','post_author' => 1);
$new_event_id = wp_insert_post($args);
update_field('attacktype', 'war_declared', $new_event_id);

update_field('attacker_clan_id', $declaredbyID, $new_event_id);
update_field('defender_clan_id', $declaredonID, $new_event_id);

update_field('attacker_id', $userId, $new_event_id);
update_field('defender_id', $def_clan_leader, $new_event_id);
update_field('dec_message', $_POST['resume_msg'], $new_event_id);
update_field('outcome', 'resume', $new_event_id);
update_field('time_attacked', $timestamp, $new_event_id);

$clan_members = get_post_meta($declaredonID, 'clan_members');
foreach ($clan_members[0] as $member) {
    $globals = get_user_meta($member, 'new_global_events', true);
    update_user_meta($member, 'new_global_events', $globals+1);
}

$clan_members = get_post_meta($declaredbyID, 'clan_members');
foreach ($clan_members2 as $member) {
    $globals = get_user_meta($member, 'new_global_events', true);
    update_user_meta($member2, 'new_global_events', $globals+1);
}

$array['status'] = 'War resumed against '.get_the_title($declaredonID).'(#'.$declaredonID.')';
$array['next'] = true;
echo json_encode($array);
exit;