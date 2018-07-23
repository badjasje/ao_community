<?php
/**
 * Handles market sales
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

if (! defined('ABSPATH')) { exit; }
$userId = get_current_user_id();

if (empty($userId) || !is_user_logged_in()) {
   $array['status'] = 'You must log in to perform this action';
   $array['next'] = false;
   echo json_encode($array);
   exit;
}

$userData = get_user_meta($userId);
include 'count_functions.php';
$totalMoney = $userData['money'][0];
$userLock = $userData['user_lock'][0];
$marketSellMultiplier = (2.2 * 0.5);

$specialUnitsArray = [
    'spyplane',
    'sniper',
    'thief',
    'saboteur',
    'spy'
];

if ($userLock == 1) {
	$array['status'] = 'Please try again';
	$array['next'] = false;
	update_user_meta($userId, 'user_lock', 0);
	echo json_encode($array);
	exit;
}

update_user_meta($userId, 'user_lock', 1);
include 'units_array.php';

    $specialSelling = 0;
    foreach ($units as $key => $order) {
        if (!empty($_POST["$key"])) {
            $ownedUnits = $userData[$key.'_owned'][0];
            $soldUnits = ceil($_POST["$key"]);

            if ($_POST[$key] < 0) {
                $array['status'] = 'Enter a valid number';
				$array['next'] = false;
				update_user_meta($userId, 'user_lock', 0);
				echo json_encode($array);
				exit;
            }

            $letterCheck = isset($_POST[$key]) ? $_POST[$key] : 0;
            if (!is_numeric($letterCheck)) {
                $array['status'] = 'Enter a valid number';
					$array['next'] = false;
					update_user_meta($userId, 'user_lock', 0);
					echo json_encode($array);
					exit;
            }

            if ($ownedUnits < $soldUnits) {
                $soldUnits = $ownedUnits;
            }
            if (in_array($key, $specialUnitsArray)) {
                $specialSelling += $_POST[$key];
            }
        }
    }

    $specials_sold = $userData['special_sold_today'][0];

    if (($specials_sold+$specialSelling) > 50) {
        $array['status'] = 'Cannot sell more than 50 special units per day';
		$array['next'] = false;
		update_user_meta($userId, 'user_lock', 0);
		echo json_encode($array);
		exit;
    } else {
        update_user_meta($userId, 'special_sold_today', $specials_sold+$specialSelling);
    }


    $totalSelling = 0;

    foreach ($units as $key => $order) {
        if (!empty($_POST["$key"])) {
            $ownedUnits = $userData[$key.'_owned'][0];
            $soldUnits = ceil($_POST["$key"]);
            if ($_POST["$key"] < 0) {
                $array['status'] = 'Enter a valid number';
				$array['next'] = false;
				update_user_meta($userId, 'user_lock', 0);
				echo json_encode($array);
				exit;
            }
            if (empty($_POST["$key"])) {
                $letterCheck = 0;
            } else {
                $letterCheck = $_POST["$key"];
            }
            if (!is_numeric($letterCheck)) {
                $array['status'] = 'Enter a valid number';
				$array['next'] = false;
				update_user_meta($userId, 'user_lock', 0);
				echo json_encode($array);
				exit;
            }

            if ($ownedUnits < $soldUnits) {
                $soldUnits = $ownedUnits;
            }
            if ($key == 'spy' || $key == 'spyplane' || $key == 'sniper') {
                $specialSelling+=$_POST["$key"];
            }
            $price = $order['price'] * $marketSellMultiplier;

            $soldAmount = $price * $soldUnits;
            $totalSelling += $soldAmount;
            update_user_meta($userId, $key.'_owned', $ownedUnits - $soldUnits);

            $unitsSold = $userData['units_sold'][0];
            update_user_meta($userId, 'units_sold', $unitsSold+$soldUnits);

            // Add log entry
            // Todo: Replace this with a standardized logger (E.g. Monolog)
           /* $file = 'marketselllog.txt';
            $current = file_get_contents($file);
            $current .= "ID: ".$userId."\n";
            $current .= "Units sold: ".$soldUnits."\nType: ".$key."\n\n";
            file_put_contents($file, $current);
			*/
            update_user_meta($userId, 'money', $totalMoney+$totalSelling);
        }
    }
    update_user_meta($userId, 'user_lock', 0);

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

    
    
    
    $array['status'] = $soldUnits.' units sold for a price of $ '. number_format($totalSelling, 0, ',', ' ');
	$array['money'] = $userData['money'][0];
	$array['turns'] = $userData['turns'][0];
	$array['networth'] = $userData['networth'][0];
	$array['allowned'] = $allOwned;
	$array['newmax'] = $newMax;
	$array['usedspace'] = $availableSpace;
	$array['next'] = true;
	echo json_encode($array);
	update_user_meta($userId, 'user_lock', 0);
	exit;