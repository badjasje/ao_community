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

global $userId;
global $userData;

if (! defined('ABSPATH')) {
    exit;
}
if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$totalmoney = $userData['money'][0];
$totalturns = $userData['turns'][0];


include('units_array.php');
include 'count_functions.php';


$totalordercost = 0;
$totalunits = 0;
$total_AIR = 0;
$total_SEA = 0;
$total_INF = 0;
$total_VEH = 0;

$airspace = $userData['airfield'][0]*10;
$seaspace = $userData['shipyard'][0]*5;
$vehspace = $userData['warfactory'][0]*10;
$infspace = $userData['baracks'][0]*20;


$spies = $userData['spy_owned'][0];
$spies_ordered = $userData['spy_ordered'][0];
$thiefs = $userData['thief_owned'][0];
$thiefs_ordered = $userData['thief_ordered'][0];
$planes = $userData['spyplane_owned'][0];
$planes_ordered = $userData['spyplane_ordered'][0];
$sniper = $userData['sniper_owned'][0];
$sniper_ordered = $userData['sniper_ordered'][0];
$saboteur = $userData['saboteur_owned'][0];
$saboteur_ordered = $userData['saboteur_ordered'][0];

$commandcenter = $userData['command_centre'][0];
$ccspace = ($commandcenter*5)-$saboteur-$saboteur_ordered-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;

$total_special = $saboteur+$spies+$thiefs+$planes+$spies_ordered+$thiefs_ordered+$planes_ordered+$sniper+$sniper_ordered+$saboteur_ordered;

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
foreach ($units as $key => $order) {
    if ($order['type'] == 'air') {
        if ($_POST["$key"] < 0) {
            $array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
        $tot_air+=ceil($_POST["$key"]);
            
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
            
        if ($key == 'spyplane' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
               $array['status'] = 'Not enough command centres';
			   $array['next'] = false;
			   echo json_encode($array);
			   exit;
            }
        }
            
        $unit_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_units = ceil($_POST["$key"]);
        $air+=$ordered_units;
        $owned_units = $userData[$key.'_owned'][0];
        $units_already_on_order = $userData[$key.'_ordered'][0];
        $total_air_ordered+=$ordered_units+$owned_units+$units_already_on_order;
    }
}
        
if ($air>0) {
    if ($total_air_ordered > $airspace) {
        $array['status'] = 'Build more airfields';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
}

// CHECK VEHSPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'veh') {
        if ($_POST["$key"] < 0) {
            $array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
        $tot_veh+=ceil($_POST["$key"]);
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
        $unit_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_units = ceil($_POST["$key"]);
        $veh+=$ordered_units;
        $owned_units = $userData[$key.'_owned'][0];
        $units_already_on_order = $userData[$key.'_ordered'][0];
        $total_veh_ordered+=$ordered_units+$owned_units+$units_already_on_order;
    }
}
        
if ($veh>0) {
    if ($total_veh_ordered > $vehspace) {
       $array['status'] = 'Build more warfactories';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
}

// CHECK SEASPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'sea') {
        if ($_POST["$key"] < 0) {
            $array['status'] = 'Enter a valid number';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
        $tot_sea+=ceil($_POST["$key"]);
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
        $unit_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_units = ceil($_POST["$key"]);
        $sea+=$ordered_units;
        $owned_units = $userData[$key.'_owned'][0];
        $units_already_on_order = $userData[$key.'_ordered'][0];
        $total_sea_ordered+=$ordered_units+$owned_units+$units_already_on_order;
    }
}
        
