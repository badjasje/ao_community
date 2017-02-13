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

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
$ownedland = get_user_meta($user_ID, 'land');
$explored_today = get_user_meta($user_ID, 'explored_today');
$perturnm2 = 200-((ceil($ownedland[0]*0.002)));
if($perturnm2 < 50){
	$perturnm2 = 50;
}
$freeland = get_user_meta($user_ID, 'builtland')[0]/$ownedland[0];


if($_POST['turns'] < 0){$_SESSION['status'] = '12';wp_redirect(get_permalink(3582)); exit;}
if(!is_numeric($_POST['turns'])){$_SESSION['status'] = '12';wp_redirect(get_permalink(3582)); exit;}
if($perturnm2 <0){$_SESSION['status'] = '16';wp_redirect(get_permalink(3582)); exit;}
$turns = get_user_meta($user_ID, 'turns');
if((20000-$explored_today[0]) < ($perturnm2*$_POST['turns'])){$_SESSION['status'] = '3';wp_redirect(get_permalink(3582)); exit;}

if($turns[0] < $_POST['turns']){$_SESSION['status'] = '1';wp_redirect(get_permalink(3582)); exit;}else{





update_user_meta($user_ID,'turns',$turns[0]-$_POST['turns']);
update_user_meta($user_ID,'land',$ownedland[0]+($perturnm2*$_POST['turns']));
update_user_meta($user_ID,'explored_today',($perturnm2*$_POST['turns'])+$explored_today[0]);
$_SESSION['status'] = '2';
$_SESSION['explored'] = $perturnm2*$_POST['turns'];
wp_redirect(get_permalink(3582)); exit;


}