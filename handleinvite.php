<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require( dirname(__FILE__) . '/wp-load.php' );

$userId = get_current_user_id();

if (!defined( 'ABSPATH')) exit;

if(empty($userId)){
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if (!is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$inviteKey = $_POST['hash'];
$clan = $_POST['clan'];

$clanId = get_user_meta($userId, 'clan_id_user',true);

if($clanId != 0){
	$array['status'] = 'You are already a member of a clan';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if($_POST['target'] == 'accept') {

    $clanMembers = maybe_unserialize(get_post_meta($clan,'clan_members',true));

    $clanLeader = get_post_meta($clan,'clan_leader',true);
    $timestamp = current_time('timestamp');

    $endDate = get_field('end_date','option');
    $endStamp = strtotime($endDate);
    $timeLeft = $endStamp-$timestamp;
    $marketClose = $timeLeft - 172800;
    if($timeLeft < 172800){
        $array['status'] = 'Cannot join a clan the last 48 hours of a round';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    // Todo: The number '5' should be contained inside a configuration file / constant.
    if(count($clanMembers) >= 6){
        $array['status'] = 'Maximum number of clan members reached';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    $open_invites = maybe_unserialize(get_post_meta($clan,'open_invites',true));

    if(!is_array($open_invites)){
        $open_invites = array();
    }

    foreach ($open_invites as $key => $invite) {
        if($invite['invite'] == $inviteKey && $invite['clan'] == $clan) {
            if($invite['user'] != $userId) {
                $array['status'] = "This is not the invite you're looking for";
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }

            if($invite['user'] == $userId) {
                update_user_meta($userId,'clan_id_user',$clan);

                $clanMembers[] = $userId;

                unset($open_invites[$key]);

                update_post_meta($clan, 'clan_members', $clanMembers);
                update_post_meta($invite['invite_id'], 'invite_status', 'accept');
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
                    foreach ($clanMembers as $member) {
                        $globals = get_user_meta($member, 'new_global_events', true);
                        update_user_meta($member, 'new_global_events', $globals + 1);
                    }
                }

                $array['status'] = "You are now a member of ".get_the_title($clan);
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
    }

} else { // decline

    $open_invites = maybe_unserialize(get_post_meta($clan, 'open_invites',true));

    foreach ($open_invites as $key => $invite) {
        if ($invite['invite'] == $invitekey && $invite['clan'] == $clan && $invite['user'] == $userId) {
            unset($open_invites[$key]);
            update_post_meta($invite['invite_id'], 'invite_status', 'accept');
            update_post_meta($clan, 'open_invites', $open_invites);
            $array['status'] = "You declined the invite of ".get_the_title($clan);
            $array['next'] = false;
            echo json_encode($array);
            exit;
        }
    }
}