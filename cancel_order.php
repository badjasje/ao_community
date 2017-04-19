<?php 

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}
	
	require( dirname(__FILE__) . '/wp-load.php' );
if(get_field('game_status','option') == 'Live'){
	include 'units_array.php';
	
	$order_ID = $_POST['order'];
	
	$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
	$placed_ID = get_post_meta($order_ID,'user_placed_id',true);

if($user_ID != $placed_ID){
	wp_redirect(get_permalink(3204)); exit;
}
	

$unit_type = get_post_meta($order_ID,'unit_type',true);
	
$discount_level = get_user_meta($user_ID, 'level_market_discount',true);

$discount = 1;
if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level == 2){
	$discount = 0.70;
}

	
		
		$units_in_this_order = get_post_meta($order_ID,'amount_ordered',true);
		
			
		$ownedunits = get_user_meta($user_ID, $unit_type.'_owned',true);
		
		
		$total_units_on_order = get_user_meta($user_ID, $unit_type.'_ordered',true);

	
		
			$unitprice = $units[$unit_type]['price']*2.2*$discount;
			$cashback = $unitprice*$units_in_this_order*0.75;
			

			$owned_meta_key = $unit_type.'_owned';
			
			update_user_meta( $user_ID,$unit_type.'_ordered',$total_units_on_order - $units_in_this_order);
			
			
			$totalmoney = get_user_meta($user_ID, 'money',true);
			update_user_meta( $user_ID,'money',$totalmoney+$cashback);
			
			wp_trash_post($order_ID);
			$_SESSION['status'] = 'Order canceled. You received $ '.number_format($cashback, 0, ',', ' ');
			wp_redirect(get_permalink(3204)); exit;
}