<?php
/**
 * Handles exploration
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

$user_ID = get_current_user_id(); 

$exploreUrl = get_permalink(3582);

if ( ! defined( 'ABSPATH' ) ) exit;
if(empty($user_ID)){
	wp_redirect($exploreUrl); exit;
}
if ( !is_user_logged_in() ) {
	wp_redirect($exploreUrl); exit;
	}
$ownedland = get_user_meta($user_ID, 'land');
$money = get_user_meta($user_ID, 'money');
$sold_land_today = get_user_meta($user_ID, 'land_sold_today');
$freeland = $ownedland[0]-get_user_meta($user_ID, 'builtland')[0];

$freeland = get_user_meta($user_ID, 'land')[0]-get_user_meta($user_ID, 'builtland')[0];
if($freeland < 0){$_SESSION['status'] = 'Cannot sell! Not enough free land';wp_redirect($exploreUrl); exit;}
if($_POST['land'] < 0){$_SESSION['status'] = 'Enter a valid number';wp_redirect($exploreUrl); exit;}
if(!is_numeric($_POST['land'])){$_SESSION['status'] = 'Enter a valid number';wp_redirect($exploreUrl); exit;}

if($_POST['land'] > $freeland){$_SESSION['status'] = 'Enter a valid number';wp_redirect($exploreUrl); exit;}
if((20000-$sold_land_today[0]) >= $_POST['land']){

update_user_meta($user_ID,'land',$ownedland[0]-$_POST['land']);
update_user_meta($user_ID,'land_sold_today',$sold_land_today[0]+($_POST['land']));
update_user_meta($user_ID,'money',$money[0]+($_POST['land']*75));

$_SESSION['status'] = 'You sold '.number_format($_POST['land'], 0, ',', ' ').' m<sup>2</sup>';
count_all_stats($user_ID);
wp_redirect($exploreUrl.'?tab=sell'); exit;
}

if((20000-$sold_land_today[0]) < $_POST['land']){
$_SESSION['status'] = 'Cannot sell any more land';
wp_redirect($exploreUrl); exit;
}

