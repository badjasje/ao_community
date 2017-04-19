<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );

nocache_headers();
if(get_field('game_status','option') == 'Live'){
$user_ID = get_current_user_id(); 
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$clanmembers = get_post_meta($clan_ID,'clan_members',true);




if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(49609)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(49609)); exit;
	}
$receiver = $_POST['receiver'];
$aid_sent = get_user_meta($user_ID, 'aid_sent_today', true);


if (!in_array($receiver, $clanmembers)){
	wp_redirect(get_permalink(49609)); exit;
}

$receiver_NW = get_user_meta($receiver, 'networth',true);
$sender_NW = get_user_meta($user_ID, 'networth',true);

if($receiver_NW > $sender_NW){
	wp_redirect(get_permalink(49609)); exit;
	}
			



if($aid_sent == 3){
	wp_redirect(get_permalink(49609)); exit;
}
$money = get_user_meta($user_ID, 'money',true);
$aid = $_POST['amount'];

if($aid > $money){
	$_SESSION['status'] = 'Insufficient funds';wp_redirect(get_permalink(49609)); exit;
}
if($_POST['amount'] < 0){$_SESSION['status'] = 'Enter a valid number';wp_redirect(get_permalink(49609)); exit;}

if(empty($_POST['amount'])){
		$letter_check = 0;
	}
		else
	{
		$letter_check = $_POST['amount'];
	}

if(!is_numeric($letter_check)){$_SESSION['status'] = 'Enter a valid number';
	wp_redirect(get_permalink(49609)); exit;
	}


if($aid > 250000){
	$aid = 250000;
}

update_user_meta($user_ID, 'money',$money-$aid);
$money_receiver = get_user_meta($receiver, 'money',true);
update_user_meta($receiver, 'money',$money_receiver+$aid);

$aid_sent = get_user_meta($user_ID, 'aid_sent_today', true);
update_user_meta($user_ID, 'aid_sent_today', $aid_sent+1);

/* Update event count */
$event_count = get_user_meta($receiver, 'new_events',true);
update_user_meta($receiver, 'new_events', $event_count + 1);

/* Create event */
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
	'post_title'    => 'Aid sent by '.$user_ID.' Receiver: '.$receiver,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $user_ID
);
			
$new_event_id = wp_insert_post( $args );

update_field('defender_id',$receiver, $new_event_id);
update_field('attacker_id',$user_ID, $new_event_id);
update_field('attacktype','aid', $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);
update_field('money_lost', $aid, $new_event_id);


$file = 'aidlog.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "ID: ".$user_ID." Receiver: ".$receiver."\n";
$current .= "Aid sent: ".$aid."\n\n";
// Write the contents back to the file
file_put_contents($file, $current);



$_SESSION['status'] = '$ '.number_format($aid, 0, ',', ' ').' aid sent';
wp_redirect(get_permalink(49609)); exit;

}