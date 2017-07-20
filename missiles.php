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

$turns = get_user_meta($user_ID, 'turns',true);


$missilespace = get_user_meta($user_ID, 'silo',true);
$tomahawkspace = get_user_meta($user_ID, 'submarine_owned',true)*2;


include 'missiles_array.php';




$totalordercost = 0;
$totalturncost = 0;
foreach($missiles as $key => $order){

$price = $order['price'];
$ordered_missiles = ceil($_POST["$key"]);

if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
if(!is_numeric($letter_check)){$_SESSION['status'] = 'Enter a valid number';wp_redirect(get_permalink(3457)); exit;}

if($key != 'tomahawk'){

$orderamount = $price*$ordered_missiles;
$totalordercost+=$orderamount;
$totalturncost+=$ordered_missiles*5;

}
if($key == 'tomahawk'){
	
$orderamount = $price*$ordered_missiles;
$totalordercost+=$orderamount;
$totalturncost+=ceil($ordered_missiles/3);
	
}



}
if($totalordercost > $totalmoney){$_SESSION['status'] = 'Insufficient funds'; wp_redirect(get_permalink(3457));exit;}
if($turns < $totalturncost){$_SESSION['status'] = 'Not enough turns'; wp_redirect(get_permalink(3457));exit;}



$mis = 0;
$total_missile_ordered = 0;


$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_speed = 1;
if($startingbonus == 'shipping'){
	$shipping_speed = 0.5;
}


// CHECK MISSILESPACE //

foreach($missiles as $key => $order){
		
			if($key != 'tomahawk'){
				
				$missile_name = $key.'_ordered';
				$normalname = $order['normalname'];
				$price = $order['price'];
				$ordered_missiles = ceil($_POST["$key"]);
				$mis+=$ordered_missiles;
				
				$owned_missiles = get_user_meta($user_ID, $key.'_owned',true);
				$missiles_already_on_order = get_user_meta($user_ID, $key.'_ordered',true);
				
				$total_missile_ordered+=$ordered_missiles+$owned_missiles+$missiles_already_on_order;}
				
				if($mis>0){
					if($total_missile_ordered > $missilespace ){ 
						$_SESSION['status'] = 'Build more missile silos';
						wp_redirect(get_permalink(3457)); 
						exit;
						}
					}
			
			
			if($key == 'tomahawk'){
				
				$owned_tomahawks = get_user_meta($user_ID, $key.'_owned',true);
				$tomahawks_already_on_order = get_user_meta($user_ID, $key.'_ordered',true);
				
				$ordered_tomahawks = ceil($_POST["$key"]);
				
				$total_tomahawks_ordered = $ordered_tomahawks + $tomahawks_already_on_order + $owned_tomahawks;
				
				if($ordered_tomahawks > 0){
					if($total_tomahawks_ordered > $tomahawkspace ){ 
						$_SESSION['status'] = 'Build more submarines';
						wp_redirect(get_permalink(3457)); 
						exit;
						}
					}
					
					
				}
			}

// BUILD MISSILES //
$total_missiles_ordered = 0;
foreach($missiles as $key => $order){
		
		
			$missile_name = $key.'_ordered';
	
			$normalname = $order['normalname'];
			$price = $order['price'];
			$ordered_missiles = ceil($_POST["$key"]);
			$total_missiles_ordered+=$ordered_missiles;
	
			if($ordered_missiles > 0){
			
		
				
	
		
				$missiles_on_order = get_user_meta($user_ID, $missile_name);
				$missiles_on_order = $missiles_on_order[0];

		
			update_user_meta( $user_ID, 'money',$totalmoney-$totalordercost);
			update_user_meta( $user_ID, 'turns',$turns-$totalturncost);
		
			
			
			update_user_meta( $user_ID, $missile_name,$missiles_on_order+$ordered_missiles);
			
			$args = array(
				'post_title'    => $order['normalname'],
				'post_status'   => 'publish',
				'post_type'		=> 'market_order',
				'post_author'   => $user_ID
				);
				$timestamp = current_time('timestamp');
			
			$new_order_id = wp_insert_post( $args );
			update_field('unit_type', $key, $new_order_id);
			update_field('user_placed_id', $user_ID, $new_order_id);
			update_field('time_placed',$timestamp, $new_order_id);
			update_field('delivery_time', $timestamp+(6 * 3600*$shipping_speed), $new_order_id);
			update_field('amount_ordered', $ordered_missiles, $new_order_id);
			update_field('order_type', 'missile', $new_order_id);
		
		
		


}}
$_SESSION['status'] = $total_missiles_ordered.' missiles ordered for '.$totalturncost.' turns and $ '.number_format($totalordercost, 0, ',', ' ');
wp_redirect(get_permalink(3457));exit;


			