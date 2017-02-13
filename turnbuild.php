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
$totalturns = get_user_meta($user_ID, 'turns',true);


include('units_array.php');



$totalordercost = 0;
$totalunits = 0;
$total_AIR = 0;
$total_SEA = 0;
$total_INF = 0;
$total_VEH = 0;

$airspace = get_user_meta($user_ID, 'airfield');
$airspace = $airspace[0]*10;
$seaspace = get_user_meta($user_ID, 'shipyard');
$seaspace = $seaspace[0]*5;
$vehspace = get_user_meta($user_ID, 'warfactory');
$vehspace = $vehspace[0]*10;
$infspace = get_user_meta($user_ID, 'baracks');
$infspace = $infspace[0]*20;


$spies = get_user_meta($user_ID, 'spy_owned',true);
$spies_ordered = get_user_meta($user_ID, 'spy_ordered',true);
$thiefs = get_user_meta($user_ID, 'thief_owned',true);
$thiefs_ordered = get_user_meta($user_ID, 'thief_ordered',true);
$planes = get_user_meta($user_ID, 'spyplane_owned',true);
$planes_ordered = get_user_meta($user_ID, 'spyplane_ordered',true);
$sniper = get_user_meta($user_ID, 'sniper_owned',true);
$sniper_ordered = get_user_meta($user_ID, 'sniper_ordered',true);


$commandcenter = get_user_meta($user_ID, 'command_centre',true);
$ccspace = ($commandcenter*5)-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;
echo $ccspace.'<br/>';
$total_special = $spies+$thiefs+$planes+$spies_ordered+$thiefs_ordered+$planes_ordered+$sniper+$sniper_ordered;
echo $total_special.'<br/>';
$air = 0;
$veh = 0;
$sea = 0;
$inf = 0;
$total_air_ordered = 0;
$total_sea_ordered = 0;
$total_inf_ordered = 0;
$total_veh_ordered = 0;
$tot_inf = 0;
$tot_sea = 0;
$tot_air = 0;
$tot_veh = 0;


