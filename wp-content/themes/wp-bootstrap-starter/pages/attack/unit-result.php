<?php
$units = Units::get();
$buildings = Buildings::get();

$defender_networth_lost = 0;
$defender_building_NW_lost = 0;

$attack_nw = $attackerData['networth'][0];
$attack_clan_id = $attackerData['clan_id_user'][0];
$attack_clan = new Clan($attack_clan_id);

$attack_cost_turns = 0;
$attack_cost_morale = 0;

$maintarget = ($debug ? $_POST['maintarget'] : filter_input(INPUT_POST, 'maintarget', FILTER_SANITIZE_STRING));
if(!in_array($maintarget, array_keys(Settings::get('attack_maintargets')))) {
	$maintarget = 'none';
}
$attackmode = ($debug ? $_POST['attackmode'] : filter_input(INPUT_POST, 'attackmode', FILTER_SANITIZE_STRING));
$attackmode = ($attackmode == 'aggressive' ? 'aggressive' : 'normal');
if($debug) debug_var('Attackmode', $attackmode);
if($debug) debug_var('Maintarget', $maintarget);

$extra_morale_cost = 0;
$aggressive_multi = 1;

$life_deduct = 1;
if($attackmode == 'aggressive'){
	$extra_morale_cost = 10;
	$life_deduct = 1.15;
	$aggressive_multi = 1.35;
}

$defend_nw = $defenderData['networth'][0];
$defend_clan_id = $defenderData['clan_id_user'][0];
$defend_clan = new Clan($defend_clan_id);

/* retrieve attack info */
$attack_type = $_POST['attacktype'];
$attack_array = $_POST['attackarray'];
if($debug) debug_var('Attacktype', $attack_type);
if($debug) debug_var('Attack_array', print_r($attack_array,1));

/* standard regular attack multis */
$dmgMulti = 0.95;
$resourceMulti = 1.25;

/* set multis other attack types */
if($attack_type == 'air_sea'){
	$dmgMulti = 1.17;
	$resourceMulti = 0.9;
}

if($attack_type == 'ground' ){
	$dmgMulti = 1.10;
	$resourceMulti = 1;
}


if($attackerData['starting_bonus'][0] == 'aggressive'){
	$dmgMulti = $dmgMulti*1.20;
}


// Check if user is member of clan for 24h, if not, cannot attack out of range in mutual
$join_timestamp = $attackerData['clan_join_stamp'][0];
$timestamp = current_time('timestamp');
$in_range = target_in_range($attack_type, $attack_nw, $defend_nw, 'none');

