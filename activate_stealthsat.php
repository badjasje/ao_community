<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */


require( dirname(__FILE__) . '/wp-load.php' );


$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(8578)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(8578)); exit;
	}

$sat_owned = get_user_meta($user_ID, 'sat_owned',true);
$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

if($sat_owned != 'stealths'){
	wp_redirect(get_permalink(8578)); exit;
}

if($sat_morale < 100){
	wp_redirect(get_permalink(8578)); exit;
}

$timestamp = strtotime(date('Y-m-d H:i:s'));

update_user_meta($user_ID, 'stealth_sat_status', 'active');
update_user_meta($user_ID, 'stealth_sat_time', $timestamp+3600*7);
update_user_meta($user_ID, 'sat_morale', $sat_morale-100);


$_SESSION['status'] = '999';
wp_redirect(get_permalink(8578)); exit;