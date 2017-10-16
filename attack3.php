<?php
/**
 * Handles attacks
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
include('units_array.php');
include('building_array.php');
include('attack_functions.php');


/* 
	is this even used? seems only content-attackresult.php is used 
	-stonefish
*/




$attacking_units = $_POST;

$SEA_ATT_power = 0;
$AIR_ATT_power = 0;
$INF_ATT_power = 0;
$VEH_ATT_power = 0;
$BLD_ATT_power = 0;

$SEA_ATT_life = 0;
$AIR_ATT_life = 0;
$INF_ATT_life = 0;
$VEH_ATT_life = 0;

$no_air_types = 0;
$no_veh_types = 0;
$no_inf_types = 0;
$no_sea_types = 0;

$_total_air_units_att = 0;
$_total_inf_units_att = 0;
$_total_veh_units_att = 0;
$_total_sea_units_att = 0;

$user_id = get_current_user_ID();
$attacker_data = get_user_meta($user_id);

$target_id = $_SESSION['target_id'];
$target_data = get_user_meta($target_id);

error_log("step 3 target id: ".$target_id);


foreach ($attacking_units as $key => $order) {
    $units_owned = get_user_meta($userId, $key.'_owned');
    $units_attacking = $units_owned[0]*$order;
    
    $attackpower = $units[$key]['attack']*$units_attacking;
    $divided_power = $attackpower/count($units[$key]['attacks']);
    
    
    $unittype = $units[$key]['type'];
    if ($unittype == 'sea') {
        $no_sea_types = $no_sea_types+1;
        $SEA_ATT_life = $units[$key]['life']*$units_attacking;
        $_total_sea_units_att+=$units_attacking;
    }
    if ($unittype == 'air') {
        $no_air_types = $no_air_types+1;
        $AIR_ATT_life = $units[$key]['life']*$units_attacking;
        $_total_air_units_att+=$units_attacking;
    }
    if ($unittype == 'inf') {
        $no_inf_types = $no_inf_types+1;
        $INF_ATT_life = $units[$key]['life']*$units_attacking;
        $_total_inf_units_att+=$units_attacking;
    }
    if ($unittype == 'veh') {
        $no_veh_types = $no_veh_types+1;
        $VEH_ATT_life = $units[$key]['life']*$units_attacking;
        $_total_veh_units_att+=$units_attacking;
    }
    

    
    $attacks = $units[$key]['attacks'];
    foreach ($attacks as $attack) {
        if ($attack == 'sea') {
            $SEA_ATT_power+= $divided_power*(rand(9, 11)/10);
        }
        if ($attack == 'air') {
            $AIR_ATT_power+= $divided_power*(rand(9, 11)/10);
        }
        if ($attack == 'inf') {
            $INF_ATT_power+= $divided_power*(rand(9, 11)/10);
        }
        if ($attack == 'veh') {
            $VEH_ATT_power+= $divided_power*(rand(9, 11)/10);
        }
        if ($attack == 'bld') {
            $BLD_ATT_power+= $divided_power*(rand(9, 11)/10);
        }
    }
}



$SEA_DEF_ATT_power = 0;
$AIR_DEF_ATT_power = 0;
$INF_DEF_ATT_power = 0;
$VEH_DEF_ATT_power = 0;


$SEA_DEF_life = 0;
$AIR_DEF_life = 0;
$INF_DEF_life = 0;
$VEH_DEF_life = 0;

    // DEFENDING //
        $_total_air_units_def = 0;
        $_total_inf_units_def = 0;
        $_total_veh_units_def = 0;
        $_total_sea_units_def = 0;
        