// CHECK AIRSPACE //
$total_spec_count = 0;
foreach($units as $key => $order){
		
		if($order['type'] == 'air'){
			if($_POST["$key"] < 0){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$tot_air+=ceil($_POST["$key"]);
			
			if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
			if(!is_numeric($letter_check)){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			
			if($key == 'spyplane' && $_POST["$key"] > 0){
				$total_special+=$_POST["$key"];
				$total_spec_count+=$_POST["$key"];
				if(ceil($_POST["$key"]) > $ccspace){
					$_SESSION['status'] = '15';wp_redirect(get_permalink(3415)); exit;
				}
			}
			
			$unit_name = $key.'_ordered';
			$normalname = $order['normalname'];
			$price = $order['price'];
			$ordered_units = ceil($_POST["$key"]);
			$air+=$ordered_units;
			$owned_units = get_user_meta($user_ID, $key.'_owned');
			$units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
			$total_air_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];}}
		
			if($air>0){
			if($total_air_ordered > $airspace ){ $_SESSION['status'] = '1';wp_redirect(get_permalink(3415)); exit;}}

// CHECK VEHSPACE //

foreach($units as $key => $order){
		
		if($order['type'] == 'veh'){
			if($_POST["$key"] < 0){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$tot_veh+=ceil($_POST["$key"]);
			if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
			if(!is_numeric($letter_check)){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$unit_name = $key.'_ordered';
			$normalname = $order['normalname'];
			$price = $order['price'];
			$ordered_units = ceil($_POST["$key"]);
			$veh+=$ordered_units;
			$owned_units = get_user_meta($user_ID, $key.'_owned');
			$units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
			$total_veh_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];}}
		
			if($veh>0){
			if($total_veh_ordered > $vehspace ){ $_SESSION['status'] = '2';wp_redirect(get_permalink(3415)); exit;}}

// CHECK SEASPACE //

foreach($units as $key => $order){
		
		if($order['type'] == 'sea'){
			if($_POST["$key"] < 0){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$tot_sea+=ceil($_POST["$key"]);
			if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
			if(!is_numeric($letter_check)){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$unit_name = $key.'_ordered';
			$normalname = $order['normalname'];
			$price = $order['price'];
			$ordered_units = ceil($_POST["$key"]);
			$sea+=$ordered_units;
			$owned_units = get_user_meta($user_ID, $key.'_owned');
			$units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
			$total_sea_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];}}
		
			if($sea>0){
			if($total_sea_ordered > $seaspace ){ $_SESSION['status'] = '3';wp_redirect(get_permalink(3415)); exit;}}

// CHECK INFSPACE //

foreach($units as $key => $order){
		
		if($order['type'] == 'inf'){
			if($_POST["$key"] < 0){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			$tot_inf+=ceil($_POST["$key"]);
			if(empty($_POST["$key"])){$letter_check = 0;}else{$letter_check = $_POST["$key"];}
			if(!is_numeric($letter_check)){$_SESSION['status'] = '12';wp_redirect(get_permalink(3415)); exit;}
			
			if($key == 'spy' && $_POST["$key"] > 0){
				$total_special+=$_POST["$key"];
				$total_spec_count+=$_POST["$key"];
				if(ceil($_POST["$key"]) > $ccspace){
					$_SESSION['status'] = '15';wp_redirect(get_permalink(3415)); exit;
				}
			}
			
			if($key == 'thief' && $_POST["$key"] > 0){
				$total_special+=$_POST["$key"];
				$total_spec_count+=$_POST["$key"];
				if(ceil($_POST["$key"]) > $ccspace){
					$_SESSION['status'] = '15';wp_redirect(get_permalink(3415)); exit;
				}
			}
			
			if($key == 'sniper' && $_POST["$key"] > 0){
				$total_special+=$_POST["$key"];
				$total_spec_count+=$_POST["$key"];
				if(ceil($_POST["$key"]) > $ccspace){
					$_SESSION['status'] = '15';wp_redirect(get_permalink(3415)); exit;
				}
			}
			
			$unit_name = $key.'_ordered';
			$normalname = $order['normalname'];
			$price = $order['price'];
			$ordered_units = ceil($_POST["$key"]);
			$inf+=$ordered_units;
			$owned_units = get_user_meta($user_ID, $key.'_owned');
			$units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
			$total_inf_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];}}
		
			if($inf>0){
			if($total_inf_ordered > $infspace ){ $_SESSION['status'] = '4';wp_redirect(get_permalink(3415)); exit;}}	

echo $total_spec_count.'<br/>';
echo $total_special.'<br/>';
echo $ccspace.'<br/>';

if($total_spec_count>0){
if($total_special>500 || ($total_special/$commandcenter) > $ccspace){
	$_SESSION['status'] = '192';wp_redirect(get_permalink(3415)); exit;
	}
}

$total_units_ordered = 0;
foreach($units as $key => $order){
		$price = $order['price'];
		$totalordercost+= $price*ceil($_POST["$key"]);}




$turns_needed = ceil(($tot_air/10)+($tot_veh/10)+($tot_inf/20)+($tot_sea/5));

if($turns_needed > $totalturns){
	$_SESSION['status'] = '92';wp_redirect(get_permalink(3415)); exit;
}else{
if($totalordercost > $totalmoney){
	$_SESSION['status'] = '5';wp_redirect(get_permalink(3415)); exit;
	}else{
	
	$units_built_turns = get_user_meta($user_ID, 'units_built_turns', true);
	
	
	foreach($units as $key => $order){
		$unit_name = $key;
	
		$normalname = $order['normalname'];
		$price = $order['price'];
		$ordered_units = ceil($_POST["$key"]);
		if($ordered_units > 0){
		
		$orderamount = $price*$ordered_units;
	
		
		$units_owned = get_user_meta($user_ID, $unit_name.'_owned');
		$total_units_ordered+=$ordered_units;

		
			
			
		
			
			
			update_user_meta( $user_ID, $unit_name.'_owned',$units_owned[0]+$ordered_units);
			$units_tbuilt = get_user_meta($user_ID, 'units_built_turns', true);
			update_user_meta($user_ID, 'units_built_turns', $units_tbuilt+$ordered_units);
			
			$success = '?success=1';
		
		
		


}}}}
count_all_stats($user_ID);
update_user_meta( $user_ID, 'money',$totalmoney-$totalordercost);
update_user_meta( $user_ID, 'turns',$totalturns-$turns_needed);

$_SESSION['units_ordered'] = $total_units_ordered;
$_SESSION['order_price'] = $totalordercost;
$_SESSION['turns_used'] = $turns_needed;

$_SESSION['status'] = '6'; wp_redirect(get_permalink(3415));
  
  exit;



			