<?php
/* utility functions for attack engine reuse */

/*
	determine war type
	Params:
		$attack_clan_id : Clan ID of attacking player
		$defend_clan_id : Clan ID of defending player
	Returns:
		war_type : 'mutual', 'outgoing', 'incoming', 'none'
*/
include('constants.php');


function calculate_pts($bld_damage, $unit_damage, $aggressive_multi) {
    global $debug;
    $bld_pts = ($bld_damage > 0 ? log($bld_damage)*1.3 * sqrt($bld_damage/10000) : 0);
    $unit_pts = ($unit_damage > 0 ? log($unit_damage)/1.9 * sqrt($unit_damage/1000) : 0);
    $pts = $bld_pts + $unit_pts;
    if($bld_pts > 0 && $unit_pts > 0) { // UBK needs a little adjustment
        $pts = ($bld_pts*0.7) + ($unit_pts*0.8);
    }
    if ($aggressive_multi > 1) { // But then again, aggressive is more points
        $pts = $pts * 1.2;
    }
    if(!Round::isDev() && !Round::isTest()) { // Don't make things too predictable
        $pts = $pts * (mt_rand(96, 108) / 100);
    }
    $pts = min(ceil($pts), Settings::get('points_cap')); // Round to higher number, if more than max, set to max
    if($debug) debug_var('calculate_pts', $pts);
    return $pts;
}


function get_war_type($attack_clan_id, $defend_clan_id) {
    $attClan = Clan::make($attack_clan_id);
    return (!!$attClan ? $attClan->getWarType($defend_clan_id) : 'none');
}


/*
	get_war_multiplier
	Params:
		$war_type : type of war ('mutual', 'outgoing', 'incoming', 'none')
	Return:
		war_multiplier : retrieved from constants
*/
function get_war_multiplier($war_type) {
    $warTypeMulti = Settings::get('war_type_multi');
    return (isset($warTypeMulti[$war_type]) ? $warTypeMulti[$war_type] : 0);
}


/*
	target_in_range
	Params:
		$attack_type : type of attack
		$attack_nw : networth of attacker
		$defend_nw : networth of defender
		$war_type : type of war
	Return:
		in_range : true/false
*/
function target_in_range($attack_type, $attack_nw, $defend_nw, $war_type) {
    include('constants.php');

    $in_range = false;
    $range_min = $attack_nw / $ATTACK_RANGE_MULT;
    $range_max = $attack_nw * $ATTACK_RANGE_MULT;
    $in_range = ($defend_nw >= $range_min && $defend_nw <= $range_max);

    /* always in range for spying or mutuals */
    if ($war_type == 'mutual' || $attack_type == 'spy') {
        $in_range = true;
    }

    return $in_range;
}


/*
	get_attack_cost
	Params:
		$attack_type : type of attack
		$attack_nw : networth of attacker
		$defend_nw : networth of defender
	Return:
		cost_arr : array with turn and morale costs
		{
			'turns' : <value>
			'morale' : <value>
		}
*/

// Function for calculating morale cost of the attack.
function get_attack_cost_morale($attack_type, $attack_nw, $defend_nw) {
    $targetIsBigger = $attack_nw < $defend_nw;
    switch (strtolower($attack_type)) {
        case 'missile':
            return Settings::get($targetIsBigger ? 'morale_missile_tgt_above' : 'morale_missile_tgt_below');
        case 'thief':
            return Settings::get('thief_morale_cost');
        case 'saboteur':
            return Settings::get('saboteur_morale_cost');
        case 'spy':
            return Settings::get('spy_morale_cost');
        case 'sniper':
            return Settings::get('sniper_morale_cost');
        case 'air_sea':
        case 'regular':
        case 'ground':
            return Settings::get($targetIsBigger ? 'morale_attack_tgt_above' : 'morale_attack_tgt_below');
    }
};

