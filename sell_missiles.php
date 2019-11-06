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

$userId = get_current_user_id();
$array = array();
if (empty($userId) || !is_user_logged_in()) {
    $array['status'] = 'You must log in to perform this action';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

if (strlen(implode("",$_POST)) <= 0) {
    $array['status'] = 'Select 1 or more missiles to sell';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

$totalmoney = get_user_meta($userId, 'money', true);
$missiles = Missiles::get();

$totalordercost = 0;
$total_missiles_ordered = 0;
foreach ($missiles as $key => $order) {
    $price = $order['price'];

    $ordered_missiles = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);
    if (!is_numeric($ordered_missiles)) {
        $array['status'] = 'Enter a valid number';
        $array['next'] = false;
        echo json_encode($array);
        exit;
    }

    $ownedMissiles = get_user_meta($userId, $key.'_owned', true);
    if ($ordered_missiles > 0) {
        if ($ownedMissiles < $ordered_missiles) {
             $array['status'] = 'You cannot sell more missiles than you own';
			 $array['next'] = false;
			 echo json_encode($array);
			 exit;
        }
        $orderamount = $price*$ordered_missiles;
        $totalordercost+=$orderamount;
        $total_missiles_ordered+=$ordered_missiles;
    }
}

$newMax = array();
foreach ($missiles as $key => $order) {
    $price = $order['price'];
    $ordered_missiles = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);
    if ($ordered_missiles > 0) {
        update_user_meta($userId, 'money', $totalmoney + ($totalordercost * Settings::get('missile_sell_multi') ));
        $missilesOwned = get_user_meta($userId, $key.'_owned', true);
        update_user_meta($userId, $key.'_owned', $missilesOwned-$ordered_missiles);
    }
    $ownedMissiles = get_user_meta($userId, $key.'_owned',true);
	$newMax[$key] = $ownedMissiles;
}
count_all_stats($userId);

$array['status'] = $total_missiles_ordered.' missile'.plural_func($total_missiles_ordered).' sold for $ '.number_format($totalordercost*0.75, 0, ',', ' ');
$array['next'] = true;
$array['newmaxsell'] = $newMax;
echo json_encode($array);
exit;