if ($war_type == 'mutual' && $timestamp < $join_timestamp && $in_range != true) {
	$array['status'] = 'Cannot attack out of networth range in mutual war the first 24 hours after joining a clan';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$attack_cost_turns = get_attack_cost_turns($attack_type);
$attack_cost_morale = get_attack_cost_morale($attack_type, $attack_nw, $defend_nw)+$extra_morale_cost;

/* retrieve attacker's current resources */
$attack_curr_turns = $attackerData['turns'][0];
$attack_curr_morale = $attackerData['morale'][0];

/* validate attacker has sufficient morale */
if (!$debug && $attack_cost_morale > $attack_curr_morale) {
	$array['status'] = 'Insufficient morale';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* validate attacker has sufficient turns */
if (!$debug && $attack_cost_turns > $attack_curr_turns) {
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* deduct attack cost */
$attack_new_turns = $attack_curr_turns - $attack_cost_turns;
if($debug) debug_update_user($userId, 'turn_cost', $attack_cost_turns);
else update_user_meta($userId, 'turns', $attack_new_turns);
$attack_new_morale = $attack_curr_morale - $attack_cost_morale;
if($debug) debug_update_user($userId, 'morale_cost', $attack_cost_morale);
else update_user_meta($userId, 'morale', $attack_new_morale);
turn_spread('unit_attack', $attack_cost_turns);

/* start checking for damage split */
$defAirTot = 0;
$defSeaTot = 0;
$defInfTot = 0;
$defVehTot = 0;

$removeArray = array('air','sea','inf','veh');
foreach ($units as $key => $unit) {
	if($unit['sectype'] == 'special') continue;
	if($units[$key]['type'] == 'air') $defAirTot += $defenderData[$key.'_owned'][0];
	if($units[$key]['type'] == 'sea') $defSeaTot += $defenderData[$key.'_owned'][0];
	if($units[$key]['type'] == 'inf') $defInfTot += $defenderData[$key.'_owned'][0];
	if($units[$key]['type'] == 'veh') $defVehTot += $defenderData[$key.'_owned'][0];
}

/* If defender has unit type, unset from remove array */
if($defAirTot > 0) unset($removeArray[0]);
if($defSeaTot > 0) unset($removeArray[1]);
if($defInfTot > 0) unset($removeArray[2]);
if($defVehTot > 0) unset($removeArray[3]);

/* iterate over attack array */
/* damage by type */
$attAirTot = 0;
$attSeaTot = 0;
$attInfTot = 0;
$attVehTot = 0;

$attacker_type_damage = array('bld'=>0);
$attackerRemoveArray = array('air','sea','inf','veh');
foreach ($attack_array as $key => $count) {
	if($key != 'tomahawk'){
		if($count > 0){
			if($units[$key]['type'] == 'air') $attAirTot = 1;
			if($units[$key]['type'] == 'sea') $attSeaTot = 1;
			if($units[$key]['type'] == 'inf') $attInfTot = 1;
			if($units[$key]['type'] == 'veh') $attVehTot = 1;
	    }
	}
}

foreach ($attack_array as $key => $count) {
	$owned_units = $attackerData[$key.'_owned'][0];
	if($count > $owned_units) $count = $owned_units;
	else $count = $owned_units * $count;

	/* distribute attack power equally across types */
	if($key != 'tomahawk'){
		$atk_types 		= $units[$key]['attacks'];
		$typecountInit 	= count($atk_types);

		/* removing attack types defender does not have */
		$atk_types 		= array_diff($atk_types, $removeArray);
		$type_count 	= count($atk_types);
		$atk_power_total = ($count * $units[$key]['attack'] * $dmgMulti);
		$typeMulti = 1;
		$typeDif = $type_count-$typecountInit;
		if($typeDif == -1){
			$typeMulti = 0.9;
		}
		if($typeDif == -2){
			$typeMulti = 0.8;
		}
		$atk_power_distrib = $atk_power_total*$typeMulti / max($type_count,1);

		/* damage per unit */
		$attacker_single_unit_damage = array();
		foreach($atk_types as $type) {
			$one_type = array($type);

			/* calculate attack totals by type */
			if (array_key_exists($type, $attacker_type_damage)) $attacker_type_damage[$type] += $atk_power_distrib;
			else $attacker_type_damage[$type] = $atk_power_distrib;
		}
	}
}
if($debug) debug_var('attacker_type_damage0', print_r($attacker_type_damage,1));

/* If defender has unit type, unset from remove array */
if($attAirTot > 0) unset($attackerRemoveArray[0]);
if($attSeaTot > 0) unset($attackerRemoveArray[1]);
if($attInfTot > 0) unset($attackerRemoveArray[2]);
if($attVehTot > 0) unset($attackerRemoveArray[3]);
$tomahawksSent = 0;
$shotdown = 0;
if(array_key_exists('tomahawk', $attack_array)){
	$tomahawks = $attackerData['tomahawk_owned'][0];
	$tomahawkPerc = $attack_array['tomahawk'];

	$tomahawksSent = floor($tomahawks*$tomahawkPerc);

	$tomahawkspace = $attackerData['submarine_owned'][0]*2;
	$maxNetworth = round($attackerData['networth'][0]/10000*2);
	$maxTomahawk = min($tomahawkspace,$maxNetworth,$tomahawksSent);

	$tomahawksSent = $maxTomahawk;

	$samSites = $defenderData['samsite'][0];
	$ams = $defenderData['antimissile'][0];
	$defPower = $defenderData['power'][0];
	$shotdown = ceil(($samSites*(mt_rand(120,135)/1000))+($ams*(mt_rand(190,250)/1000)));

	if($shotdown > $tomahawksSent) $shotdown = ceil($tomahawksSent*0.75);
	if($defPower > 100) $shotdown = 0;

	if($tomahawksSent > 0){
		$tomahawkDamage = ($tomahawksSent-$shotdown)*1850*(mt_rand(90,110)/100);
		$damageDamp = sqrt(($tomahawksSent-$shotdown)*2.3);
		$attacker_type_damage['bld'] += $tomahawkDamage*((100-$damageDamp)/100);
	}

	if($debug) debug_update_user($userId, 'tomahawk_owned', $tomahawks-$tomahawksSent);
    else update_user_meta($userId, 'tomahawk_owned', $tomahawks-$tomahawksSent);
}

// Attack power scaled to number of out-of-war attacks within X days between two provinces, where first Y aren't counted,
// only applied outside of war
if($war_type == 'none') {
	foreach($attacker_type_damage as $type => $dmg) {
		$attacker_type_damage[$type] = scaled_power_pvp($dmg, $userId, $target_id);
	}
}

// Scale building damage on clan size difference
if($debug) debug_var('attacker_type_damage', print_r($attacker_type_damage,1));
if(isset($attacker_type_damage['bld'])) {
	//$defend_clan->getClanSizeDamageMultiplier($attack_clan);
	$attacker_type_damage['bld'] = scaled_damage_to_clansize($attacker_type_damage['bld'], $userId, $target_id);
}
if($debug) debug_var('scaled_attacker_type_damage', print_r($attacker_type_damage,1));

// Check if there are wars for statistic counting
$warstatID = 0;
$warcheck = get_posts(
	array(
		'numberposts'	=> -1,
		'post_type'		=> 'wars',
		'meta_query'	=> array(
			'relation'		=> 'AND',
			array(
				'key'	 	=> 'declared_on',
				'value'	  	=> array($attack_clan_id,$defend_clan_id),
				'compare' 	=> 'IN',
			),
			array(
				'key'	 	=> 'declared_by',
				'value'	  	=> array($attack_clan_id,$defend_clan_id),
				'compare' 	=> 'IN',
			),
		),
	)
);
if(is_array($warcheck) && count($warcheck) > 0){
	$warstatID = get_post_meta($warcheck[0]->ID, 'war_array_id', true);
	if($debug) debug_var('in war', true);
}

/* add statistics for defender and attacker */
//attacker
$attacks_made = $attackerData['attacks_made'][0];
update_user_meta($userId, 'attacks_made', $attacks_made+1);

//defender
if(!$attacker->isShadowBanned()) {
	$attacks_received = (isset($defenderData['attacks_received']) ? $defenderData['attacks_received'][0] : 0);
	update_user_meta($target_id, 'attacks_received', $attacks_received+1);
}

/* calculate power usage */
$defender_power_usage = calculate_power($target_id);
$defender_power_on = $defender_power_usage < 1;
if($debug) debug_var('Power usage', $defender_power_usage);

/* calculate defense by type */
$defense_by_type = calculate_defense_by_type($target_id, $defender_power_on, $attackerRemoveArray);
$defense_attack_type = $defense_by_type['attack'];
$defense_life_type = $defense_by_type['life'];
if($debug) debug_var('defense_by_type', print_r($defense_by_type,1));

$defense_by_type2 = calculate_defense_by_type2($target_id, $defender_power_on, $attackerRemoveArray);
$defense_attack_type2 = $defense_by_type2['attack'];  // Used to calculate Attack Power vs Defense Power
$defense_life_type2 = $defense_by_type2['life'];
if($debug) debug_var('defense_by_type2', print_r($defense_by_type2,1));

/* get defender breakdown to determine kills */
$defender_unit_array = create_defender_array($target_id, array_keys($attacker_type_damage));
$defender_building_total = $defender_unit_array['bld']['total_count'];
if($debug) debug_var('defender_unit_array', print_r($defender_unit_array,1));

/* determine kills using unit and damage arrays */
$defender_unit_losses = calculate_unit_kills($defender_unit_array, $attacker_type_damage, $attack_type,$target_id, $life_deduct  );
if($debug) debug_var('defender_unit_losses', print_r($defender_unit_losses,1));

$secondaryArray = $attack_array;
$attack_array = array();
foreach ($secondaryArray as $key => $percentage) {
	$attack_array[$key] = $percentage*$attackerData[$key.'_owned'][0];
}
if($debug) debug_var('Attack_array', print_r($attack_array,1));

/* create attacker array for calculating losses */
$attacker_unit_array = create_attacker_array($attack_array);
if($debug) debug_var('attacker_unit_array', print_r($attacker_unit_array,1));

/* calculate attacker losses */
$attacker_unit_losses = calculate_unit_kills($attacker_unit_array, $defense_attack_type, 'defend',$target_id,$life_deduct);
if($debug) debug_var('attacker_unit_losses', print_r($attacker_unit_losses,1));

/* calculate attack totals */
$attacker_total_power = 0;
foreach($attacker_type_damage as $type => $attack) {
	$valid_types = array_keys($defender_unit_array);
	//if(in_array($type, $valid_types))
		$attacker_total_power += $attack;
}
if($debug) debug_var('attacker_total_power', $attacker_total_power);
$defender_total_power = 0;
foreach($defense_attack_type2 as $type => $attack) {
	$valid_types = array_keys($attacker_unit_array);
	if(in_array($type, $valid_types))
		$defender_total_power += $attack;
}
if($debug) debug_var('defender_total_power', $defender_total_power);

/* calculate loss totals */
$attacker_loss_totals = calculate_losses($attacker_unit_losses);
if($debug) debug_var('attacker_loss_totals', print_r($attacker_loss_totals,1));
$defender_loss_totals = calculate_losses($defender_unit_losses);
if($debug) debug_var('defender_loss_totals', print_r($defender_loss_totals,1));

/* determine win/loss */
//echo 'Defender total power: '.$defender_total_power.'<br/>';
//echo 'Attacker total power: '.$attacker_total_power.'<br/>';
$attacker_extra_losses = 1;
$defender_loss_decrease = 1;
$defender_unit_loss_decrease = 1;

$defender_buildings_lost = 0;
$lost_building_count = 0;
$lost_unit_count = 0;

foreach($defender_unit_losses as $unit_type => $breakdown) {
	foreach($breakdown as $key => $killed) {
		if ($unit_type == 'bld') {
			$type = 'bld';
			$count_key = $key;
			$killed = round($killed*$defender_loss_decrease);

			$owned_blds = $defenderData[$key][0];

			if($killed > $owned_blds){
				$killed = $owned_blds;
			}

			$lost_building_count+=$killed;
        }
        else {
			$type = 'unit';
			$count_key = $key.'_owned';
			$killed = round($killed*$defender_unit_loss_decrease);

			$owned_units = $defenderData[$key.'_owned'][0];

			if($killed > $owned_units){
				$killed = $owned_units;
			}
			$lost_unit_count+=$killed;
	    }
    }
}

/* determine if attacker is winner or loser of battle */
if($defender_total_power*1.2 <= $attacker_total_power){
	$result = 'success';
	$winner_id = $userId;
	$attacker->updateXP('unit_attack');
	$defender->updateXP('unit_defeat');
}
else{
	$result = 'failure';
	$winner_id = $target_id;
	$attacker->updateXP('defeat_attacking');
	$defender->updateXP('succesful_defense');
}

if ($lost_building_count == 0 && $lost_unit_count == 0) {
	/* attacker did no damage */
	$result       = 'failure';
	$attacker->updateXP('defeat_attacking');
	$defender->updateXP('succesful_defense');
	$land_stolen  = 0;
	$money_stolen = 0;
	$winner_id = $target_id;
	$attacker_extra_losses = 1.35;
	$defender_loss_decrease = 0.35;
	$defender_unit_loss_decrease = 0.5;
}

$defender_units_lost = $defender_unit_NW_lost = 0;

/* translate array structure for display + calculate & deduct losses */
$def_unitslost = array();
foreach($defender_unit_losses as $unit_type => $breakdown) {
	foreach($breakdown as $key => $killed) {
		if ($unit_type == 'bld') {
			$type = 'bld';
			$count_key = $key;

			$killed = round($killed*$defender_loss_decrease);

			//Reduce lost buildings to just 40% if the attack was not successful
			//MEGA 20170531
			if ($result == 'failure') {
				$killed = floor($killed*0.4);
			}

			$owned_blds = $defenderData[$key][0];

			if($killed > $owned_blds){
				$killed = $owned_blds;
			}
			if($debug) debug_var('killed '.$key, $killed);
			$defender_buildings_lost+=$killed;
			$defender_networth_lost+=$killed*$buildings[$key]['price']*($buildings[$key]['networth']/100);
			$defender_building_NW_lost+=$killed*$buildings[$key]['price']*($buildings[$key]['networth']/100);
		}
		else {
			$type = 'unit';
			$count_key = $key.'_owned';

			if($units[$key]['sectype'] == 'bk'){
				$killed = round($killed*0.75*$defender_unit_loss_decrease);
			} else {
				$killed = round($killed*$defender_unit_loss_decrease);
			}
			$owned_units = $defenderData[$key.'_owned'][0];

			if($killed > $owned_units){
				$killed = $owned_units;
			}
			//Reduce lost units to just 40% if the attack was not successful
			//MEGA 20170531
			if ($result == 'failure') {
				$killed = floor($killed*0.4);
			}
			if($debug) debug_var('killed '.$key, $killed);
			$defender_units_lost+=$killed;
			$defender_networth_lost+=$killed*$units[$key]['price']*($units[$key]['networth']/100);
			$defender_unit_NW_lost+=$killed*$units[$key]['price']*($units[$key]['networth']/100);
		}

		if($killed > 0){
			$def_unitslost[] = array('type' => $type, $key => $killed);
		}

		$prev_units = $defenderData[$count_key][0];
		$new_units = max($prev_units - $killed, 0);
		if($debug) $f=1;//debug_update_user($target_id, $count_key, $new_units);
		elseif(!$attacker->isShadowBanned()) update_user_meta($target_id, $count_key, $new_units);
	}
}

$attacker_units_lost = 0;
$attacker_networth_lost = 0;

$attacker_networth_lost+= $tomahawksSent*150;

$att_unitslost = array();
foreach($attacker_unit_losses as $unit_type => $breakdown) {
	if(null==$breakdown) continue;
	foreach($breakdown as $key => $killed) {
		$killed = round($killed*$attacker_extra_losses*$life_deduct);

		$owned_units = $attackerData[$key.'_owned'][0];
        if($killed > $owned_units*$_POST['attackarray'][$key]){
            $killed = round($owned_units*$_POST['attackarray'][$key]);
        }

		$attacker_networth_lost+=$killed*$units[$key]['price']*($units[$key]['networth']/100);

		$attacker_units_lost+=$killed;
		$att_unitslost[] = array('type' => 'unit', $key => $killed);
		$prev_units = $attackerData[$key.'_owned'][0];
		$new_units = max($prev_units - $killed, 0);
		if($debug) debug_var('killed '.$key, $killed);
		if($debug) $f=1;//debug_update_user($userId, $key.'_owned', $new_units);
		else update_user_meta($userId, $key.'_owned', $new_units);
	}
}

/* recalculate built land */
$builtland = 0;
foreach ($buildings as $key => $building) {
	$ownedbuildings = get_user_meta($target_id, $key,true);
	if ($ownedbuildings > 0) {
		$builtland += $ownedbuildings * $LAND_PER_BUILDING;
	}
}
if($debug) debug_update_user($target_id, 'builtland', ceil($builtland));
elseif(!$attacker->isShadowBanned()) update_user_meta($target_id, 'builtland', ceil($builtland));

/* resources stolen */
$land_stolen  = 0;
$money_stolen = 0;

/* if success calculate resources stolen */
if($debug) debug_var('Result', $result);
if($result == 'success'){
	$extraLandKill = 1;
	if ($defender_buildings_lost >= $defender_building_total) {
		$extraLandKill = 2.5;
	}

	$money     = $defenderData['money'][0];
	$land      = $defenderData['land'][0];
	$builtland = $defenderData['builtland'][0];
	$freeland  = $land - $builtland;

	$startingbonus = $attackerData['starting_bonus'][0];
	if($startingbonus == 'offensive') {
	    $land_stolen   = max(ceil($freeland * ($STOLEN_LAND_RATIO*2*$resourceMulti*$aggressive_multi*$extraLandKill) * resource_dice_roll()), 0);
	    $money_stolen  = max(ceil($money * ($STOLEN_MONEY_RATIO*2*$resourceMulti*$extraLandKill) * resource_dice_roll()*$aggressive_multi), 0);
	}
	else {
	    $land_stolen   = max(ceil($freeland * $STOLEN_LAND_RATIO * $resourceMulti * $aggressive_multi * $extraLandKill * resource_dice_roll()), 0);
	    $money_stolen  = max(ceil($money * ($STOLEN_MONEY_RATIO * $resourceMulti*$extraLandKill) * resource_dice_roll()*$aggressive_multi), 0);
	}

	// Jaap, resources stolen based on nw-losses
	$land_stolen = scaled_land_to_clansize($land_stolen, $userId, $target_id, $attacker_networth_lost, $defender_networth_lost);
	$money_stolen = scaled_money_to_clansize($money_stolen, $userId, $target_id, $attacker_networth_lost, $defender_networth_lost);

	$attackermoney = $attackerData['money'][0];
	$attackerland  = $attackerData['land'][0];

	/* take money and land */
	if($debug) debug_update_user($userId, 'money', round($attackermoney + $money_stolen));
	else update_user_meta($userId, 'money', round($attackermoney + $money_stolen));
	if($debug) debug_update_user($userId, 'land', round($attackerland + $land_stolen));
	else update_user_meta($userId, 'land', round($attackerland + $land_stolen));
	if($debug) debug_update_user($target_id, 'money', round($money - $money_stolen));
	elseif(!$attacker->isShadowBanned()) update_user_meta($target_id, 'money', round($money - $money_stolen));
	if($debug) debug_update_user($target_id, 'land', round($land - $land_stolen));
	elseif(!$attacker->isShadowBanned()) update_user_meta($target_id, 'land', round($land - $land_stolen));

	/* add stats */
	// attacker
	$money_gained_combat = $attackerData['money_gained_combat'][0];
	update_user_meta($userId, 'money_gained_combat', $money_gained_combat+$money_stolen);

	$land_gained_combat = $attackerData['land_gained_combat'][0];
	update_user_meta($userId, 'land_gained_combat', $land_gained_combat+$land_stolen);

	if(!$attacker->isShadowBanned()) {
		// defender
		$money_lost_combat = (isset($defenderData['money_lost_combat']) ? $defenderData['money_lost_combat'][0] : 0);
		update_user_meta($target_id, 'money_lost_combat', $money_lost_combat+$money_stolen);

		$land_lost_combat = (isset($defenderData['land_lost_combat']) ? $defenderData['land_lost_combat'][0] : 0);
		update_user_meta($target_id, 'land_lost_combat', $land_lost_combat+$land_stolen);
	}
}

$killed = false;
if ($defender_buildings_lost >= $defender_building_total && !$attacker->isShadowBanned()) {
	if($debug) wtf('kill_player', $defender_buildings_lost, $defender_building_total);
	else $killed = $defender->dies($attacker->get('id'));
}

// not a kill - handle damage
if(!$killed) {
	$defender_networth_lost += $land_stolen * 0.85;
	$defender_networth = $defenderData['networth'][0];
	$defender_new_nw = round($defender_networth - ceil($defender_networth_lost));
}

// calculate clan points
$clan_points = 0;
$unit_points = 0;

if($war_type != 'none' && $result == 'success') {

	$inwarattacks = $attackerData['in_war_attacks'][0];
	update_user_meta($userId, 'in_war_attacks', $inwarattacks+1);

	$def_total_units = 0;
	foreach($defender_unit_array as $type => $unit_stats) {
		$def_total_units += $unit_stats['total_count'];
	}

	$defender_networth = $defenderData['networth'][0];

	$clan_points = calculate_pts($defender_building_NW_lost,$defender_unit_NW_lost,$aggressive_multi);
	if($debug) debug_var('Clan points1', $clan_points);

	/* determine points multiplier due to war */
	$war_multiplier = get_war_multiplier($war_type);
	$clan_points = ceil($clan_points * $war_multiplier);
	//$clan_points = ceil($clan_points * $defend_clan->getWarTypeMultiplier($war_type));

	if($debug) debug_var('Clan points2', $clan_points);

	// Points cap
	$clan_points = min(ceil($clan_points), Settings::get('points_cap'));

	if ($killed == true) {
		/* add stats */
		// attacker
		$kills_made = $attackerData['kills_made'][0];
		update_user_meta($userId, 'kills_made', $kills_made+1);

		// defender
		$times_killed = $defenderData['times_killed'][0];
		update_user_meta($target_id, 'times_killed', $times_killed+1);

		if($war_type == 'mutual') $clan_points = Settings::get('points_kill_mutual');
		elseif($war_type == 'incoming') $clan_points = Settings::get('points_kill_incoming');
		elseif($war_type == 'outgoing') $clan_points = Settings::get('points_kill_outgoing');
	}
	else {
		// Jaap, points based on clansize
		$clan_points = scaled_points_to_clansize($clan_points, $userId, $target_id);
		if($debug) debug_var('Clan points4', $clan_points);
		// Jaap, points based on difference between clanpoints totals
		$clan_points = scaled_points_to_clanpoints($clan_points, $userId, $target_id);
		if($debug) debug_var('Clan points5', $clan_points);
	}
	if($attacker->isShadowBanned()) $clan_points = 0;

	/* add points */
	$attackerClan->addToMeta('clan_points', $clan_points);
	$attackerClan->addToMeta('ua_total', 1);
	$attackerClan->addToMeta('24h_pts', $clan_points);
}

/* add stats */

//attacker
$units_killed = $attackerData['units_killed'][0];
update_user_meta($userId, 'units_killed', $units_killed+$defender_units_lost);

$nw_damage_attacks = $attackerData['nw_damage_attacks'][0];
update_user_meta($userId, 'nw_damage_attacks', $nw_damage_attacks+$defender_networth_lost);

$buildings_killed = $attackerData['buildings_killed'][0];
update_user_meta($userId, 'buildings_killed', $buildings_killed+$defender_buildings_lost);

$defender_points = 0;
if(!$attacker->isShadowBanned()) {
	// defender
	$nw_damage_lost = (isset($defenderData['nw_damage_lost']) ? $defenderData['nw_damage_lost'][0] : 0);
	update_user_meta($target_id, 'nw_damage_lost', $nw_damage_lost+$defender_networth_lost);

	$units_lost = (isset($defenderData['units_lost']) ? $defenderData['units_lost'][0] : 0);
	update_user_meta($target_id, 'units_lost', $units_lost+$defender_units_lost);

	$buildings_lost = (isset($defenderData['buildings_lost']) ? $defenderData['buildings_lost'][0] : 0);
	update_user_meta($target_id, 'buildings_lost', $buildings_lost+$defender_buildings_lost);

	/* Defender clan points */
	if($result == 'failure' && $war_type != 'none'){
		$defender_points = round(1.3 * log($attacker_networth_lost/3.4 / 400));

		//Saw a bug here.. If pts were exactly 5, it would break the logic and award some crazy points
		//MEGA 20170531
		if($defender_points <= 1){
			$defender_points = 1;
		}

		if($defender_points >= 5){
			$defender_points = 5;
		}

		$defPts = (isset($defenderData['user_clan_points']) ? $defenderData['user_clan_points'][0] : 0);
		update_user_meta($target_id,'user_clan_points',$defPts+$defender_points);

		// Update points for current clan
		$userDefPts = (isset($defenderData['current_clan_points']) ? $defenderData['current_clan_points'][0] : 0);
		update_user_meta($target_id, 'current_clan_points', $userDefPts+$defender_points);

		$_def24Hpts = get_post_meta($defend_clan_id, '24h_pts', true);
		update_post_meta($defend_clan_id,'24h_pts',$_def24Hpts+$defender_points);

		$starting_Defpoints = get_post_meta($defend_clan_id,'clan_points',true);
		update_post_meta($defend_clan_id,'clan_points',$starting_Defpoints+$defender_points);
	}
}

if(!$debug) {
if ($result == 'success') {
	/* add statistics for defender and attacker */
	//attacker
	if($war_type != 'none'){
		$succesful_attacks = $attackerData['succesful_attacks'][0];
		update_user_meta($userId, 'succesful_attacks', $succesful_attacks+1);
	}

	//defender
	if(!$attacker->isShadowBanned()) {
		$attacks_lost = (isset($defenderData['attacks_lost']) ? $defenderData['attacks_lost'][0] : 0);
		update_user_meta($target_id, 'attacks_lost', $attacks_lost+1);
	}
	?>
	<script>
		jQuery(document).ready(function() {
			jQuery( "#successsplash" ).show();
			jQuery( "#successsplash" ).delay(750).fadeOut( "slow");
			jQuery('.pageTitle').html('S U C C E S S');
		});
	</script>
	<div class="blockHeader">You won the battle against <?php echo get_user_name($target_id)?>
		<?php if ($killed == true):?>
			<u>and killed this player</u>
		<?php endif;?>
	</div>
<?php } else { ?>
	<script>
		jQuery(document).ready(function() {
			jQuery( "#failsplash" ).show();
			jQuery( "#failsplash" ).delay(750).fadeOut( "slow")
			jQuery('.pageTitle').html('F A I L U R E');
		});
	</script>
	<div class="blockHeader">You lost the battle against <?php echo get_user_name($target_id);?>
		<?php if ($killed == true):?>
			<u>but managed to kill this player</u>
		<?php endif;?>
	</div>
<?php } ?>

<div class="battleReportInfo statCol-1">Battle Report</div>
<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">Money Stolen: $ <?php echo number_format($money_stolen, 0, ',', ' ');?></div>
	<div class="col-md-4 battleReportInfo statCol-3">Land Stolen: <?php echo number_format($land_stolen, 0, ',', ' ');?>m<sup>2</sup></div>
	<div class="col-md-4 battleReportInfo statCol-4">
		<?php if ($war_type != 'none'):?>
			Clan points gained: <?php echo $clan_points;?>
		<?php else:?>
			No clan points gained
		<?php endif;?>
	</div>
</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.56);">
		Your networth decreased: $<?php echo number_format($attacker_networth_lost, 0, ',', ' ');?>
	</div>
	<div class="col-md-8 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.48);">
		Enemy networth decreased: $<?php echo number_format($defender_networth_lost, 0, ',', ' ');?>
	</div>
</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">
		Units Lost: <?php echo $attacker_units_lost; ?>
		</strong><br/>
		<?php
		foreach ($units as $key => $order) {
			foreach ($att_unitslost as $att_unitlost) {
				if (isset($att_unitlost[$key])) {
					if($att_unitlost[$key] > 0){
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . '<br/>';
					}
				}
			}
		}
		?>
		<?php if(($tomahawksSent-$shotdown) > 0):?>
			<br/><?php echo ($tomahawksSent-$shotdown);?> tomahawk<?php echo plural_func($tomahawksSent-$shotdown);?> hit the enemy base<br/>
		<?php endif;?>
		<?php if($shotdown > 0):?>
			<?php echo $shotdown;?> tomahawk<?php echo plural_func($shotdown);?> shot down
		<?php endif;?>
	</div>
	<div class="col-md-4 battleReportInfo statCol-3">
		Units Killed: <?php echo $defender_units_lost; ?></strong><br/>
		<?php
		foreach ($units as $key => $order) {
			foreach ($def_unitslost as $def_unitlost) {
				if (isset($def_unitlost[$key])) {
					if($def_unitlost[$key] > 0){
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . '<br/>';
					}
				}
			}
		}
		?>
	</div>
	<div class="col-md-4 battleReportInfo statCol-4">
		Buildings destroyed: <?php echo $defender_buildings_lost; ?></strong><br/>
		<?php
		foreach ($buildings as $key => $order) {
			foreach ($def_unitslost as $def_unitlost) {
				if (isset($def_unitlost[$key])) {
					if ($def_unitlost['type'] == 'bld') {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . '<br/>';
					}
				}
			}
		}
		?>
	</div>
</div>

<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Strike Again</div>

<?php
/* create event post */
$args = array(
	'post_title'    => 'Attack made by '.$userId.' Defender: '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);

$new_event_id = wp_insert_post( $args );
update_field('defender_lost', $def_unitslost, $new_event_id);
update_field('attacker_lost', $att_unitslost, $new_event_id);
update_field('land_lost', $land_stolen, $new_event_id);
update_field('money_lost', $money_stolen, $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);
update_field('total_buildings_lost',$defender_buildings_lost, $new_event_id);
update_field('def_total_units_lost',$defender_units_lost, $new_event_id);
update_field('att_total_units_lost',$attacker_units_lost, $new_event_id);
update_field('defender_id',$target_id, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);
update_field('attacktype',$attack_type, $new_event_id);
update_field('outcome',$result, $new_event_id);
update_field('maintarget', $maintarget, $new_event_id);
update_field('attackmode', $attackmode, $new_event_id);
update_field('moralecost', $attack_cost_morale, $new_event_id);

update_field('tomahawk_hit', $tomahawksSent-$shotdown, $new_event_id);
update_field('tomahawk_down', $shotdown, $new_event_id);

update_field('war_status', $war_type, $new_event_id);
update_field('defender_points',$defender_points, $new_event_id);

update_field('nw_damage_defender',$defender_networth_lost, $new_event_id);
update_field('nw_damage_attacker',$attacker_networth_lost, $new_event_id);

update_field('defender_clan_id',$defend_clan_id, $new_event_id);
update_field('attacker_clan_id',$attack_clan_id, $new_event_id);

if(!$attacker->isShadowBanned()) {
	/* Add globals to defender */
	$clan = $defenderData['clan_id_user'][0];
	$clan_members = get_post_meta($clan,'clan_members');

	if(!empty($clan) || $clan != 0){
		// Update attacks for current clan
		$attRec = (isset($defenderData['attacks_rec_current']) ? $defenderData['attacks_rec_current'][0] : 0);
		update_user_meta($target_id, 'attacks_rec_current', $attRec+1);
		foreach ($clan_members[0] as $member) {
			$globals = get_user_meta($member, 'new_global_events', true);
			update_user_meta($member, 'new_global_events', $globals+1);
		}
	}
}

/* Add globals to attacker */
$clan_att = $attackerData['clan_id_user'][0];
$clan_members_att = get_post_meta($clan_att,'clan_members');
if(!empty($clan_att) || $clan_att != 0){
	// Update attacks for current clan
	$attMade = $attackerData['attacks_made_current'][0];
	update_user_meta($userId, 'attacks_made_current', $attMade+1);
	foreach ($clan_members_att[0] as $member_att) {
		$globals = get_user_meta($member_att, 'new_global_events', true);
		update_user_meta($member_att, 'new_global_events', $globals+1);
	}
}

update_field('clan_points', $clan_points, $new_event_id);

if($killed == true){
	kill_event($userId,$target_id,$result,$defend_clan_id,$attack_clan_id);
	update_post_meta($new_event_id, 'status_defender', 'death');
	$attacker->updateXP('kill');
	$defender->updateXP('death');
}

if(!$attacker->isShadowBanned()) {
	/* update defender land and trigger event */
	$event_count = $defenderData['new_events'][0];
	update_user_meta($target_id, 'new_events', $event_count + 1);
}

/* update attacker points */
$user_pts = (isset($attackerData['user_clan_points']) ? $attackerData['user_clan_points'][0] : 0);
update_user_meta($userId,'user_clan_points',$user_pts+$clan_points);

// Update attacker points for current clan
$userAttPts = $attackerData['current_clan_points'][0];
update_user_meta($userId, 'current_clan_points', $userAttPts+$clan_points);

$last_ids = $attackerData['last_attacked'][0];
update_user_meta($userId, 'last_attacked', $target_id.','.$last_ids);

$war_array_def = maybe_unserialize(get_post_meta($defend_clan_id, 'war_array', true));
if(!is_array($war_array_def)){
	$war_array_def = array();
}

foreach(array('attacks_received','nw_dmg_rec','bds_lost','units_lost','land_lost','money_lost','clan_points') as $key) {
	if(!isset($war_array_def[$warstatID][$key])) $war_array_def[$warstatID][$key] = 0;
}
$war_array_def[$warstatID]['attacks_received'] += 1;
$war_array_def[$warstatID]['nw_dmg_rec'] += $defender_networth_lost;
$war_array_def[$warstatID]['bds_lost'] += $defender_buildings_lost;
$war_array_def[$warstatID]['units_lost'] += $defender_units_lost;
$war_array_def[$warstatID]['land_lost'] += $land_stolen;
$war_array_def[$warstatID]['money_lost'] += $money_stolen;
$war_array_def[$warstatID]['clan_points'] += $defender_points;

if($killed == true){
	$war_array_def[$warstatID]['deaths'] += 1;
}

if($result == 'failure'){
	$war_array_def[$warstatID]['successfull_def'] += 1;
}

update_post_meta($defend_clan_id, 'war_array', maybe_serialize($war_array_def));

// Updating stats for war
$war_array_att = maybe_unserialize(get_post_meta($attack_clan_id, 'war_array', true));

if(!is_array($war_array_att)){
	$war_array_att = array();
}
if(!isset($war_array_att[$warstatID]['money_gained'])) $war_array_att[$warstatID]['money_gained'] = 0;
if(!isset($war_array_att[$warstatID]['clan_points'])) $war_array_att[$warstatID]['clan_points'] = 0;

foreach(array('attacks_made','successfull_att','nw_dmg_done','highest_nw_dmg','highest_dmg_id',
	'bds_killed','units_killed','land_gained') as $key) {
	if(!isset($war_array_att[$warstatID][$key])) $war_array_att[$warstatID][$key] = 0;
}
$war_array_att[$warstatID]['attacks_made'] += 1;
if($result == 'success'){
	$war_array_att[$warstatID]['successfull_att'] += 1;
}

$war_array_att[$warstatID]['nw_dmg_done'] += $defender_networth_lost;

if($defender_networth_lost > $war_array_att[$warstatID]['highest_nw_dmg']){
	$war_array_att[$warstatID]['highest_nw_dmg'] = $defender_networth_lost;
	$war_array_att[$warstatID]['highest_dmg_id'] = $new_event_id;
}

$war_array_att[$warstatID]['bds_killed'] += $defender_buildings_lost;
$war_array_att[$warstatID]['units_killed'] += $defender_units_lost;
$war_array_att[$warstatID]['land_gained'] += $land_stolen;
$war_array_att[$warstatID]['money_gained'] += $money_stolen;
$war_array_att[$warstatID]['clan_points'] += $clan_points;

if($killed == true){
	$war_array_att[$warstatID]['kills'] += 1;
}

update_post_meta($attack_clan_id, 'war_array', $war_array_att);
}
