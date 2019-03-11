<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
nocache_headers();
include 'building_array.php';
include 'units_array.php';
include 'missiles_array.php';

/* initialize some necessary vars */
$logPrefix = "demolish.php - ";
global $userId;
global $userData;

$totalmoney = $userData['money'][0];

/* calculate total unit amounts by type */
$totalair  = 0;
$totalsea  = 0;
$totalveh  = 0;
$totalinf  = 0;
$totalspec = 0;
foreach ($units as $key => $order) {
    $units_owned = $userData[$key.'_owned'][0];
    $units_ordered = $userData[$key.'_ordered'][0];
    $units_total = $units_owned + $units_ordered;
    $unittype = $units[$key]['type'];

    if ($unittype == 'air') {
        $totalair+=$units_total;
        if ($key == 'spyplane') {
            $totalspec += $units_total;
        }
    }
    if ($unittype == 'sea') {
        $totalsea+=$units_total;
    }
    if ($unittype == 'inf') {
        if ($key == 'thief' || $key == 'spy') {
            $totalspec += $units_total;
        }
        $totalinf+=$units_total;
    }
    if ($unittype == 'veh') {
        $totalveh+=$units_total;
    }
}

/* calculate total missiles */
$totalmissiles = 0;
foreach ($missiles as $key => $data) {
    $missiles_owned = (!empty($userData[$key.'_owned'][0]) ? $userData[$key.'_owned'][0] : 0);
    $missiles_ordered = (!empty($userData[$key.'_ordered'][0]) ? $userData[$key.'_ordered'][0] : 0);
    $totalmissiles+=($missiles_owned + $missiles_ordered);
}

/* calculate max # of housing that can be demolished - lowest is 0 */
$max_sell = array();
$max_sell['airfield']       = max($userData['airfield'][0] - ($totalair/10), 0);
$max_sell['shipyard']       = max($userData['shipyard'][0] - ($totalsea/5), 0);
$max_sell['warfactory']     = max($userData['warfactory'][0] - ($totalveh/10), 0);
$max_sell['baracks']        = max($userData['baracks'][0] - ($totalinf/20), 0);
$max_sell['command_centre'] = max($userData['command_centre'][0] - ($totalspec/5), 0);
$max_sell['silo']           = max($userData['silo'][0] - $totalmissiles, 0);

/* determine if we can demolish and calculate cost */
$total_selling = 0;
$toSell = array();
$total_buildings = 0;
foreach ($buildings as $key => $order) {
    /* retrieve total owned count */
    $owned_buildings = $userData[$key][0];

    /* default sold_buildings to 0 if empty */
    $sold_buildings = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);

    /* validate $sold_buildings is a positive integer */
    if (!is_numeric($sold_buildings) || $sold_buildings < 0) {
        $array['status'] = 'Enter a valid number';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    /* cannot sell more than you own */
    if ($sold_buildings > $owned_buildings) {
        $sold_buildings = $owned_buildings;
    }

    /* validate no demolishing filled buildings */
    if (array_key_exists($key, $max_sell) && $sold_buildings > $max_sell[$key]) {
        $array['status'] = 'You must sell units occupying the buildings before you can demolish them';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    /* all validations passed - add to array for selling */
    $toSell[$key] = $sold_buildings;

    /* calculate cost to sell */
    $total_selling+=($order['price']*0.15*$sold_buildings);
    $total_buildings+=$sold_buildings;
}
$tot_buildings_owned = count_buildings($userId);

if ($total_buildings == $tot_buildings_owned) {
    $array['status'] = 'Cannot demolish all your buildings';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}


/* validate you have enough money to sell these */
if ($totalmoney < $total_selling) {
    $array['status'] = 'Insufficient funds';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}


/* update user to remove buildings */
foreach ($toSell as $key => $count) {
    $new_count = $userData[$key][0] - $count;
    update_user_meta($userId, $key, $new_count);
}
/* now update to remove the cash */
update_user_meta($userId, 'money', $totalmoney-$total_selling);

count_all_stats($userId);

$newMax = array();
$newOwned = array();
$userData = get_user_meta($userId);
$builtland = $userData['builtland'][0];
$totalmoney = $userData['money'][0];

$totalair = 0;
$totalsea = 0;
$totalveh = 0;
$totalinf = 0;
foreach ($units as $key => $order) {
	$units_owned   = $userData[$key.'_owned'][0];
	$units_ordered = $userData[$key.'_ordered'][0];
	$unittype      = $units[$key]['type'];
	if ($unittype == 'air') {
		$totalair += $units_ordered + $units_owned;
	}

	if ($unittype == 'sea') {
		$totalsea += $units_ordered + $units_owned;
	}

	if ($unittype == 'inf') {
		$totalinf += $units_ordered + $units_owned;
	}

	if ($unittype == 'veh') {
		$totalveh += $units_ordered + $units_owned;
	}
}

foreach ($buildings as $key => $building) {

		$newOwned[$key] = $userData[$key][0];

		$newMax[$key] = floor($userData[$key][0]);
		if ($key == 'airfield') {
			$newMax[$key] = max(0,floor($userData[$key][0] - ($totalair/10)));
		}
		elseif ($key == 'shipyard') {
			$newMax[$key] = max(0,floor($userData[$key][0] - ($totalsea/5)));
		}
		elseif ($key == 'warfactory') {
			$newMax[$key] = max(0,floor($userData[$key][0] - ($totalveh/10)));
		}
		elseif ($key == 'baracks') {
			$newMax[$key] = max(0,floor($userData[$key][0] - ($totalinf/20)));
		}

}


$array['status'] = 'Buildings demolished';
$array['money'] = $totalmoney;
$array['newmax'] = $newMax;
$array['newowned'] = $newOwned;
$array['newpower'] = number_format($userData['power'][0], 0, ',', ' ');
$array['landspace'] = floor(($userData['land'][0] - $userData['builtland'][0]) / 20);
$array['networth'] = $userData['networth'][0];
$array['next'] = true;
echo json_encode($array);
exit;