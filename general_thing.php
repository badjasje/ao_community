<?php
	require_once("wp-load.php");
	require_once("attack_functions.php");
	$units = Units::get();
	$buildings = Buildings::get();

$attacker_type_damage = array('bld'=>0);

$attackerData = get_user_meta(234);


$dmgMulti = 0.95;
	$attackerData = get_user_meta( 234);
	$attackArray = array(
		'jsf'	=> 1000,
	);
$removeArray = array();
foreach ($attackArray as $key => $count) {

	$owned_units = $attackerData[$key.'_owned'][0];
echo $owned_units;
	if($count > $owned_units) $count = $owned_units;
	else $count = $owned_units * $count;

	/* distribute attack power equally across types */
	if($key != 'tomahawk'){
		$atk_types 		= $units[$key]['attacks'];


	
		$typecountInit 	= count($atk_types);

		/* removing attack types defender does not have */
		$atk_types 		= array_diff($atk_types, $removeArray);
		$type_count 	= count($atk_types);
		$atk_power_total = ($count * $units[$key]['attack'] * $dmgMulti);
		
		$typeMulti = 1;
		$typeDif = $type_count-$typecountInit;
		if($typeDif == -1){
			$typeMulti = 0.9;
		}
		if($typeDif == -2){
			$typeMulti = 0.8;
		}
		$atk_power_distrib = $atk_power_total*$typeMulti / max($type_count,1);

		/* damage per unit */
		$attacker_single_unit_damage = array();
		foreach($atk_types as $type) {
			$one_type = array($type);
			
			/* calculate attack totals by type */
			if (array_key_exists($type, $attacker_type_damage_FIRST)) $attacker_type_damage_FIRST[$type] += $atk_power_distrib;
			else $attacker_type_damage_FIRST[$type] = $atk_power_distrib;
		}
	}
}




$allDamage = array_sum($attacker_type_damage_FIRST);


$attacker_type_damage = array();
foreach (attackPerNW(234,$attacker_type_damage_FIRST) as $key => $dmg) {
	$attacker_type_damage[$key] = ($allDamage*$dmg);
}




/*	
	
	$units = Units::get();

	$args = array();
    $users = get_users($args);
    $allMoney = 0;
    
    $air = 0;
    $inf = 0;
    $veh = 0;
    $sea = 0;
    
foreach ($users as $user) {
	$userId = $user->ID;
	$meta = get_user_meta($userId);
	$allMoney+= $meta['money'][0];
        
        if($meta['attacks_made'][0] >= 20){
	        foreach ($units as $key => $unit) {
		        
		        if($unit['type'] == 'air' && $unit['sectype'] != 'special'){
					$air += ($unit['price'] * ($unit['networth']/100)) * $meta[$key.'_owned'][0];
		        }
		        
		        if($unit['type'] == 'inf' && $unit['sectype'] != 'special'){
					$inf += ($unit['price'] * ($unit['networth']/100)) * $meta[$key.'_owned'][0];
		        }
		        
		        if($unit['type'] == 'veh' && $unit['sectype'] != 'special'){
					$veh += ($unit['price'] * ($unit['networth']/100)) * $meta[$key.'_owned'][0];
		        }
		        
		        if($unit['type'] == 'sea' && $unit['sectype'] != 'special'){
					$sea += ($unit['price'] * ($unit['networth']/100)) * $meta[$key.'_owned'][0];
		        }
	        
	        }
		}
        
}


echo Format::money($allMoney);
$totalUnitNw = $air+$inf+$veh+$sea;

$unitNwArray = array();
$unitNwArray['air'] = round(($air/$totalUnitNw*100),1).'%';
$unitNwArray['inf'] = round(($inf/$totalUnitNw*100),1).'%';
$unitNwArray['veh'] = round(($veh/$totalUnitNw*100),1).'%';
$unitNwArray['sea'] = round(($sea/$totalUnitNw*100),1).'%';

echo '<pre>';
print_r($unitNwArray);
echo '</pre>';
  /*
require_once("wp-load.php");

$orders = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'market_order',
	'meta_key'		=> 'order_type',
	'meta_value'	=> 'units'
));

foreach ($orders as $order) {
	
	$meta = get_post_meta( $order->ID );

	$userId = $meta['user_placed_id'][0];
	$unitType = $meta['unit_type'][0];
	
	$amount = get_user_meta( $userId, $unitType.'_ordered', true );
	
	if(empty($amount)){
		echo 'FFS';
			echo '<pre>';
	print_r($unitType.' - '.$amount);
	echo '</pre>';
	echo  $meta['amount_ordered'][0];
	update_user_meta( $userId, $unitType.'_ordered', $meta['amount_ordered'][0]);
	}
	

	
}


/*
    $args = array(
        'meta_query'=>

         array(

            array(

                'relation' => 'AND',

            array(
                'key' => 'last_online',
                'value' => $timestamp-3728000,
                'compare' => ">",
                'type' => 'numeric'
            ),

            array(
                'key' => 'networth',
                'value' =>  10,
                'compare' => ">",
                'type' => 'numeric'
            ),



          )
       )
    );

    $users = get_users( $args );


foreach ($users as $user):
$userData = get_user_meta($user->ID);
$userId = $user->ID;

$unit_1 = get_user_meta( $userId, 'unit_1_ordered', true );
if(empty($unit_1)){
	
	update_user_meta( $userId, 'unit_1_ordered', 0 );
}



$unit_2 = get_user_meta( $userId, 'unit_2_ordered', true );
if(empty($unit_2)){
	
	update_user_meta( $userId, 'unit_2_ordered', 0 );
}






$unit_3 = get_user_meta( $userId, 'unit_3_ordered', true );
if(empty($unit_3)){
	
	update_user_meta( $userId, 'unit_3_ordered', 0 );
}





$unit_4 = get_user_meta( $userId, 'unit_4_ordered', true );
if(empty($unit_4)){
	
	update_user_meta( $userId, 'unit_4_ordered', 0 );
}





$unit_5 = get_user_meta( $userId, 'unit_5_ordered', true );
if(empty($unit_5)){
	
	update_user_meta( $userId, 'unit_5_ordered', 0 );
}






$unit_6 = get_user_meta( $userId, 'unit_6_ordered', true );
if(empty($unit_6)){
	
	update_user_meta( $userId, 'unit_6_ordered', 0 );
}





$unit_7 = get_user_meta( $userId, 'unit_7_ordered', true );
if(empty($unit_7)){
	
	update_user_meta( $userId, 'unit_7_ordered', 0 );
}




$unit_8 = get_user_meta( $userId, 'unit_8_ordered', true );
if(empty($unit_8)){
	
	update_user_meta( $userId, 'unit_8_ordered', 0 );
}





$unit_9 = get_user_meta( $userId, 'unit_9_ordered', true );
if(empty($unit_9)){
	
	update_user_meta( $userId, 'unit_9_ordered', 0 );
}



endforeach;