function get_attack_cost_turns($attack_type) {
    if (strtolower($attack_type) == 'thief') return Settings::get('turns_thief');
    if (strtolower($attack_type) == 'saboteur') return Settings::get('turns_saboteur');
    if (strtolower($attack_type) == 'spy') return Settings::get('turns_spy');
    if (strtolower($attack_type) == 'missile') return Settings::get('turns_missile');
    if (in_array(strtolower($attack_type), array('air_sea','regular','ground'))) return Settings::get('turns_attack');
    return 0;
};

/*
	create_defender_array
	Params:
		$target_id : player id for target
		$type_array : array of types to build for
	Return:
		stat_array[$type][$key,'total_life','total_count']['life','count']
*/
function create_defender_array($target_id, $type_array) {

    global $debug;
    $units = Units::get();
    $buildings = Buildings::get(); // @todo: get it from province to automatically get life with ppe-research and/or defensive startbonus
    include('constants.php');

    // Check for starting bonus
    $startingbonus = get_user_meta($target_id, 'starting_bonus',true);
    $defensive_multi = 1;
    $defensive_multi_units = 1;
    if($startingbonus == 'defensive'){
        $defensive_multi = 1.25;
        $defensive_multi_units = 1.2;
    }

    // Check for ppe
    $PPE_multi = 1;
    $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency', true);
    if ($PPE_level == 1) $PPE_multi = 1.5;

    $target_data = get_user_meta($target_id);

    $stat_array = array();
    $total_life = 0;
    $total_count = 0;

    /* calculate building stats */
    $total_bld_life = 0;
    $total_bld_count = 0;
    foreach($buildings as $key => $data) {
        $build_count = $target_data[$key][0];
        if ($build_count > 0) {
            $build_life = (in_array($key,array('powerplant','advancedpowerplant')) ? $data['life']*$PPE_multi : $data['life']);
            $bld_sum_life = $build_count * ($build_life * $defensive_multi);
            $stat_array['bld'][$key]['life'] = $bld_sum_life;
            $stat_array['bld'][$key]['count'] = $build_count;

            $total_bld_life += $bld_sum_life;
            $total_bld_count += $build_count;
            if($debug) debug_var('cda '.$key, ($build_life*$defensive_multi));
        }
    }
    $stat_array['bld']['total_life'] = $total_bld_life;
    $stat_array['bld']['total_count'] = $total_bld_count;

    /* calculate unit stats */
    $total_unit_life = array(
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    $total_unit_count = array(
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    foreach($units as $key => $data) {
        $unit_type = $data['type'];
        $unit_count = $target_data[$key."_owned"][0];
        if ($unit_count > 0 && $data['sectype'] != 'special') {
            $unit_life = $data['life'];
            $unit_sum_life = $unit_life * $unit_count;
            $stat_array[$unit_type][$key]['life'] = $unit_sum_life;
            $stat_array[$unit_type][$key]['count'] = $unit_count;

            $total_unit_life[$unit_type] += $unit_sum_life;
            $total_unit_count[$unit_type] += $unit_count;
        }
    }

    /* set unit totals in $stat_array array */
    foreach ($total_unit_count as $type => $count) {
        if ($count > 0) {
            $stat_array[$type]['total_count'] = $count;
            $stat_array[$type]['total_life'] = $total_unit_life[$type];
        }
    }

    return $stat_array;
}


/*
	create_attacker_array
	Params:
		$attack_array : array of types to build for
	Return:
		attacker_array[$type][$key,'total_life','total_count']['life','count']
*/
function create_attacker_array($attack_array) {
    $units = Units::get();
    $stat_array = array();

    /* calculate unit stats */
    $total_unit_life = array(
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    $total_unit_count = array(
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    foreach($attack_array as $key => $unit_count) {
	    if($key != 'tomahawk'){
       		$unit_type = $units[$key]['type'];

	   		if ($unit_count > 0) {
            	$unit_life = $units[$key]['life'];
				$unit_sum_life = $unit_life * $unit_count;
				$stat_array[$unit_type][$key]['life'] = $unit_sum_life;
				$stat_array[$unit_type][$key]['count'] = $unit_count;

				$total_unit_life[$unit_type] += $unit_sum_life;
				$total_unit_count[$unit_type] += $unit_count;
			}
        }
    }

    /* set unit totals in $stat_array array */
    foreach ($total_unit_count as $type => $count) {
        if ($count > 0) {
            $stat_array[$type]['total_count'] = $count;
            $stat_array[$type]['total_life'] = $total_unit_life[$type];
        }
    }

    return $stat_array;
}


/*
	calculate_defense_by_type
	Params:
		$target_id : user id of target
	Return:
		$defense_array : array of defensive power by type
*/
$overall_bld_total = 0;
function calculate_defense_by_type($target_id, $power_on, $attackerRemoveArray) {

    global $overall_bld_total;
    global $debug;
    $units = Units::get();
    $buildings = Buildings::get(); // @todo: get it from province to automatically get life with ppe-research and/or defensive startbonus
    include('constants.php');

    // Check for starting bonus
    $startingbonus = get_user_meta($target_id, 'starting_bonus',true);
    $defensive_multi = 1;
    $defensive_multi_units = 1;
    if($startingbonus == 'defensive'){
        $defensive_multi = 1.25;
        $defensive_multi_units = 1.2;
    }

    // Check for ppe
    $PPE_multi = 1;
    $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency', true);
    if ($PPE_level == 1) $PPE_multi = 1.5;

    /* initialize attack array with 0 for all */
    $attack_array = array(
        'bld' => 0,
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    /* initialize life array with all 0 */
    $life_array = array(
        'bld' => 0,
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );

    /* get values for buildings */
    foreach($buildings as $key => $data) {
        $bld_count = get_user_meta($target_id, $key)[0];

        /* next building if none */
        if ($bld_count < 1)
            continue;
        $power = get_user_meta($target_id, 'power', true);


        /* if valid DB add to attack array */
        if ($power < 100 && in_array($key, $DEFENSIVE_BUILDINGS)) {
            $target_type = $buildings[$key]['attacks'][0];
            $attack_power = $buildings[$key]['attack'];

            /* moved to kill code */
            $dice_roll = attack_dice_roll();
            $db_atk_power = $bld_count * $attack_power * 1.904;
            $attack_array[$target_type] += $db_atk_power;
        }

        /* add to life for all */
        $bld_life = (in_array($key,array('powerplant','advancedpowerplant')) ? $buildings[$key]['life'] * $PPE_multi : $buildings[$key]['life']);
        $bld_life_total = ($bld_life * $defensive_multi) * $bld_count;
        $life_array['bld'] += $bld_life_total;

        //Store the value of this building count to overall total
        $overall_bld_total +=$bld_count;
        if($debug) debug_var('cdbt '.$key, ($bld_life*$defensive_multi) );
    }

    /* get defense from units */
    foreach($units as $key => $data) {
        $unit_count = get_user_meta($target_id, $key.'_owned')[0];

        /* if defender has none of this unit continue */
        if ($unit_count < 1)
            continue;
            
        /* do not incorporate special units */
		if($units[$key]['sectype'] == 'special')
			continue;
		
        /* calculate attack power per type */
        $unit_def_types = $units[$key]['defends'];

        /* Unset types not used in attack by attacker */
        $unit_def_types = array_diff($unit_def_types, $attackerRemoveArray);

        $unit_def_count = count($unit_def_types);

        /* no defense - exit */
        if ($unit_def_count == 0)
            continue;

        /* no use calculating if the unit can't defend */
        if ($unit_def_count < 1)
            continue;

        $dice_roll = attack_dice_roll();
        $unit_atk_power = $data['attack'];
        $atk_power = $unit_atk_power * $unit_count * $dice_roll;
        $divided_atk_power = $atk_power / $unit_def_count;

        foreach($unit_def_types as $type) {
            if(!isset($attack_array[$type])) $attack_array[$type] = 0;
            $attack_array[$type] += $divided_atk_power;
        }

        /* calculate life per type */
        $unit_life = $units[$key]['life'];
        $unit_life_total = ($unit_life * $defensive_multi_units) * $unit_count;
        $unit_type = $units[$key]['type'];
        if($debug) debug_var('cbdt2 '.$key, ($unit_life * $defensive_multi_units));
        $life_array[$unit_type] += $unit_life_total;
    }
    $defense_array['life'] = $life_array;
    $defense_array['attack'] = $attack_array;

    return $defense_array;
}

function calculate_defense_by_type2($target_id, $power_on, $attackerRemoveArray) {
    global $debug;
    $units = Units::get();
    $buildings = Buildings::get(); // @todo: get it from province to automatically get life with ppe-research and/or defensive startbonus
    include('constants.php');

    // Check for starting bonus
    $startingbonus = get_user_meta($target_id, 'starting_bonus',true);
    $defensive_multi = 1;
    $defensive_multi_units = 1;
    if($startingbonus == 'defensive'){
        $defensive_multi = 1.25;
        $defensive_multi_units = 1.2;
    }

    // Check for ppe
    $PPE_multi = 1;
    $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency', true);
    if ($PPE_level == 1) $PPE_multi = 1.5;

    /* initialize attack array with 0 for all */
    $attack_array = array(
        'bld' => 0,
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );
    /* initialize life array with all 0 */
    $life_array = array(
        'bld' => 0,
        'sea' => 0,
        'air' => 0,
        'veh' => 0,
        'inf' => 0
    );

    /* get values for buildings */
    foreach($buildings as $key => $data) {
        $bld_count = get_user_meta($target_id, $key)[0];

        /* next building if none */
        if ($bld_count < 1)
            continue;
        $power = get_user_meta($target_id, 'power', true);

        /* if valid DB add to attack array */
        if ($power < 100 && in_array($key, $DEFENSIVE_BUILDINGS)) {
            $target_type = $buildings[$key]['attacks'][0];
            $attack_power = $buildings[$key]['attack'];

            /* moved to kill code */
            $dice_roll = attack_dice_roll();
            $db_atk_power = $bld_count * $attack_power;
            $attack_array[$target_type] += $db_atk_power;
        }

        /* add to life for all */
        $bld_life = (in_array($key,array('powerplant','advancedpowerplant')) ? $buildings[$key]['life'] * $PPE_multi : $buildings[$key]['life']);
        $bld_life_total = ($bld_life * $defensive_multi) * $bld_count;
        $life_array['bld'] += $bld_life_total;
        if($debug) debug_var('cbdt2 '.$key, ($bld_life* $defensive_multi));
    }

    /* get defense from units */
    foreach($units as $key => $data) {
        $unit_count = get_user_meta($target_id, $key.'_owned')[0];

        /* if defender has none of this unit continue */
        if ($unit_count < 1)
            continue;
		if($units[$key]['sectype'] == 'special')
			continue;
			
        /* calculate attack power per type */
        $unit_def_types = $units[$key]['defends'];

        /* Unset types not used in attack by attacker */
        $unit_def_types = array_diff($unit_def_types, $attackerRemoveArray);

        $unit_def_count = count($unit_def_types);

        /* no defense - exit */
        if ($unit_def_count == 0)
            continue;

        /* no use calculating if the unit can't defend */
        if ($unit_def_count < 1)
            continue;

        $dice_roll = attack_dice_roll();
        $unit_atk_power = $data['attack'];
        $atk_power = $unit_atk_power * $unit_count * $dice_roll;
        $divided_atk_power = $atk_power / $unit_def_count;

        foreach($unit_def_types as $type) {
            if(!isset($attack_array[$type])) $attack_array[$type] = 0;
            $attack_array[$type] += $divided_atk_power;
        }

        /* calculate life per type */
        $unit_life = $units[$key]['life'];
        $unit_life_total = ($unit_life * $defensive_multi_units) * $unit_count;
        $unit_type = $units[$key]['type'];
        if($debug) debug_var('cbdt2 '.$key, ($unit_life * $defensive_multi_units));

        $life_array[$unit_type] += $unit_life_total;
    }
    $defense_array['life'] = $life_array;
    $defense_array['attack'] = $attack_array;

    return $defense_array;
}


/*
	calculate_power
	Params:
		$target_id : user id of target
	Retun:
		$power_usage : % power used
*/
function calculate_power($target_id) {

    $buildings = Buildings::get();  // @todo: get it from province to automatically get life with ppe-research

    /* check if target has PPE researched */
    $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency')[0];
    $PPE_multi = 1;
    if($PPE_level == 1){$PPE_multi = 1.5;}

    $used_power 		= 0;
    $power_production 	= 0;
    foreach($buildings as $key => $building){
        $buildings_owned = get_user_meta($target_id, $key);

        $power_production+=$building['powerprod']*$buildings_owned[0];
        $used_power+=$building['power']*$buildings_owned[0];
    }
    if ($power_production > 0)
        return ($used_power / ($power_production*$PPE_multi));
    else
        return 1000;
}

/*
	attack_dice_roll
	Params:
		<none>
	Return:
		$dice_modifier
*/
function attack_dice_roll() {
    include('constants.php');
    $gameType = get_field('game_type','option');
    if(in_array($gameType, array('Development','Test'))) return $UNIT_DICEROLL_DAMAGE_MIN / 100;
    return rand($UNIT_DICEROLL_DAMAGE_MIN, $UNIT_DICEROLL_DAMAGE_MAX) / 100;
}


/*
	resource_dice_roll
	Params:
		<none>
	Return
		$dice_modifier
*/
function resource_dice_roll() {
    include('constants.php');
    $gameType = get_field('game_type','option');
    if(in_array($gameType, array('Development','Test'))) return $RESOURCE_DICEROLL_MIN / 100;
    return rand($RESOURCE_DICEROLL_MIN, $RESOURCE_DICEROLL_MAX) / 100;
}


/*
	calculate_unit_death
	Params:
		$target_data : user data for target player
		$defender_damage : damage taken per type
	Return:
		$damage_array['total_damage',$type][$key] = value
*/
function calculate_unit_kills($unit_array, $attacker_type_power, $attack_type,$target_id,$life_deduct) {
    global $debug;
    global $userId;
    global $maintarget;
    $units = Units::get();
    $buildings = Buildings::get(); // @todo: get it from province to automatically get life with ppe-research and/or defensive startbonus
    include('constants.php');

    // Check for starting bonus
    $startingbonus = get_user_meta($target_id, 'starting_bonus',true);
    $defensive_multi = 1;
    $defensive_multi_units = 1;
    if($startingbonus == 'defensive' && $attack_type != 'defend'){ // only defending units have a multi
        $defensive_multi = 1.25;
        $defensive_multi_units = 1.2;
    }

    // Check for ppe (not logged in user, that's in building_array)
    $PPE_multi = 1;
    $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency', true);
    if ($PPE_level == 1 && $target_id != $userId) $PPE_multi = 1.5;

    $losses = array();

    foreach($unit_array as $type => $type_stats) {

        $attack_power = 0;

        if (array_key_exists($type, $attacker_type_power))
            $attack_power = $attacker_type_power[$type];

        /* ensure we can attack this type */
        if ($attack_power < 1) continue;

        /* get total life for this type */
        $total_units = $unit_array[$type]['total_count'];

        foreach($type_stats as $unit_key => $unit_stats) {

            /* ignore totals */
            if ($unit_key == 'total_life' || $unit_key == 'total_count') continue;

            /* get count for this unit */
            $unit_count = $unit_stats['count'];

            /* determine portion of attack power dedicated to this unit */
            $power_ratio = $unit_count / $total_units;
            $distributed_power = $attack_power * $power_ratio;

            /* dice roll to pseudo randomize */
            $dice_roll_modifier = attack_dice_roll();
            $distributed_power = $distributed_power * $dice_roll_modifier;

            /* now calculate kill count */
            $dmg_reduction = 6;
            if ($type == 'bld') {
                if(in_array($unit_key, array('powerplant','advancedpowerplant'))) {
                    $buildings[$unit_key]['life'] =  $buildings[$unit_key]['life'] * $PPE_multi;
                }
                $unit_life = $buildings[$unit_key]['life'] * $defensive_multi;

                if($maintarget != 'none') {
                    if($buildings[$unit_key]['targetname'] == $maintarget) $multi = Settings::get('maintarget_target_multi');
                    else $multi = Settings::get('maintarget_notarget_multi');
                    $unit_life = $unit_life * $multi;
                }

                if($debug) debug_var($unit_key, $unit_life);

                $dmg_reduction = Settings::get('damage_reduction_building');
            }
            else {
                $unit_life = $units[$unit_key]['life'] * $defensive_multi_units;
                $dmg_reduction = Settings::get('damage_reduction_unit');
            }

            /* reduce damage by factor determined in constants */
            $effective_atk_power = $distributed_power / $dmg_reduction;

            /* MEGA fix effective attack power for aggro so it ACTUALLY does something haha 20170929 */
            if ($_POST['attackmode'] == 'aggressive') {
                $effective_atk_power = $effective_atk_power*1.2;
            }

            /* calculate kills as power/life */
            $units_killed = round($effective_atk_power / $unit_life);

            /* can't kill more than they have */
            $units_killed = min($units_killed, $unit_count);

            if ($units_killed > 0)
                $losses[$type][$unit_key] = $units_killed;
        }
    }
    return $losses;
}

/*
	kill_player
	Params:
		$uder_id : user id of target
	Return:
		void
*/
function kill_player($user_id) {
    update_user_meta($user_id, 'status', 'dead');
    update_user_meta($user_id, 'networth', 0);
    update_user_meta($user_id, 'land', 0);
    update_user_meta($user_id, 'total_deposits', 0);
}


/*
	calculate_networth_damage
	Params:
		$damage_array
	Returns:
		$networth_damage
*/
function calculate_losses($damage_array) {
    $units = Units::get();
    $buildings = Buildings::get(); // Using original values, not discounted from PPE-research or Defensive startbonus?

    $losses = array();
    $networth_damage = 0;
    $buildings_lost = 0;
    $units_lost = 0;
    foreach($damage_array as $type => $loss_array) {
        if($type == 'total_power') continue;

        foreach($loss_array as $key => $count) {
            if ($type == 'bld') {
                $net_ratio = $buildings[$key]['networth'] / 100;
                $cost = $buildings[$key]['price'];
                $networth = $net_ratio * $cost;
                $buildings_lost += $count;
            }
            else {
                $net_ratio = $units[$key]['networth'] / 100;
                $cost = $units[$key]['price'];
                $networth = $net_ratio * $cost;
                $units_lost += $count;
            }
            $networth_damage += $networth * $count;
        }
    }
    $losses['networth'] = $networth_damage;
    $losses['units'] = $units_lost;
    $losses['buildings'] = $buildings_lost;
    return $losses;
}


function kill_event($attackerId,$defenderId,$result,$defend_clan_id,$attack_clan_id) {
    $timestamp = current_time('timestamp');

    $args = array(
        'post_title'    => 'Kill made by '.$attackerId.' Defender: '.$defenderId,
        'post_status'   => 'publish',
        'post_type'		=> 'event_local',
        'post_author'   => $attackerId
    );

    $new_event_id = wp_insert_post( $args );

    update_field('time_attacked',$timestamp, $new_event_id);

    update_field('defender_id',$defenderId, $new_event_id);
    update_field('attacker_id',$attackerId, $new_event_id);

    update_field('winner_id',$attackerId, $new_event_id);
    update_field('attacktype','killed', $new_event_id);
    update_field('outcome',$result, $new_event_id);


    update_field('defender_clan_id',$defend_clan_id, $new_event_id);
    update_field('attacker_clan_id',$attack_clan_id, $new_event_id);
}

/**
 * Jaap: Attack power scaled to number of out-of-war attacks within X days between two provinces, where first Y aren't counted,
 * only applied outside of war
 */
function scaled_power_pvp($power, $attacker_ID, $defender_ID) {
    /*$out_of_war_attacks = count(get_posts(
        array('numberposts'	=> -1, 'post_type' => 'event_local', 'author' => $attacker_ID, 'meta_query' => array(
            'relation' => 'AND',
            array('key'	=> 'defender_id', 'value' => $defender_ID, 'compare' => '='),
            array('key'	=> 'war_status', 'value' => 'none', 'compare' => '='),
            array('key'	=> 'time_attacked', 'value'	=> strtotime('-5 day'), 'compare' => '>', 'type' => 'numeric'),
        ))
    ));
    $power = $power * (1 / max(1, $out_of_war_attacks - 5) );*/
    return $power;
}

/**
 * Clan points scaled to the difference between the points of two clans.
 * An attacking clan with higher points than defending clan will receive less points
 */
function scaled_points_to_clanpoints($clan_points, $attacker_ID, $defender_ID) {
    $attacker = Province::make($attacker_ID);
    $defender = Province::make($defender_ID);
    $attClan = $attacker->getClan();
    if(!$attClan) return $clan_points;
    $multi = $attClan->getClanTotalPointsMultiplier($defender->getClanId());// Returns percentage
    $clan_points = $clan_points * ($multi/100);
    return min(ceil($clan_points), Settings::get('points_cap'));
}

/**
 * Clanpoint gain reduction based on clanmembersize difference
 */
function scaled_points_to_clansize($clan_points, $attacker_ID, $defender_ID) {
    $attacker = Province::make($attacker_ID);
    $defender = Province::make($defender_ID);
    $attClan = $attacker->getClan();
    if(!$attClan) return $clan_points;
    $multi = $attClan->getClanSizePointsMultiplier($defender->getClanId());
    $clan_points = $clan_points * ($multi/100);
    return min(ceil($clan_points), Settings::get('points_cap'));
}

/**
 * Stolen land reduction based on nw loss difference
 */
function scaled_land_to_clansize($land_stolen, $attacker_ID, $defender_ID, $attacker_networth_lost, $defender_networth_lost) {
    $land_stolen = $land_stolen / max(1, (($attacker_networth_lost*0.75) / $defender_networth_lost));
    return $land_stolen;
}

/**
 * Stolen money reduction based on nw loss difference
 */
function scaled_money_to_clansize($money_stolen, $attacker_ID, $defender_ID, $attacker_networth_lost, $defender_networth_lost) {
    $money_stolen = $money_stolen / max(1, (($attacker_networth_lost*0.75) / $defender_networth_lost));
    return $money_stolen;
}

/**
 * Building damage reduction based on clan member size difference
 * Only used for buildings for now
 */
function scaled_damage_to_clansize($damage, $attacker_ID, $defender_ID) {
    $attacker = Province::make($attacker_ID);
    $defender = Province::make($defender_ID);
    $attClan = $attacker->getClan();
    if(!$attClan) return $damage;
    $multi = $attClan->getClanSizeDamageMultiplier($defender->getClanId());
    $damage = $damage * ($multi/100);
    return $damage;
}