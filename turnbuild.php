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
$unitsRedirectUrl = get_permalink(3415) . $activeTab;

nocache_headers();

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
$totalturns = get_user_meta($user_ID, 'turns', true);


include('units_array.php');



$totalordercost = 0;
$totalunits = 0;
$total_AIR = 0;
$total_SEA = 0;
$total_INF = 0;
$total_VEH = 0;

$airspace = get_user_meta($user_ID, 'airfield');
$airspace = $airspace[0]*10;
$seaspace = get_user_meta($user_ID, 'shipyard');
$seaspace = $seaspace[0]*5;
$vehspace = get_user_meta($user_ID, 'warfactory');
$vehspace = $vehspace[0]*10;
$infspace = get_user_meta($user_ID, 'baracks');
$infspace = $infspace[0]*20;


$spies = get_user_meta($user_ID, 'spy_owned', true);
$spies_ordered = get_user_meta($user_ID, 'spy_ordered', true);
$thiefs = get_user_meta($user_ID, 'thief_owned', true);
$thiefs_ordered = get_user_meta($user_ID, 'thief_ordered', true);
$planes = get_user_meta($user_ID, 'spyplane_owned', true);
$planes_ordered = get_user_meta($user_ID, 'spyplane_ordered', true);
$sniper = get_user_meta($user_ID, 'sniper_owned', true);
$sniper_ordered = get_user_meta($user_ID, 'sniper_ordered', true);
$saboteur = get_user_meta($user_ID, 'saboteur_owned', true);
$saboteur_ordered = get_user_meta($user_ID, 'saboteur_ordered', true);

$commandcenter = get_user_meta($user_ID, 'command_centre', true);
$ccspace = ($commandcenter*5)-$saboteur--$saboteur_ordered$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;

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
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
        $tot_air+=ceil($_POST["$key"]);
            
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }
        if (!is_numeric($letter_check)) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
            
        if ($key == 'spyplane' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $_SESSION['status'] = 'Not enough command centres';
                wp_redirect($unitsRedirectUrl);
                exit;
            }
        }
            
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
        wp_redirect($unitsRedirectUrl);
        exit;
    }
}

// CHECK VEHSPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'veh') {
        if ($_POST["$key"] < 0) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
        $tot_veh+=ceil($_POST["$key"]);
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }
        if (!is_numeric($letter_check)) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
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
        wp_redirect($unitsRedirectUrl);
        exit;
    }
}

// CHECK SEASPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'sea') {
        if ($_POST["$key"] < 0) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
        $tot_sea+=ceil($_POST["$key"]);
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }
        if (!is_numeric($letter_check)) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
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
        wp_redirect($unitsRedirectUrl);
        exit;
    }
}

// CHECK INFSPACE //

foreach ($units as $key => $order) {
    if ($order['type'] == 'inf') {
        if ($_POST["$key"] < 0) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
        $tot_inf+=ceil($_POST["$key"]);
        if (empty($_POST["$key"])) {
            $letter_check = 0;
        } else {
            $letter_check = $_POST["$key"];
        }
        if (!is_numeric($letter_check)) {
            $_SESSION['status'] = 'Enter a valid number';
            wp_redirect($unitsRedirectUrl);
            exit;
        }
            
        if ($key == 'spy' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $_SESSION['status'] = 'Not enough command centres';
                wp_redirect($unitsRedirectUrl);
                exit;
            }
        }
            
        if ($key == 'thief' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $_SESSION['status'] = 'Not enough command centres';
                wp_redirect($unitsRedirectUrl);
                exit;
            }
        }
            
        if ($key == 'sniper' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $_SESSION['status'] = 'Not enough command centres';
                wp_redirect($unitsRedirectUrl);
                exit;
            }
        }
        
        if ($key == 'saboteur' && $_POST["$key"] > 0) {
            $total_special+=$_POST["$key"];
            $total_spec_count+=$_POST["$key"];
            if (ceil($_POST["$key"]) > $ccspace) {
                $_SESSION['status'] = 'Not enough command centres';
                wp_redirect($unitsRedirectUrl);
                exit;
            }
        }
            
        $unit_name = $key.'_ordered';
        $normalname = $order['normalname'];
        $price = $order['price'];
        $ordered_units = ceil($_POST["$key"]);
        $inf+=$ordered_units;
        $owned_units = get_user_meta($user_ID, $key.'_owned');
        $units_already_on_order = get_user_meta($user_ID, $key.'_ordered');
        $total_inf_ordered+=$ordered_units+$owned_units[0]+$units_already_on_order[0];
    }
}
        
if ($inf>0) {
    if ($total_inf_ordered > $infspace) {
        $_SESSION['status'] = 'Build more baracks';
        wp_redirect($unitsRedirectUrl);
        exit;
    }
}



if ($total_spec_count>0) {
    echo $total_special.' total special<br/>';
    echo $ccspace.' cc space<br/>';
    echo $total_spec_count.' tot spec count';

    if ($total_special>500 || $total_spec_count > $ccspace) {
        $_SESSION['status'] = 'Cannot build more than 500 special units';
        wp_redirect($unitsRedirectUrl);
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
    $_SESSION['status'] = 'Not enough turns';
    wp_redirect($unitsRedirectUrl);
    exit;
} else {
    if ($totalordercost > $totalmoney) {
        $_SESSION['status'] = 'Insufficient funds';
        wp_redirect($unitsRedirectUrl);
        exit;
    } else {
        $units_built_turns = get_user_meta($user_ID, 'units_built_turns', true);
    
    
        foreach ($units as $key => $order) {
            $unit_name = $key;
    
            $normalname = $order['normalname'];
            $price = $order['price'];
            $ordered_units = ceil($_POST["$key"]);
            if ($ordered_units > 0) {
                $orderamount = $price*$ordered_units;
    
        
                $units_owned = get_user_meta($user_ID, $unit_name.'_owned');
                $total_units_ordered+=$ordered_units;

        
            
            
        
            
            
                update_user_meta($user_ID, $unit_name.'_owned', $units_owned[0]+$ordered_units);
                $units_tbuilt = get_user_meta($user_ID, 'units_built_turns', true);
                update_user_meta($user_ID, 'units_built_turns', $units_tbuilt+$ordered_units);
            
                $success = '?success=1';
        
        
        
                $file = 'turnbuildlog.txt';
    // Open the file to get existing content
                $current = file_get_contents($file);
    // Append a new person to the file
                $time = current_time('G:i:s | d-m-Y');
                $current .= $time."\n";
                $current .= "ID: ".$user_ID."\n";
                $current .= "Units ordered: ".$unit_name." ".$ordered_units."\n\n";
    // Write the contents back to the file
                file_put_contents($file, $current);
            }
        }
    }
}
count_all_stats($user_ID);
update_user_meta($user_ID, 'money', $totalmoney-$totalordercost);
update_user_meta($user_ID, 'turns', $totalturns-$turns_needed);



$_SESSION['status'] = $total_units_ordered.' units built, for the total price of $ '. number_format($totalordercost, 0, ',', ' ').' and '.$turns_needed.' turns';
wp_redirect($unitsRedirectUrl);
  
  exit;
