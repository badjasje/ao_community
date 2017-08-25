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


$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
$totalmoney = get_user_meta($user_ID, 'money',true);
include 'missiles_array.php';




$totalordercost = 0;
$totalturncost = 0;
foreach($missiles as $key => $order){

$price = $order['price'];
$ordered_missiles = ceil($_POST["$key"]);

$ownedMissiles = get_user_meta( $user_ID, $key.'_owned', true);
if($ordered_missiles > 0){

if($ownedMissiles < $ordered_missiles){
	$_SESSION['status'] = 'You cannot sell more missiles than you own.';
	wp_redirect(get_permalink(3457).'/?tab=sell'); 
	exit;
}

if(empty($_POST["$key"])){
	$letter_check = 0;
		}else{
	$letter_check = $_POST["$key"];
}

if(!is_numeric($letter_check)){
	$_SESSION['status'] = 'Enter a valid number';
	wp_redirect(get_permalink(3457).'/?tab=sell'); 
	exit;
}

if($key != 'tomahawk'){

$orderamount = $price*$ordered_missiles;
$totalordercost+=$orderamount;

}
if($key == 'tomahawk'){
	
$orderamount = $price*$ordered_missiles;
$totalordercost+=$orderamount;
	
}
}
}
// BUILD MISSILES //
$total_missiles_ordered = 0;
foreach($missiles as $key => $order){

			$price = $order['price'];
			$ordered_missiles = ceil($_POST["$key"]);
			$total_missiles_ordered+=$ordered_missiles;
	
			if($ordered_missiles > 0){
			
		
				
	
		
				$missiles_on_order = get_user_meta($user_ID, $missile_name);
				$missiles_on_order = $missiles_on_order[0];

		
			update_user_meta( $user_ID, 'money',$totalmoney+($totalordercost*0.75));

		
			
			$missilesOwned = get_user_meta( $user_ID, $key.'_owned', true);
			update_user_meta( $user_ID, $key.'_owned',$missilesOwned-$ordered_missiles);
			
			
		
		
		


}}
$_SESSION['status'] = $total_missiles_ordered.' missile'.plural_func($total_missiles_ordered).' sold for $ '.number_format($totalordercost*0.75, 0, ',', ' ');
wp_redirect(get_permalink(3457).'/?tab=sell');exit;


			