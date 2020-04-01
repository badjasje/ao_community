<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
global $userId;
global $userData;
$declarer_ID = $userId;
$timestamp = current_time('timestamp');

if (! defined('ABSPATH')) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
if (empty($declarer_ID)) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'You must be logged in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}

$war_status = get_post_status($_POST['war']);
if($war_status == 'trash') {
    $array['status'] = 'Already peaced';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}

$warTime = get_the_title($_POST['war']);
$canPeace = (!!$warTime && $timestamp-$warTime > 86400);
if(!$canPeace) {
    $array['status'] = 'You cannot peace yet!';
    $array['next'] = false;
    echo json_encode($array);
    update_user_meta($declarer_ID, 'user_lock', 0);
    exit;
}

$declarer_clan_ID = $userData['clan_id_user'][0];
$clan_leader = get_post_meta($declarer_clan_ID, 'clan_leader', true);

$cts=array();
for($i=1; $i<=Settings::get('clan_trustee_num'); $i++) {
    $cts[$i] = get_post_meta($declarer_clan_ID, 'ct_'.$i, true);
}
$allowed = array_merge($cts, array($clan_leader));

if(in_array($declarer_ID, $allowed)) {
    $declared_on = get_post_meta($_POST['war'], 'declared_on', true);
    $def_clan_leader = get_post_meta($declared_on, 'clan_leader', true);

    $args = array(
        'post_title'    => 'PEACE',
        'post_status'   => 'publish',
        'post_type'     => 'event_local',
        'post_author'   => 1
    );
    $new_event_id = wp_insert_post($args);

    update_field('attacktype', 'peace_declared', $new_event_id);

    update_field('attacker_clan_id', $declarer_clan_ID, $new_event_id);
    update_field('defender_clan_id', $declared_on, $new_event_id);

    update_field('attacker_id', $declarer_ID, $new_event_id);
    update_field('defender_id', $def_clan_leader, $new_event_id);
	update_field('dec_message', $_POST['dec_msg'], $new_event_id);
    update_field('time_attacked', $timestamp, $new_event_id);

    /* add clan to cooldown list */
    $cooldownlist = maybe_unserialize(get_post_meta($declarer_clan_ID, 'cooldown_list', true));
    if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
    if(!is_array($cooldownlist)) $cooldownlist = array();
	$clan_ID = $declared_on;
    $cooldownlist[$clan_ID] = $timestamp + Settings::get('cooldown_time');
    update_post_meta($declarer_clan_ID, 'cooldown_list', $cooldownlist);

    /* update events */
    $clan_members = get_post_meta($declared_on, 'clan_members');
    foreach ($clan_members[0] as $member) {
        $globals = get_user_meta($member, 'new_global_events', true);
        update_user_meta($member, 'new_global_events', $globals+1);
    }

    $clan_members2 = get_post_meta($declarer_clan_ID, 'clan_members');
    foreach ($clan_members2[0] as $member2) {
        $globals = get_user_meta($member2, 'new_global_events', true);
        update_user_meta($member2, 'new_global_events', $globals+1);
    }

    wp_trash_post($_POST['war']);

    $array['status'] = 'Peace declared on '.get_the_title($_POST['clan']);;
	$array['next'] = true;
	echo json_encode($array);
	update_user_meta($declarer_ID, 'user_lock', 0);
	exit;
}