if ($sea>0) {
    if ($total_sea_ordered > $seaspace) {
        $array['status'] = 'Build more shipyards';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
}

// CHECK INFSPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'inf') {
        if ($_POST["$key"] < 0) {
           $array['status'] = 'Enter a valid number';
		   $array['next'] = false;
		   echo json_encode($array);
		   exit;
        }
        $tot_inf+=ceil($_POST["$key"]);
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
            
        if ($key == 'spy' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $array['status'] = 'Not enough command centres';
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
            
        if ($key == 'thief' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $array['status'] = 'Not enough command centres';
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
            
        if ($key == 'sniper' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $array['status'] = 'Not enough command centres';
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
        
        if ($key == 'saboteur' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $array['status'] = 'Not enough command centres';
				$array['next'] = false;
				echo json_encode($array);
				exit;
            }
        }
            
        $unit_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_units = ceil($_POST["$key"]);
        $inf+=$ordered_units;
        $owned_units = $userData[$key.'_owned'][0];
        $units_already_on_order = $userData[$key.'_ordered'][0];
        $total_inf_ordered+=$ordered_units+$owned_units+$units_already_on_order;
    }
}
        
if ($inf>0) {
    if ($total_inf_ordered > $infspace) {
        	$array['status'] = 'Build more baracks';
			$array['next'] = false;
			echo json_encode($array);
			exit;
    }
}



if ($total_spec_count>0) {


    if ($total_special>500 || $total_spec_count > $ccspace) {
        	$array['status'] = 'Cannot build more than 500 special units';
			$array['next'] = false;
			echo json_encode($array);
			exit;
    }
}

$total_units_ordered = 0;
foreach ($units as $key => $order) {
        $price = $order['price'];
        $totalordercost+= $price*ceil($_POST["$key"]);
}




$turns_needed = ceil(($tot_air/10)+($tot_veh/10)+($tot_inf/20)+($tot_sea/5));

if ($turns_needed > $totalturns) {
    $array['status'] = 'Not enough turns';
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
        $units_built_turns = $userData['units_built_turns'][0];
    
    
        foreach ($units as $key => $order) {
            $unit_name = $key;
    
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            if ($ordered_units > 0) {
                $orderamount = $price*$ordered_units;
    
        
                $units_owned = $userData[$unit_name.'_owned'][0];
                $total_units_ordered+=$ordered_units;

        
            
            
        
            
            
                update_user_meta($userId, $key.'_owned', $units_owned+$ordered_units);
                $units_tbuilt = $userData['units_built_turns'][0];
                update_user_meta($userId, 'units_built_turns', $units_tbuilt+$ordered_units);
           
        
        
        
                $file = 'turnbuildlog.txt';
    // Open the file to get existing content
                $current = file_get_contents($file);
    // Append a new person to the file
                $time = current_time('G:i:s | d-m-Y');
                $current .= $time."\n";
                $current .= "ID: ".$userId."\n";
                $current .= "Value: ".$totalordercost."\n";
                $current .= "Units ordered: ".$unit_name." ".$ordered_units."\n\n";
    // Write the contents back to the file
                file_put_contents($file, $current);
            }
        }
    }
}

update_user_meta($userId, 'money', $totalmoney-$totalordercost);
update_user_meta($userId, 'turns', $totalturns-$turns_needed);
count_all_stats($userId);
$userData = get_user_meta($userId);
$totalMoney = $userData['money'][0];
$allOrdered = array();
$newMax = array();

// Calculate space for special units.
$spies = $userData['spy_owned'][0];
$spiesOrdered = $userData['spy_ordered'][0];
$thieves = $userData['thief_owned'][0];
$thievesOrdered = $userData['thief_ordered'][0];
$planes = $userData['spyplane_owned'][0];
$planesOrdered = $userData['spyplane_ordered'][0];
$sniper = $userData['sniper_owned'][0];
$snipersOrdered = $userData['sniper_ordered'][0];


$commandCenters = $userData['command_centre'][0];

$specialUnitsArray = [
    'spyplane',
    'sniper',
    'thief',
    'spy'
];

$space = [
    'air' => $userData['airfield'][0] * 10,
    'sea' => $userData['shipyard'][0] * 5,
    'veh' => $userData['warfactory'][0] * 10,
    'inf' => $userData['baracks'][0] * 20,
    'special' => ($commandCenters * 5) - $spies - $thieves - $planes - $spiesOrdered - $thievesOrdered - $planesOrdered - $sniper - $snipersOrdered
];

$usedSpace = [
    'air' => count_airspace($userId),
    'sea' => count_seaspace($userId),
    'veh' => count_vehspace($userId),
    'inf' => count_infspace($userId),
];

$availableSpace = [
    'air' => $space['air']-count_airspace($userId),
    'sea' => $space['sea']-count_seaspace($userId),
    'veh' => $space['veh']-count_vehspace($userId),
    'inf' => $space['inf']-count_infspace($userId),
];

$unitsPerTurn = [
    'air' => 10,
    'sea' => 5,
    'veh' => 10,
    'inf' => 20,
];

foreach ($units as $key => $unit) {
    $unitTypeKey = $unit['type'];
    $owned = $userData[$key.'_owned'][0];

    if($owned > 0) {
        $allOwned[$key] = $owned;
    }
    
    $maxMoney = floor($totalMoney / ceil($unit['price']));
    $maxSpace = $space[$unitTypeKey] - $usedSpace[$unitTypeKey];
    $maxTurns = floor($totalturns*$unitsPerTurn[$unitTypeKey]);
    
    if(in_array($key, $specialUnitsArray)) {
        $newMax[$key] = min($maxMoney, $maxSpace, $space['special'], $maxTurns);
    } else {
        $newMax[$key] = min($maxMoney, $maxSpace, $maxTurns);
    }
}


$array['status'] = $total_units_ordered.' units built, for the total price of $ '. number_format($totalordercost, 0, ',', ' ').' and '.$turns_needed.' turns';
$array['money'] = $userData['money'][0];
$array['turns'] = $userData['turns'][0];
$array['networth'] = $userData['networth'][0];
$array['allowned'] = $allOwned;
$array['newmax'] = $newMax;
$array['usedspace'] = $availableSpace;
$array['next'] = true;
echo json_encode($array);
exit;