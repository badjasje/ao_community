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

$activeTab = $_POST['currentTab'] ? sanitize_text_field($_POST['currentTab']) : 'air';
$marketRedirectUrl = get_permalink(3179) . $activeTab;

nocache_headers();
if (get_field('game_status', 'option') == 'Live') {
    $user_ID = get_current_user_id();

    if (! defined('ABSPATH')) {
        exit;
    }
    if (empty($user_ID)) {
        wp_redirect(get_permalink(3582));
        exit;
    }
    if (!is_user_logged_in()) {
        wp_redirect(get_permalink(3582));
        exit;
    }
    $totalmoney = get_user_meta($user_ID, 'money', true);




    $airspace = get_user_meta($user_ID, 'airfield', true)*10;
    $seaspace = get_user_meta($user_ID, 'shipyard', true)*5;
    $vehspace = get_user_meta($user_ID, 'warfactory', true)*10;
    $infspace = get_user_meta($user_ID, 'baracks', true)*20;


    $spies = get_user_meta($user_ID, 'spy_owned', true);
    $spies_ordered = get_user_meta($user_ID, 'spy_ordered', true);
    $thiefs = get_user_meta($user_ID, 'thief_owned', true);
    $thiefs_ordered = get_user_meta($user_ID, 'thief_ordered', true);
    $planes = get_user_meta($user_ID, 'spyplane_owned', true);
    $planes_ordered = get_user_meta($user_ID, 'spyplane_ordered', true);
    $sniper = get_user_meta($user_ID, 'sniper_owned', true);
    $sniper_ordered = get_user_meta($user_ID, 'sniper_ordered', true);

    $commandcenter = get_user_meta($user_ID, 'command_centre', true);
    $ccspace = ($commandcenter*5)-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;
    if ($ccspace < 0) {
        $ccspace = 0;
    }

    $total_special = $spies+$thiefs+$planes+$spies_ordered+$thiefs_ordered+$planes_ordered+$sniper+$sniper_ordered;

    $discount_level = get_user_meta($user_ID, 'level_market_discount', true);

    if ($discount_level == 0) {
        $discount = 1;
    }
    if ($discount_level == 1) {
        $discount = 0.85;
    }
    if ($discount_level >= 2) {
        $discount = 0.70;
    }


    $MSlevel = get_user_meta($user_ID, 'level_shipping_time')[0];

    if ($MSlevel == 0 || empty($MSlevel)) {
                        $hours = 12;
    }
                    
    if ($MSlevel == 1) {
        $hours = 6;
    }
    if ($MSlevel == 2) {
        $hours = 3;
    }

    $startingbonus = get_user_meta($user_ID, 'starting_bonus', true);
    $shipping_discount = 0;

    if ($startingbonus == 'shipping') {
        $shipping_discount = 0.1;
    }

    include 'units_array.php';




    $totalordercost = 0;
    $total_spec_count = 0;
    $discount_value = $discount-$shipping_discount;
    foreach ($units as $key => $order) {
        if ($key == 'spyplane' || $key == 'spy' || $key == 'thief' || $key == 'sniper') {
            if ($_POST["$key"] > 0) {
                $total_special+=$_POST["$key"];
                $total_spec_count+=$_POST["$key"];
                if (ceil($_POST["$key"]) > $ccspace) {
                    $_SESSION['status'] = 'Not enough command centres';
                    wp_redirect($marketRedirectUrl);
                    exit;
                }
            }
        }

        $price = $order['price']*2.2*$discount_value;
        $ordered_units = ceil($_POST["$key"]);

        if ($_POST["$key"] < 0) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }
    
        if (!is_numeric(
            $letter_check
        )) {
                $_SESSION['status'] = 'Enter a valid number';
                wp_redirect($marketRedirectUrl);
                exit;
        }

        if ($ordered_units > 0) {
            $orderamount = $price*$ordered_units;
            $endvalue += $orderamount;
            $totalordercost = $totalordercost+$orderamount;
        }
    }

    if ($total_spec_count > 0) {
        if ($total_special>500 || $total_special > $ccspace) {
            $_SESSION['status'] = 'Cannot build more than 500 special units';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    }

    $total_air_ordered = 0;
    $total_sea_ordered = 0;
    $total_inf_ordered = 0;
    $total_veh_ordered = 0;
    $air = 0;
    $veh = 0;
    $sea = 0;
    $inf = 0;
    if ($totalordercost > $totalmoney) {
        $_SESSION['status'] = 'Insufficient funds';
        wp_redirect($marketRedirectUrl);
        exit;
    }




// CHECK AIRSPACE //

    foreach ($units as $key => $order) {
        if ($order['type'] == 'air') {
            $unit_name = $key.'_ordered';
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            $air+=$ordered_units;
            $owned_units = get_user_meta($user_ID, $key.'_owned');
            $units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
            $total_air_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];
        }
    }
        
    if ($air>0) {
        if ($total_air_ordered > $airspace) {
            $_SESSION['status'] = 'Build more airfields';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    }

// CHECK VEHSPACE //

    foreach ($units as $key => $order) {
        if ($order['type'] == 'veh') {
            $unit_name = $key.'_ordered';
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            $veh+=$ordered_units;
            $owned_units = get_user_meta($user_ID, $key.'_owned');
            $units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
            $total_veh_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];
        }
    }
        
    if ($veh>0) {
        if ($total_veh_ordered > $vehspace) {
            $_SESSION['status'] = 'Build more warfactories';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    }

// CHECK SEASPACE //

    foreach ($units as $key => $order) {
        if ($order['type'] == 'sea') {
            $unit_name = $key.'_ordered';
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            $sea+=$ordered_units;
            $owned_units = get_user_meta($user_ID, $key.'_owned');
            $units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
            $total_sea_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];
        }
    }
        
    if ($sea>0) {
        if ($total_sea_ordered > $seaspace) {
            $_SESSION['status'] = 'Build more shipyards';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    }

// CHECK INFSPACE //

    foreach ($units as $key => $order) {
        if ($order['type'] == 'inf') {
            $unit_name = $key.'_ordered';
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            
            if ($key == 'spy' && $_POST["$key"] > 0) {
                if (ceil($_POST["$key"]) > $ccspace) {
                    $_SESSION['status'] = 'Not enough command centres';
                    wp_redirect(get_permalink(3415));
                    exit;
                }
            }
            
            if ($key == 'thief' && $_POST["$key"] > 0) {
                if (ceil($_POST["$key"]) > $ccspace) {
                    $_SESSION['status'] = 'Not enough command centres';
                    wp_redirect(get_permalink(3415));
                    exit;
                }
            }
            
            $inf+=$ordered_units;
            $owned_units = get_user_meta($user_ID, $key.'_owned');
            $units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
            $total_inf_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];
        }
    }
        
    if ($inf>0) {
        if ($total_inf_ordered > $infspace) {
            $_SESSION['status'] = 'Build more baracks';
            wp_redirect($marketRedirectUrl);
            exit;
        }
    }
            
            

    $total_units_ordered = 0;
    $total_order_amount = 0;
    foreach ($units as $key => $order) {
            $delay = $_POST['delay'.$key];
        if ($delay > 360) {
            $delay = 360;
        }
        if ($delay < 0) {
            $delay = 0;
        }
            $unit_name = $key.'_ordered';
    
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            $total_air_ordered+=$ordered_units;
    
            
        if ($ordered_units > 0) {
            $orderamount = $price*$ordered_units;
    
            $total_units_ordered+=$ordered_units;
            $units_on_order = get_user_meta($user_ID, $unit_name);
            $units_on_order = $units_on_order[0];

        
            update_user_meta($user_ID, 'money', $totalmoney-$totalordercost);

            $total_order_amount+=$totalordercost;
            
            
            update_user_meta($user_ID, $unit_name, $units_on_order+$ordered_units);
            
            $args = array(
            'post_title'    => $order['normalname'],
            'post_status'   => 'publish',
            'post_type'     => 'market_order',
            'post_author'   => $user_ID
            );
            $timestamp = current_time('timestamp');
            
            $new_order_id = wp_insert_post($args);
            update_field('unit_type', $key, $new_order_id);
            update_field('user_placed_id', $user_ID, $new_order_id);
            update_field('time_placed', $timestamp, $new_order_id);
            update_field('delivery_time', $timestamp+($hours * 3600)+($delay*60), $new_order_id);
            update_field('amount_ordered', $ordered_units, $new_order_id);
            update_field('order_type', 'units', $new_order_id);
            update_field('order_value', ($price*2.2*$discount_value)*$ordered_units, $new_order_id);
            
            $units_ordered = get_user_meta($user_ID, 'units_ordered', true);
            update_user_meta($user_ID, 'units_ordered', $units_ordered+$ordered_units);
            
            $file = 'marketlog.txt';
// Open the file to get existing content
            $current = file_get_contents($file);
// Append a new person to the file
            $time = current_time('G:i:s | d-m-Y');
            $current .= $time."\n";
            $current .= "ID: ".$user_ID."\n";
            $current .= "Units ordered: ".$ordered_units."\n\n";
// Write the contents back to the file
            file_put_contents($file, $current);
        }
    }




    $_SESSION['status'] = $total_units_ordered. ' units ordered for a total price of $ '.number_format($endvalue, 0, ',', ' ');
    wp_redirect($marketRedirectUrl);
    exit;
}
