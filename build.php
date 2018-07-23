<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();

if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
	$array = array();
    $user_ID = get_current_user_id();

    if (! defined('ABSPATH')) {
        exit;
    }
    if (empty($user_ID)) {
        $array['status'] = 'You must log in to perform this action';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    if (!is_user_logged_in()) {
        $array['status'] = 'You must log in to perform this action';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }


    $totalmoney = get_user_meta($user_ID, 'money',true);

    $totalturns = get_user_meta($user_ID, 'turns',true);

    $land = get_user_meta($user_ID, 'land',true);
    $builtland = get_user_meta($user_ID, 'builtland',true);
    $EElevel = get_user_meta($user_ID, 'level_engineering_effectiveness',true);
    $startingbonus = get_user_meta($user_ID, 'starting_bonus', true);
    $extra_divide = 0;
    if ($startingbonus == 'defensive') {
        $extra_divide = 5;
    }


    if ($EElevel == 0 || empty($EElevel)) {
		$turns_divider = 5+$extra_divide;
    }       
    if ($EElevel == 1) {
        $turns_divider = 10+$extra_divide;
    }
    if ($EElevel >= 2) {
        $turns_divider = 15+$extra_divide;
    }
                    


    include 'building_array.php';
	
	
    $totalordercost = 0;
    $totalbuildings = 0;
    foreach ($buildings as $key => $order) {
        if ($_POST["$key"] < 0) {
            $array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
        $price = $order['price'];
        $ordered_buildings = ceil($_POST["$key"]);
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }

        if (!is_numeric($letter_check)) {
			$array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }

        if ($ordered_buildings < 0) {
            $array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
        $orderamount = $price*$ordered_buildings;
        $totalbuildings+=$ordered_buildings;
        $totalordercost = $totalordercost+$orderamount;
    }
    $turns_needed = ceil($totalbuildings/$turns_divider);
    $land_needed = ceil($totalbuildings*20);


    if (($land-$builtland) < $land_needed) {
        $array['status'] = 'Not enough free land';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    if ($turns_needed > $totalturns) {
        $array['status'] = 'Not enough free turns';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    } else {
        if ($totalordercost > $totalmoney) {
            $array['status'] = 'Insufficient funds';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        } else {
            $buildings_built = get_user_meta($user_ID, 'buildings_built', true);
            update_user_meta($user_ID, 'buildings_built', $buildings_built+$totalbuildings);

            foreach ($buildings as $key => $order) {
                $unit_name = $key;
    
                $normalname = $order['normalname'];
                $price = $order['price'];
                $ordered_buildings = ceil($_POST["$key"]);
                if ($ordered_buildings > 0) {
                    $orderamount = $price*$ordered_buildings;
    
        
                    $units_on_order = get_user_meta($user_ID, $unit_name,true);

                    update_user_meta($user_ID, 'money', $totalmoney-$totalordercost);
                    update_user_meta($user_ID, 'turns', $totalturns-$turns_needed);
                    update_user_meta($user_ID, $key, $ordered_buildings);
        
            
            
                    update_user_meta($user_ID, $unit_name, $units_on_order+$ordered_buildings);
                }
            }
        }
    }

count_all_stats($user_ID); 

$newMax = array();
$userData = get_user_meta($user_ID);
$builtland = $userData['builtland'][0];
$totalmoney = $userData['money'][0];
$totalturns = $userData['turns'][0];

foreach ($buildings as $key => $building) {
	
	$maxMoney = floor($totalmoney / $building['price']);
	$maxTurns = floor($totalturns * $turns_divider);
	$maxSpace = floor(($land - $builtland) / 20);
	
	$newMax[$key] = min($maxMoney,$maxTurns,$maxSpace);
}

      
    $array['status'] = $totalbuildings.' buildings built using ' .$turns_needed.' turns';
	$array['money'] = $totalmoney;
	$array['allordered'] = $totalbuildings;
	$array['turns'] = $totalturns;
	$array['newmax'] = $newMax;
	$array['networth'] = $userData['networth'][0];
	$array['newpower'] = number_format($userData['power'][0], 0, ',', ' ');
	$array['next'] = true;
	echo json_encode($array);
	exit;