<?php
/**
 * Handles leacing
 *
 * @package WordPress
 */

if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: GET');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );

$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}


$user = $_GET['user'];
$clan = get_user_meta($user, 'clan_id_user',true);

$ct_1 = get_post_meta($clan,'ct_1',true);
$ct_2 = get_post_meta($clan,'ct_2',true);
$ct_3 = get_post_meta($clan,'ct_3',true);
$ct_4 = get_post_meta($clan,'ct_4',true);

$previous_members = get_post_meta($clan,'previous_members');
$previous_members = array_shift($previous_members);
$previous_members[] = $user;

update_post_meta($clan, 'previous_members', $previous_members);


if($user == $user_ID){
$clan_members = get_post_meta($clan,'clan_members');

$clan_members = array_shift($clan_members);	

foreach ($clan_members as $key => $member) {	
	if($member == $user){

unset($clan_members[$key]);

}}

if($user == $ct_1){
	update_post_meta($clan, 'ct_1', 0);
}
if($user == $ct_2){
	update_post_meta($clan, 'ct_2', 0);
}
if($user == $ct_3){
	update_post_meta($clan, 'ct_3', 0);
}
if($user == $ct_4){
	update_post_meta($clan, 'ct_4', 0);
}

update_post_meta($clan, 'clan_members', $clan_members);
update_user_meta($user,'clan_id_user',0);
$timestamp = strtotime(date('Y-m-d H:i:s'));
update_user_meta($user,'new_clan_timestamp',$timestamp+86400);



$_SESSION['status'] = 'You left your clan';
wp_redirect(get_permalink(3601));
}

