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
$units = Units::get();

$specialSelling = 0;
foreach ($units as $key => $order) {
    if (!empty($_POST["$key"])) {
        $ownedUnits = $userData[$key.'_owned'][0];

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

        if (in_array($key, $specialUnitsArray)) {
            $specialSelling += ceil($_POST[$key]);
        }
    }
}

// You cannot sell subs when having tommy's
$missiles_owned = (!empty($userData['tomahawk_owned']) ? $userData['tomahawk_owned'][0] : 0);
$missiles_ordered = (!empty($userData['tomahawk_ordered']) ? $userData['tomahawk_ordered'][0] : 0);
$totalmissiles = ($missiles_owned+$missiles_ordered);
$submarine_owned = (!empty($userData['submarine_owned']) ? $userData['submarine_owned'][0] : 0);
$sellsubs = (!empty($_POST['submarine']) ? intval($_POST['submarine']) : 0);
$totalsubs = $submarine_owned - $sellsubs;
if($totalmissiles > 0 && $sellsubs > 0 && ceil($totalmissiles/2) > $totalsubs ) {
    $array['status'] = 'You must sell the tomahawks occupying the submarines before you can sell them';
    $array['next'] = false;
    update_user_meta($userId, 'user_lock', 0);
    echo json_encode($array);
    exit;
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

$newMax = array();
$specialSold = $userData['special_sold_today'][0];

foreach ($units as $key => $unit) {
    $unitTypeKey = $unit['type'];
    $owned = $userData[$key.'_owned'][0];
    $newMax[$key] = $owned;
    if(is_array($specialUnitsArray) && in_array($key, $specialUnitsArray)) {
        $newMax[$key] = min($newMax[$key], (50-$specialSold));
    }
}

$array['status'] = $soldUnits.' units sold for a price of $ '. number_format($totalSelling, 0, ',', ' ');
$array['money'] = $userData['money'][0];
$array['turns'] = $userData['turns'][0];
$array['networth'] = $userData['networth'][0];
$array['newmax'] = $newMax;
$array['next'] = true;
echo json_encode($array);
update_user_meta($userId, 'user_lock', 0);
exit;
