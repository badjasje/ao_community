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

$endDate = get_field('end_date','option');
$endStamp = strtotime($endDate);
$timestamp = current_time('timestamp');
$timeLeft = $endStamp-$timestamp;
$marketClose = $timeLeft + 86400;

$array = array();
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if ($timeLeft <= 0) {
    $array['status'] = 'The market is closed';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

nocache_headers();
include 'count_functions.php';
global $userData;

$totalMoney = $userData['money'][0];

$spies = $userData['spy_owned'][0];
$spiesOrdered = $userData['spy_ordered'][0];
$thieves = $userData['thief_owned'][0];
$thievesOrdered = $userData['thief_ordered'][0];
$planes = $userData['spyplane_owned'][0];
$planesOrdered = $userData['spyplane_ordered'][0];
$snipers = $userData['sniper_owned'][0];
$snipersOrdered = $userData['sniper_ordered'][0];

$space = [
    'air' => $userData['airfield'][0] * 10,
    'sea' => $userData['shipyard'][0] * 5,
    'inf' => $userData['baracks'][0] * 20,
    'veh' => $userData['warfactory'][0] * 10,
    'special' => $userData['command_centre'][0] * 5
];

$totalSpecial = $spies + $thieves + $planes + $spiesOrdered + $thievesOrdered + $planesOrdered + $snipers + $snipersOrdered;
$specialUnitSpace = $space['special'] - $totalSpecial;
if ($specialUnitSpace < 0) {
    $specialUnitSpace = 0;
}

$specialUnitsArray = [
    'spyplane',
    'sniper',
    'thief',
    'spy'
];


// Determine market discount multiplier
$marketDiscountLevel = $userData['level_market_discount'][0];
$discount = 1.0;
if ($marketDiscountLevel == 1) {
    $discount = $discount - 0.15;
} elseif ($marketDiscountLevel >= 2) {
    $discount = $discount - 0.3;
}

$startingBonus = $userData['starting_bonus'][0];
if ($startingBonus == 'shipping') {
    $discount = $discount - 0.1;
}

// Determine shipping time
$marketShippingLevel = $userData['level_shipping_time'][0];
$hours = 12;
if ($marketShippingLevel == 1) {
    $hours = 9;
} 
if ($marketShippingLevel >= 2) {
    $hours = 6;
}

include 'units_array.php';

// Collect totals from POST request.
$totals = [];
$totalUnitsOrdered = 0;
$totalOrderAmount = 0.0;
foreach ($units as $key => $order) {
    $type = $order['type'];
    if (!isset($totals[$type])) {
        $totals[$type] = [
            'order' => 0,
            'already_ordered' => 0,
            'owned' => 0
        ];
    }
    if (!isset($totals['special'])) {
        $totals['special'] = [
            'order' => 0,
            'already_ordered' => 0,
            'owned' => 0
        ];
    }

    if (isset($_POST[$key])) {
        $orderedUnits = ceil($_POST[$key]);
        $totalUnitsOrdered += $orderedUnits;

        if (in_array($key, $specialUnitsArray)) {
            $totals['special']['order'] += $orderedUnits;
        }
        $totals[$type]['order'] += $orderedUnits;
    }

    $ordered = $userData[$key.'_ordered'][0];

    $totals[$type]['already_ordered'] += is_numeric($ordered) ? $ordered : 0;
    $totals[$type]['owned'] = $userData[$key.'_owned'][0];
    if (in_array($key, $specialUnitsArray)) {
        $totals['special']['already_ordered'] += is_numeric($ordered) ? $ordered : 0;
        $totals['special']['owned'] = $orderedUnits;
    }

    $totalOrderAmount += $order['price'] * 2.2 * $discount * $orderedUnits;
}

if ($totalOrderAmount > $totalMoney) {
    $array['status'] = 'Insufficient funds';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

foreach ($totals as $type => $total) {
    if (!($space[$type] >= ($total['order'] + $total['already_ordered'] + $total['owned']))) {
        $message = 'Not enough unit housing.';
        switch ($type) {
            case 'air':
                $message = 'Not enough Airfields.';
                break;
            case 'sea':
                $message = 'Not enough Shipyards.';
                break;
            case 'veh':
                $message = 'Not enough War Factories.';
                break;
            case 'inf':
                $message = 'Not enough Barracks.';
                break;
            case 'special':
                $message = 'Not enough Command Centers.';
                break;
        }

        $array['status'] = $message;
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    if ($type == 'special' && $total['order'] > 500) {
        $array['status'] = 'Cannot build more than 500 special unitsn';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }
}

// Order the actual units
$totalOrderCost = 0;
foreach ($units as $key => $order) {
    $delay = ($startingBonus == 'shipping') ? (int)$_POST['delay' . $key] : 0;
    $delay = $delay > 360 ? 360 : $delay;
    $delay = $delay > 0 ? $delay : 0;

    $unitName = $key . '_ordered';
    $normalName = $order['normalname'];
    $orderedUnits = ceil($_POST[$key]);
    $price = $order['price'] * 2.2 * $discount;
    $orderCost = $price * $orderedUnits;
    $totalOrderCost += $orderCost;

    if (!$orderedUnits > 0) {
        continue;
    }

    $unitsOnOrder = $userData[$unitName][0];

    $args = [
        'post_title' => $normalName,
        'post_status' => 'publish',
        'post_type' => 'market_order',
        'post_author' => $userId
    ];
    $timestamp = current_time('timestamp');

    $newOrderId = wp_insert_post($args);
    update_field('unit_type', $key, $newOrderId);
    update_field('user_placed_id', $userId, $newOrderId);
    update_field('time_placed', $timestamp, $newOrderId);
    update_field('delivery_time', $timestamp + ($hours * 3600) + ($delay * 60), $newOrderId);
    update_field('amount_ordered', $orderedUnits, $newOrderId);
    update_field('order_type', 'units', $newOrderId);
    update_field('order_value', $orderCost, $newOrderId);

    $unitsOrderedByUser = $userData['units_ordered'][0];
    update_user_meta($userId, 'units_ordered', $unitsOrderedByUser + $orderedUnits);

    // Update ordered Units:
    $ordered = $userData[$key.'_ordered'][0];
    $ordered += $orderedUnits;
    update_user_meta($userId, $key . '_ordered', $ordered);

    // Add log entry
    // Todo: Replace this with a standardized logger (E.g. Monolog)
    $file = 'marketlog.txt';
    $current = file_get_contents($file);
    $time = current_time('G:i:s | d-m-Y');
    $current .= $time."\n";
    $current .= "ID: ".$userId."\n";
    $current .= "Value: ".$totalOrderCost."\n";
    $current .= "Units ordered: ".$orderedUnits."\n\n";
    file_put_contents($file, $current);
}

update_user_meta($userId, 'money', $totalMoney - $totalOrderCost);
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
foreach ($units as $key => $unit) {
    $unitTypeKey = $unit['type'];
    $ordered = $userData[$key.'_ordered'][0];

    if($ordered > 0) {
        $allOrdered[$key] = $ordered;
    }
    
    $maxMoney = floor($totalMoney / ceil($unit['price'] * 2.2) * $discount);
    $maxSpace = $space[$unitTypeKey] - $usedSpace[$unitTypeKey];
    
    if(in_array($key, $specialUnitsArray)) {
        $newMax[$key] = min($maxMoney, $maxSpace, $space['special']);
    } else {
        $newMax[$key] = min($maxMoney, $maxSpace);
    }
}


$array['status'] = $totalUnitsOrdered. ' units ordered for a total price of $ '.number_format($totalOrderAmount, 0, ',', ' ');
$array['money'] = $totalMoney;
$array['allordered'] = $allOrdered;
$array['newmax'] = $newMax;
$array['usedspace'] = $availableSpace;
$array['next'] = true;
echo json_encode($array);
exit;
