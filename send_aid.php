<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();
if (get_field('game_status', 'option') == 'Live') {
	$array = array();
    $user_ID = get_current_user_id();
    $clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
    $clanmembers = get_post_meta($clan_ID, 'clan_members', true);
    $receiver = $_POST['receiver'];



    if (! defined('ABSPATH')) {
		$array['status'] = 'You must log in to perform this action';
	    $array['next'] = false;
		echo json_encode($array);
		exit;
    }
    if (empty($user_ID)) {
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



    if (!in_array($receiver, $clanmembers)) {
        $array['status'] = 'Not a clan member';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    $receiver_NW = get_user_meta($receiver, 'networth', true);
    $sender_NW = get_user_meta($user_ID, 'networth', true);

    if ($receiver_NW > $sender_NW) {
        $array['status'] = 'You cannot aid a member larger in networth';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    $userLock = get_user_meta($user_ID, 'user_lock', true);

    if ($userLock == 1) {
        $array['status'] = 'Try again';
		$array['next'] = false;
	    echo json_encode($array);
	    update_user_meta($user_ID, 'user_lock', 0);
		exit;
    }
    

    $aid_sent = get_user_meta($user_ID, 'aid_sent_today', true);
    update_user_meta($user_ID, 'user_lock', 1);

    if ($aid_sent >= 3) {
        $array['status'] = 'You already sent aid 3 times today';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    $money = get_user_meta($user_ID, 'money', true);
    $aid = $_POST['amount'];

    if ($aid > $money) {
        $array['status'] = 'Insufficient funds';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    if ($_POST['amount'] <= 0) {
        $array['status'] = 'Enter a valid number';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    if (empty($_POST['amount'])) {
        $letter_check = 0;
    } else {
        $letter_check = $_POST['amount'];
    }

    if (!is_numeric($letter_check)) {
        $array['status'] = 'Enter a valid number';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }


    if ($aid > 250000) {
        $aid = 250000;
    }

    update_user_meta($user_ID, 'money', $money-$aid);
    $money_receiver = get_user_meta($receiver, 'money', true);
    update_user_meta($receiver, 'money', $money_receiver+$aid);

    $aid_sent = get_user_meta($user_ID, 'aid_sent_today', true);
    update_user_meta($user_ID, 'aid_sent_today', $aid_sent+1);

/* Update event count */
    $event_count = get_user_meta($receiver, 'new_events', true);
    update_user_meta($receiver, 'new_events', $event_count + 1);

/* Create event */
    $timestamp = current_time('timestamp');
    $args = array(
    'post_title'    => 'Aid sent by '.$user_ID.' Receiver: '.$receiver,
    'post_status'   => 'publish',
    'post_type'     => 'event_local',
    'post_author'   => $user_ID
    );
            
    $new_event_id = wp_insert_post($args);
	update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
    update_field('defender_id', $receiver, $new_event_id);
    update_field('attacker_id', $user_ID, $new_event_id);
    update_field('attacktype', 'aid', $new_event_id);
    update_field('time_attacked', $timestamp, $new_event_id);
    update_field('money_lost', $aid, $new_event_id);
    update_field('attacker_clan_id', $clan_ID, $new_event_id);

    $clan_att = get_user_meta($user_ID, 'clan_id_user', true);
    $clan_members_att = maybe_unserialize(get_post_meta($clan_att, 'clan_members',true));

        foreach ($clan_members_att as $member_att) {
            $globals = get_user_meta($member_att, 'new_global_events', true);
            update_user_meta($member_att, 'new_global_events', $globals+1);
        }
   

    $totAidSent = get_user_meta($user_ID, 'total_aid_sent', true);
    update_user_meta($user_ID, 'total_aid_sent', $totAidSent+$aid);

    $noAids = get_user_meta($user_ID, 'number_of_aids', true);
    update_user_meta($user_ID, 'number_of_aids', $noAids+1);

    $aidRec = get_user_meta($receiver, 'aid_received', true);
    update_user_meta($receiver, 'aid_received', $aidRec+$aid);

    $file = 'aidlog.txt';
// Open the file to get existing content
    $current = file_get_contents($file);
// Append a new person to the file
    $current .= "ID: ".$user_ID." Receiver: ".$receiver."\n";
    $current .= "Aid sent: ".$aid."\n\n";
// Write the contents back to the file
    file_put_contents($file, $current);

	$member_data = get_userdata($receiver);
    update_user_meta($user_ID, 'user_lock', 0);
    $array['status'] = '$ '.number_format($aid, 0, ',', ' ').' aid sent to '.$member_data->display_name.' (#'.$receiver.')';
	$array['money'] = $money-$aid;
	$array['noaids'] = $noAids+1;
	$array['next'] = true;
	echo json_encode($array);
	exit;
}