foreach ($units as $key => $order) {
            $def_units_owned = get_user_meta($userId, $key.'_owned');
            $units_defending = $def_units_owned[0];
            $defpower = $units[$key]['attack']*$units_defending;
            
        
            $divided_defpower = $defpower/count($units[$key]['defends']);
            
            $defends = $units[$key]['defends'];
            $life = $units[$key]['life'];
            
            $unittype = $units[$key]['type'];
    if ($unittype == 'sea') {
        $SEA_DEF_life+= $units[$key]['life']*$units_defending;
                    
        $_total_sea_units_def+= $units_defending;
    }
    if ($unittype == 'air') {
        $AIR_DEF_life+= $units[$key]['life']*$units_defending;
                    
        $_total_air_units_def+= $units_defending;
    }
    if ($unittype == 'inf') {
        $INF_DEF_life+= $units[$key]['life']*$units_defending;
                
        $_total_inf_units_def+= $units_defending;
    }
    if ($unittype == 'veh') {
        $VEH_DEF_life+= $units[$key]['life']*$units_defending;
                    
        $_total_veh_units_def+= $units_defending;
    }
            
            
            
            $defends = $units[$key]['attacks'];
    foreach ($defends as $defend) {
        if ($defend == 'sea') {
            $SEA_DEF_ATT_power+= $divided_defpower*(rand(8, 11)/10);
        }
        if ($defend == 'air') {
            $AIR_DEF_ATT_power+= $divided_defpower*(rand(8, 11)/10);
        }
        if ($defend == 'inf') {
            $INF_DEF_ATT_power+= $divided_defpower*(rand(8, 11)/10);
        }
        if ($defend == 'veh') {
            $VEH_DEF_ATT_power+= $divided_defpower*(rand(8, 11)/10);
        }
    }
}


foreach ($buildings as $key => $building) {
    if ($building['attacks'][0] == 'sea') {
        $def_bld_owned = get_user_meta($userId, $key);
        $def_bld_owned = $def_bld_owned[0];
                    
        $SEA_DEF_ATT_power+= $def_bld_owned*$building['attack']*(rand(70, 110)/100);
    }
    if ($building['attacks'][0] == 'air') {
        $def_bld_owned = get_user_meta($userId, $key);
        $def_bld_owned = $def_bld_owned[0];
                
        $AIR_DEF_ATT_power+= $def_bld_owned*$building['attack']*(rand(70, 110)/100);
    }
    if ($building['attacks'][0] == 'inf') {
        $def_bld_owned = get_user_meta($userId, $key);
        $def_bld_owned = $def_bld_owned[0];
                    
        $INF_DEF_ATT_power+= $def_bld_owned*$building['attack']*(rand(70, 110)/100);
    }
    if ($building['attacks'][0] == 'veh') {
        $def_bld_owned = get_user_meta($userId, $key);
        $def_bld_owned = $def_bld_owned[0];
                
        $VEH_DEF_ATT_power+= $def_bld_owned*$building['attack']*(rand(70, 110)/100);
    }
}

$airdamage = $AIR_ATT_power/3.2;
$infdamage = $INF_ATT_power/3.2;
$vehdamage = $VEH_ATT_power/3.2;
$seadamage = $SEA_ATT_power/3.2;
$blddamage = $BLD_ATT_power/3.2;

$VEH_DEF_ATT_power = $VEH_DEF_ATT_power/3.2;
$AIR_DEF_ATT_power = $AIR_DEF_ATT_power/3.2;
$INF_DEF_ATT_power = $INF_DEF_ATT_power/3.2;
$SEA_DEF_ATT_power = $SEA_DEF_ATT_power/3.2;


