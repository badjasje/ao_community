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

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}


$totalmoney = get_user_meta($user_ID, 'money');
$totalmoney = $totalmoney[0];
$totalturns = get_user_meta($user_ID, 'turns');
$totalturns = $totalturns[0];
$land = get_user_meta($user_ID, 'land');
$builtland = get_user_meta($user_ID, 'builtland');
$EElevel = get_user_meta($user_ID, 'level_engineering_effectiveness')[0];
$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$extra_divide = 0;
if($startingbonus == 'defensive'){
	$extra_divide = 5;
}


if($EElevel == 0 || empty($EElevel)){
						$turns_divider = 5+$extra_divide;	
						}
					
					if($EElevel == 1){
						$turns_divider = 10+$extra_divide;	
						}
					if($EElevel == 2){
						$turns_divider = 15+$extra_divide;	
						}
					


include 'building_array.php';



$totalordercost = 0;
$totalbuildings = 0;
foreach($buildings as $key => $order){
if($_POST["$key"] < 0){$_SESSION['status'] = 'Enter a valid number';wp_redirect(get_permalink(3386)); exit;}
$price = $order['price'];
$ordered_buildings = ceil($_POST["$key"]);
if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}

if(!is_numeric($letter_check)){$_SESSION['status'] = 'Enter a valid number';wp_redirect(get_permalink(3386)); exit;}

if($ordered_buildings < 0){$_SESSION['status'] = 'You cannot enter negative amounts';wp_redirect(get_permalink(3386)); exit;}
$orderamount = $price*$ordered_buildings;
$totalbuildings+=$ordered_buildings;
$totalordercost = $totalordercost+$orderamount;


}
$turns_needed = ceil($totalbuildings/$turns_divider);
$land_needed = ceil($totalbuildings*20);


if(($land[0]-$builtland[0]) < $land_needed){
	$_SESSION['status'] = 'Not enough free land';wp_redirect(get_permalink(3386)); exit;
}

if($turns_needed > $totalturns){
	$_SESSION['status'] = 'Not enough turns';wp_redirect(get_permalink(3386)); exit;
}else{
if($totalordercost > $totalmoney){
	$_SESSION['status'] = 'Insufficient funds';wp_redirect(get_permalink(3386)); exit;
	}else{
		
	$buildings_built = get_user_meta($user_ID, 'buildings_built', true);
	update_user_meta($user_ID, 'buildings_built', $buildings_built+$totalbuildings);

	foreach($buildings as $key => $order){
		$unit_name = $key;
	
		$normalname = $order['normalname'];
		$price = $order['price'];
		$ordered_buildings = ceil($_POST["$key"]);
		if($ordered_buildings > 0){
		
		$orderamount = $price*$ordered_buildings;
	
		
		$units_on_order = get_user_meta($user_ID, $unit_name);
		$units_on_order = $units_on_order[0];

		
			update_user_meta( $user_ID, 'money',$totalmoney-$totalordercost);
			update_user_meta( $user_ID, 'turns',$totalturns-$turns_needed);
			update_user_meta( $user_ID, $key,$ordered_buildings);
		
			
			
			update_user_meta( $user_ID, $unit_name,$units_on_order+$ordered_buildings);
			
			
		
		
		
		


}}}}

$_SESSION['status'] = $totalbuildings.' buildings built using ' .$turns_needed.' turns';
count_all_stats($user_ID);
wp_redirect(get_permalink(3386).'/?tab=build'); exit;


}
			