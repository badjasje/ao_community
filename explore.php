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
$explored_today = get_user_meta($user_ID, 'explored_today');
$perturnm2 = 200-((ceil($ownedland[0]*0.002)));
if($perturnm2 < 25){
	$perturnm2 = 25;
}

$freeland = get_user_meta($user_ID, 'builtland')[0]/$ownedland[0];


if($_POST['turns'] < 0){
	$_SESSION['status'] = 'Enter a valid number';
	wp_redirect($exploreUrl); exit;
	}

if(!is_numeric($_POST['turns'])){
	$_SESSION['status'] = 'Enter a valid number';
	wp_redirect($exploreUrl); exit;
	}


if($perturnm2 <0){
	$_SESSION['status'] = 'No more exploring possible';
	wp_redirect($exploreUrl); exit;
	}
	
$turns = get_user_meta($user_ID, 'turns');
if((20000-$explored_today[0]) < ($perturnm2*$_POST['turns'])){
	
	$_SESSION['status'] = 'You can only explore '. number_format(20000-get_user_meta($user_ID, 'explored_today',true), 0, ',', ' ').' m<sup>2</sup></strong> more land.';
	wp_redirect($exploreUrl); exit;}

if($turns[0] < $_POST['turns']){
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect($exploreUrl); exit;
	}else{





update_user_meta($user_ID,'turns',$turns[0]-$_POST['turns']);
update_user_meta($user_ID,'land',$ownedland[0]+($perturnm2*$_POST['turns']));
update_user_meta($user_ID,'explored_today',($perturnm2*$_POST['turns'])+$explored_today[0]);
$_SESSION['status'] = number_format($perturnm2*$_POST['turns'], 0, ',', ' ').' m<sup>2</sup> explored';

wp_redirect($exploreUrl); exit;


}