/// KILLING DEFENDER UNITS ///
$TOTAL_ATT_DAMAGE = 0;
$defender_lost = array();
foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        
        //AIR
    if ($unittype == 'air') {
        $def_units_owned = get_user_meta($userId, $key.'_owned');
        $def_units_owned = $def_units_owned[0];
        if ($def_units_owned > 0) {
            $percentage = $def_units_owned/$_total_air_units_def;
            $damage = $airdamage*$percentage;
            $TOTAL_ATT_DAMAGE+=$damage;
            $units_lost = round($damage/$units[$key]['life']);
            if ($units_lost > 0) {
                if ($def_units_owned < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $defender_lost[] = array('type' => 'unit',$key => $def_units_owned);
                } else {
                    update_user_meta($userId, $key.'_owned', $def_units_owned-$units_lost);
                    $defender_lost[] = array('type' => 'unit',$key => $units_lost);
                }
            }
        }
    }
                    
        //INF
    if ($unittype == 'inf') {
        $def_units_owned = get_user_meta($userId, $key.'_owned');
        $def_units_owned = $def_units_owned[0];
        if ($def_units_owned > 0) {
            $percentage = $def_units_owned/$_total_inf_units_def;
            $damage = $infdamage*$percentage;
            $TOTAL_ATT_DAMAGE+=$damage;
            $units_lost = round($damage/$units[$key]['life']);
            if ($units_lost > 0) {
                if ($def_units_owned < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $defender_lost[] = array('type' => 'unit',$key => $def_units_owned);
                } else {
                    update_user_meta($userId, $key.'_owned', $def_units_owned-$units_lost);
                    $defender_lost[] = array('type' => 'unit',$key => $units_lost);
                }
            }
        }
    }
        //VEH
    if ($unittype == 'veh') {
        $def_units_owned = get_user_meta($userId, $key.'_owned');
        $def_units_owned = $def_units_owned[0];
        if ($def_units_owned > 0) {
            $percentage = $def_units_owned/$_total_veh_units_def;
            $damage = $vehdamage*$percentage;
            $TOTAL_ATT_DAMAGE+=$damage;
            $units_lost = round($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($def_units_owned < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $defender_lost[] = array('type' => 'unit',$key => $def_units_owned);
                } else {
                    update_user_meta($userId, $key.'_owned', $def_units_owned-$units_lost);
                    $defender_lost[] = array('type' => 'unit',$key => $units_lost);
                }
            }
        }
    }
        //SEA
    if ($unittype == 'sea') {
        $def_units_owned = get_user_meta($userId, $key.'_owned');
        $def_units_owned = $def_units_owned[0];
        if ($def_units_owned > 0) {
            $percentage = $def_units_owned/$_total_sea_units_def;
            $damage = $seadamage*$percentage;
            $TOTAL_ATT_DAMAGE+=$damage;
            $units_lost = round($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($def_units_owned < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $defender_lost[] = array('type' => 'unit',$key => $def_units_owned);
                } else {
                    update_user_meta($userId, $key.'_owned', $def_units_owned-$units_lost);
                    $defender_lost[] = array('type' => 'unit',$key => $units_lost);
                }
            }
        }
    }
}
    
    
// KILLING BUILDINGS OF DEFENDER //
$_total_bld_def = 0;
foreach ($buildings as $key => $building) {
    $def_bld_owned = get_user_meta($userId, $key);
    $_total_bld_def+= $def_bld_owned[0];
}


foreach ($buildings as $key => $building) {
        //bld
                    $def_bld_owned = get_user_meta($userId, $key);
                    $def_bld_owned = $def_bld_owned[0];
                    
    if ($def_bld_owned > 0) {
        $percentage = $def_bld_owned/$_total_bld_def;
                    
        $damage = $blddamage*$percentage;
        $TOTAL_ATT_DAMAGE+=$damage;
        $buildings_lost = round($damage/$building['life']);
                    
        if ($buildings_lost > 0) {
            if ($def_bld_owned < $buildings_lost) {
                update_user_meta($userId, $key, 0);
                $defender_lost[] = array('type' => 'bld',$key => $def_bld_owned);
            } else {
                update_user_meta($userId, $key, $def_bld_owned-$buildings_lost);
                $defender_lost[] = array('type' => 'bld',$key => $buildings_lost);
            }
        }
    }
}
    
// DEFENDING //
        
