<?php
/**
 * Handles clan invites
 *
 * @package WordPress
 */

if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require( dirname(__FILE__) . '/wp-load.php' );

$userId = get_current_user_id();

if ( ! defined( 'ABSPATH' ) ) exit;
if(empty($userId)){
    wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) {
    wp_redirect(get_permalink(3582)); exit;
}

$inviteKey = $_GET['invite'];
$clan = $_GET['clan'];

$clanMembers = get_post_meta($_GET['clan'],'clan_members');
$clanLeader = get_post_meta($clan,'clan_leader',true);
$timestamp = current_time('timestamp');

$endDate = get_field('end_date','option');
$endStamp = strtotime($endDate);
$timeLeft = $endStamp-$timestamp;
$marketClose = $timeLeft - 172800;
if($timeLeft<172800){
	$_SESSION['status'] = 'Cannot join a clan the last 48 hours of a round';
	wp_redirect(get_permalink(3601)); exit;
}

// Todo: The number '5' should be contained inside a configuration file / constant.
if(count($clanMembers[0]) >= 6){
    wp_redirect(get_permalink(3601)); exit;
}

$open_invites = maybe_unserialize(get_post_meta($_GET['clan'],'open_invites',true));

if(!is_array($open_invites)){
	$open_invites = array();
}

$clanId = get_user_meta($userId, 'clan_id_user',true);


if($clanId == 0){
    foreach ($open_invites as $key => $invite) {
        if($invite['invite'] == $inviteKey && $invite['clan'] == $clan) {
            if($invite['user'] != $userId) {
                wp_redirect(get_permalink(3601));
            }
            
            if($invite['user'] == $userId) {
                update_user_meta($userId,'clan_id_user',$clan);

                $clanMembers = array_shift($clanMembers);
                $clanMembers[] = $userId;

                unset($open_invites[0][$key]);

                update_post_meta($clan, 'clan_members', $clanMembers);
                update_post_meta($_GET['id'], 'invite_status', 'accept');
                update_post_meta($clan, 'open_invites', $open_invites[0]);
                update_user_meta($userId, 'clan_join_stamp', $timestamp+86400);

                $args = [
                    'post_title'    => 'Clan member joined a clan: '.$userId,
                    'post_status'   => 'publish',
                    'post_type'		=> 'event_local',
                    'post_author'   => $clanLeader
                ];

                $newEventId = wp_insert_post( $args );
                update_field('attacktype','user_change', $newEventId);
                update_field('outcome','joined', $newEventId);
                update_field('attacker_id',$clanLeader, $newEventId);
                update_field('defender_id',$userId, $newEventId);
                update_field('attacker_clan_id',$clan, $newEventId);
                update_field('time_attacked',$timestamp, $newEventId);

                if (!empty($clan) || $clan != 0) {
	                $clanMembers = get_post_meta($_GET['clan'],'clan_members');
                    foreach ($clanMembers as $member) {
                        $globals = get_user_meta($member, 'new_global_events', true);
                        update_user_meta($member, 'new_global_events', $globals + 1);
                    }
                }

                wp_redirect(get_permalink($clan));
            }
        }
    }
}
else {
    wp_redirect(get_permalink(3601));
}