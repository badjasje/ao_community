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
include 'missiles_array.php';




$totalordercost = 0;
$totalturncost = 0;
foreach ($missiles as $key => $order) {
    $price = $order['price'];

    $ordered_missiles = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);

    $ownedMissiles = get_user_meta($userId, $key.'_owned', true);
    if ($ordered_missiles > 0) {
        if ($ownedMissiles < $ordered_missiles) {
             $array['status'] = 'You cannot sell more missiles than you own';
			 $array['next'] = false;
			 echo json_encode($array);
			 exit;
        }

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

        if ($key != 'tomahawk') {
            $orderamount = $price*$ordered_missiles;
            $totalordercost+=$orderamount;
        }
        if ($key == 'tomahawk') {
            $orderamount = $price*$ordered_missiles;
            $totalordercost+=$orderamount;
        }
    }
}
$newMax = array();

$total_missiles_ordered = 0;
foreach ($missiles as $key => $order) {
	
	
	
    $price = $order['price'];
    $ordered_missiles = (empty($_POST["$key"])) ? 0 : ceil($_POST["$key"]);
   
    $total_missiles_ordered+=$ordered_missiles;
    
    if ($ordered_missiles > 0) {
        $missiles_on_order = get_user_meta($userId, $key.'_ordered');
        update_user_meta($userId, 'money', $totalmoney+($totalordercost*0.75));   
        $missilesOwned = get_user_meta($userId, $key.'_owned', true);
        update_user_meta($userId, $key.'_owned', $missilesOwned-$ordered_missiles);
    }
    $ownedMissiles = get_user_meta($userId, $key.'_owned',true);
	if($ordered_missiles > 0){
		$newMax[$key] = $ownedMissiles;
	}
}

$array['status'] = $total_missiles_ordered.' missile'.plural_func($total_missiles_ordered).' sold for $ '.number_format($totalordercost*0.75, 0, ',', ' ');
$array['next'] = true;
$array['newmaxsell'] = $newMax;
$array['newnw'] = get_user_meta($userId, 'networth',true);;
$array['money'] = $totalmoney+($totalordercost*0.75);
echo json_encode($array);
exit;