$TOTAL_DEF_DAMAGE = 0;
/// KILLING ATTACKER UNITS ///
$attacker_lost = array();
foreach ($attacking_units as $key => $order) {
        $unittype = $units[$key]['type'];
        
        //AIR
    if ($unittype == 'air') {
        $units_owned = get_user_meta($userId, $key.'_owned');
        $units_attacking = $units_owned[0]*$order;
                    
        if ($units_attacking > 0) {
            $percentage = $units_attacking/$_total_air_units_att;
            $damage = $AIR_DEF_ATT_power*$percentage;
            $TOTAL_DEF_DAMAGE+=$damage;
            $units_lost = ceil($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($units_attacking < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $attacker_lost[] = array($key => $units_attacking);
                } else {
                    $total_units_attacker = get_user_meta($userId, $key.'_owned');
                    update_user_meta($userId, $key.'_owned', $total_units_attacker[0]-$units_lost);
                    $attacker_lost[] = array($key => $units_lost);
                }
            }
        }
    }
        
        //SEA
    if ($unittype == 'sea') {
        $units_owned = get_user_meta($userId, $key.'_owned');
        $units_attacking = $units_owned[0]*$order;
                    
        if ($units_attacking > 0) {
            $percentage = $units_attacking/$_total_sea_units_att;
            $damage = $SEA_DEF_ATT_power*$percentage;
            $TOTAL_DEF_DAMAGE+=$damage;
            $units_lost = ceil($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($units_attacking < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $attacker_lost[] = array($key => $units_attacking);
                } else {
                    $total_units_attacker = get_user_meta($userId, $key.'_owned');
                    update_user_meta($userId, $key.'_owned', $total_units_attacker[0]-$units_lost);
                    $attacker_lost[] = array($key => $units_lost);
                }
            }
        }
    }
        
        //INF
    if ($unittype == 'inf') {
        $units_owned = get_user_meta($userId, $key.'_owned');
        $units_attacking = $units_owned[0]*$order;
                    
        if ($units_attacking > 0) {
            $percentage = $units_attacking/$_total_inf_units_att;
            $damage = $INF_DEF_ATT_power*$percentage;
            $TOTAL_DEF_DAMAGE+=$damage;
            $units_lost = ceil($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($units_attacking < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $attacker_lost[] = array($key => $units_attacking);
                } else {
                    $total_units_attacker = get_user_meta($userId, $key.'_owned');
                    update_user_meta($userId, $key.'_owned', $total_units_attacker[0]-$units_lost);
                    $attacker_lost[] = array($key => $units_lost);
                }
            }
        }
    }
                                                
        //VEH
    if ($unittype == 'veh') {
        $units_owned = get_user_meta($userId, $key.'_owned');
        $units_attacking = $units_owned[0]*$order;
                    
        if ($units_attacking > 0) {
            $percentage = $units_attacking/$_total_veh_units_att;
            $damage = $VEH_DEF_ATT_power*$percentage;
            $TOTAL_DEF_DAMAGE+=$damage;
            $units_lost = ceil($damage/$units[$key]['life']);
                    
            if ($units_lost > 0) {
                if ($units_attacking < $units_lost) {
                    update_user_meta($userId, $key.'_owned', 0);
                    $attacker_lost[] = array($key => $units_attacking);
                } else {
                    $total_units_attacker = get_user_meta($userId, $key.'_owned');
                    update_user_meta($userId, $key.'_owned', $total_units_attacker[0]-$units_lost);
                    $attacker_lost[] = array($key => $units_lost);
                }
            }
        }
    }
}
    

if ($TOTAL_DEF_DAMAGE>$TOTAL_ATT_DAMAGE) {
    $result = 'failure';
    $_SESSION['money_stolen'] = 0;
    $_SESSION['land_stolen'] = 0;
} else {
    $result = 'success';

    $stealing_percentage = ($TOTAL_ATT_DAMAGE-$TOTAL_DEF_DAMAGE)/$TOTAL_DEF_DAMAGE;

    $money = get_user_meta($userId, 'money');
    $land = get_user_meta($userId, 'land');
    $builtland = get_user_meta($userId, 'builtland');
    $freeland = $land[0]-$builtland[0];

    $land_stolen = $freeland*0.02*(($stealing_percentage/3)+1);
    $money_stolen = $money[0]*0.02*($stealing_percentage+1);
    $_SESSION['money_stolen'] = $money_stolen;
    $_SESSION['land_stolen'] = $land_stolen;
}
    





$_SESSION['result'] = $result;
$_SESSION['defender_lost'] = $defender_lost;
$_SESSION['attacker_lost'] = $attacker_lost;








wp_redirect(get_permalink(3418));   //result
exit;
