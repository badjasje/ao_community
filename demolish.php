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
include 'building_array.php';
include 'units_array.php';
include 'missiles_array.php';

/* initialize some necessary vars */
$logPrefix = "demolish.php - ";
$user_ID = get_current_user_id();

/* pull full userdata object - better to reduce DB hits */
$user_data = get_user_meta($user_ID);
$totalmoney = $user_data['money'][0];

/* calculate total unit amounts by type */
$totalair  = 0;
$totalsea  = 0;
$totalveh  = 0;
$totalinf  = 0;
$totalspec = 0;
foreach($units as $key => $order){
	$units_owned = $user_data[$key.'_owned'][0];
	$units_ordered = $user_data[$key.'_ordered'][0];
	$units_total = $units_owned + $units_ordered;
	$unittype = $units[$key]['type'];
		
	if($unittype == 'air'){
		$totalair+=$units_total;
		if ($key == 'spyplane')
			$totalspec += $units_total;
	}	
	if($unittype == 'sea'){
		$totalsea+=$units_total;
	}
	if($unittype == 'inf'){
		if ($key == 'thief' || $key == 'spy')
			$totalspec += $units_total;
		$totalinf+=$units_total;
	}
	if($unittype == 'veh'){
		$totalveh+=$units_total;
	}
}

/* calculate total missiles */
$totalmissiles = 0;
foreach($missiles as $key => $data) {
	$missiles_owned = $user_data[$key.'_owned'][0];
	$missiles_ordered = $user_data[$key.'_ordered'][0];
	$totalmissiles+=($missiles_owned + $missiles_ordered);
}

/* calculate max # of housing that can be demolished - lowest is 0 */
$max_sell = array();
$max_sell['airfield']		= max($user_data['airfield'][0] - ($totalair/10), 0);
$max_sell['shipyard']		= max($user_data['shipyard'][0] - ($totalsea/5), 0);
$max_sell['warfactory']		= max($user_data['warfactory'][0] - ($totalveh/10), 0);
$max_sell['baracks']		= max($user_data['baracks'][0] - ($totalinf/20), 0);
$max_sell['command_centre'] = max($user_data['command_centre'][0] - ($totalspec/5), 0);
$max_sell['silo'] 			= max($user_data['silo'][0] - $totalmissiles, 0);

/* determine if we can demolish and calculate cost */
$total_selling = 0;
$toSell = array();
$total_buildings = 0;
foreach($buildings as $key => $order){
	/* retrieve total owned count */
	$owned_buildings = $user_data[$key][0];

	/* default sold_buildings to 0 if empty */
	$sold_buildings = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);

	/* validate $sold_buildings is a positive integer */
	if(!is_numeric($sold_buildings) || $sold_buildings < 0) {
		$_SESSION['status'] = '12';
		wp_redirect(get_permalink(3386)); 
		exit;
	}
	/* cannot sell more than you own */
	if($sold_buildings > $owned_buildings){
		$sold_buildings = $owned_buildings;
	}
						
	/* validate no demolishing filled buildings */
	if (array_key_exists($key, $max_sell) && $sold_buildings > $max_sell[$key]) {
		$_SESSION['status'] = '17';
		wp_redirect(get_permalink(3386)); 
		exit;
	}

	/* all validations passed - add to array for selling */
	$toSell[$key] = $sold_buildings;
	
	/* calculate cost to sell */
	$total_selling+=($order['price']*0.15*$sold_buildings);
	$total_buildings+=$sold_buildings;
}
$tot_buildings_owned = count_buildings($user_ID);

if($total_buildings == $tot_buildings_owned){
	$_SESSION['status'] = '1322';
	wp_redirect(get_permalink(3386)); 
	exit;
}


/* validate you have enough money to sell these */
if($totalmoney < $total_selling){ 
	$_SESSION['status'] = '2';
	wp_redirect(get_permalink(3386)); 
	exit;
}


/* update user to remove buildings */
foreach($toSell as $key => $count) {
	$new_count = $user_data[$key][0] - $count;
	update_user_meta($user_ID, $key, $new_count);
}
/* now update to remove the cash */
update_user_meta($user_ID,'money',$totalmoney-$total_selling);
$_SESSION['status'] = '14';
wp_redirect(get_permalink(3386).'/#demolish'); 
exit;