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

$activeTab = $_POST['currentTab'] ? sanitize_text_field($_POST['currentTab']) : 'air';
$marketRedirectUrl = get_permalink(3938) . $activeTab;

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
$userLock = get_user_meta($user_ID, 'user_lock', true);

if($userLock == 1){
	update_user_meta($user_ID, 'user_lock', 0);
	$_SESSION['status'] = 'Please try again.';
	wp_redirect(get_permalink(3582));exit;
}else{
update_user_meta($user_ID, 'user_lock', 1);
include 'units_array.php';

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_discount = 1;
if($startingbonus == 'shipping'){
	$shipping_discount = 0.9;
}
$special_selling = 0;
foreach($units as $key => $order){
if(!empty($_POST["$key"])){
$owned_units = get_user_meta($user_ID, $key.'_owned');
$sold_units = ceil($_POST["$key"]);
if($_POST["$key"] < 0){$_SESSION['status'] = 'Enter a valid number';wp_redirect($marketRedirectUrl); exit;}
if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
if(!is_numeric($letter_check)){$_SESSION['status'] = 'Enter a valid number';wp_redirect($marketRedirectUrl); exit;}

if($owned_units[0] < $sold_units){
$sold_units = $owned_units[0];
}
if($key == 'spy' || $key == 'spyplane' || $key == 'sniper' || $key == 'thief'){
	$special_selling+=$_POST["$key"];
}
}}

$specials_sold = get_user_meta($user_ID, 'special_sold_today', true);

if(($specials_sold+$special_selling) > 50){
	$_SESSION['status'] = 'Cannot sell more than 50 special units per day';wp_redirect($marketRedirectUrl);exit;
}else{
	update_user_meta($user_ID,'special_sold_today',$specials_sold+$special_selling);
}


$total_selling = 0;

foreach($units as $key => $order){
if(!empty($_POST["$key"])){
$owned_units = get_user_meta($user_ID, $key.'_owned');
$sold_units = ceil($_POST["$key"]);
if($_POST["$key"] < 0){$_SESSION['status'] = 'Enter a valid number';wp_redirect($marketRedirectUrl); exit;}
if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
if(!is_numeric($letter_check)){$_SESSION['status'] = 'Enter a valid number';wp_redirect($marketRedirectUrl); exit;}

if($owned_units[0] < $sold_units){
$sold_units = $owned_units[0];
}
if($key == 'spy' || $key == 'spyplane' || $key == 'sniper'){
	$special_selling+=$_POST["$key"];
}
$price = ($order['price']*2.2)*0.65*$discount*$shipping_discount;

$soldamount = $price*$sold_units;
$total_selling+=$soldamount;
update_user_meta($user_ID, $key.'_owned',$owned_units[0]-$sold_units);

$units_sold = get_user_meta($user_ID, 'units_sold', true);
update_user_meta($user_ID, 'units_sold', $units_sold+$sold_units);

$file = 'marketselllog.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "ID: ".$user_ID."\n";
$current .= "Units sold: ".$sold_units."\nType: ".$key."\n\n";
// Write the contents back to the file
file_put_contents($file, $current);

update_user_meta($user_ID,'money',$totalmoney+$total_selling);

}}
update_user_meta($user_ID, 'user_lock', 0);
count_all_stats($user_ID);
$_SESSION['status'] = $sold_units.' units sold for a price of $ '. number_format($soldamount, 0, ',', ' ');wp_redirect($marketRedirectUrl);	//result 
exit;
}