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


function calculate_pts($unit_damage, $bld_damage, $aggressive_multi) {
    //MEGA 2017-07-18

    global $POINTS_CAP;
    if ($unit_damage == 0) {
        //As you cant sqrt 0
        $unit_damage = 0.01;
    }
    if ($bld_damage == 0) {
        //As you cant sqrt 0
        $bld_damage = 0.01;
    }

    //Because log(10)+log(10) is more than log(20), we need to merge the damage from uk and bk at the outset!
	$unit_damage = $unit_damage*1.2; // Increase unit damage by 20% to even out the 20% increase in life
    $damage = $bld_damage + $unit_damage;

    //MEGA reduce bld damage multiplier for damage attacks 20180219
    if ($damage < 3000) {
        $bld_damage = $bld_damage * 0.9;
        $unit_damage = $unit_damage * 1.1;
    }
    else if ($damage < 5000) {
        $bld_damage = $bld_damage * 0.95;
        $unit_damage = $unit_damage * 1.05;

    }
    //End MEGA

    $damage = $bld_damage + $unit_damage;

    if ($damage < 1101) {
        $multiplier = 0.4;
        // To award 1 pt, not 2, for very low attacks. Slowly tiers up to when the normal numbers take over.
    }
    elseif ($damage < 1501) {
        $multiplier = 0.58;
    }
    elseif ($damage < 1901) {
        $multiplier = 0.78;
    }
    else {
        $multiplier = 1.17;
    }

    $random_factor = (mt_rand(96,108)/100); //Set randomness

    $pts_gained =  ((((sqrt($damage)*log($damage))/100)*$multiplier)*$random_factor);
    if ($aggressive_multi > 1) {
        $pts_gained = $pts_gained *1.2;
    }

    //MEGA new change - scale damage at high NW down by small amounts
    //END
    $pts = ceil ($pts_gained);	 //Round to higher number
    if($pts > $POINTS_CAP) {
        $pts = $POINTS_CAP;  // If more than max, set to max!
    }

    return $pts;

}


function get_war_type($attack_clan_id, $defend_clan_id) {
    /* check for clan war to determine points multiplier */
    $outgoing_war = false;
    $incoming_war = false;
    $mutual_war = false;
    $war_type = "none";

    /* if both players are in a clan */
    if($defend_clan_id != 0 && $attack_clan_id != 0) {
        /* check if attacker declared on defender */
        $outgoing_wars = get_posts(
            array(
                'numberposts'	=> -1,
                'post_type'		=> 'wars',
                'meta_query'	=> array(
                    'relation'		=> 'AND',
                    array(
                        'key'	 	=> 'declared_on',
                        'value'	  	=> $defend_clan_id,
                        'compare' 	=> '=',
                    ),
                    array(
                        'key'	 	=> 'declared_by',
                        'value'	  	=> $attack_clan_id,
                        'compare' 	=> '=',
                    ),
                ),
            )
        );

        if(count($outgoing_wars) > 0) {
            $outgoing_war = true;
        }

        /* check if defender has declared on attacker */
        $incoming_wars = get_posts(
            array(
                'numberposts'	=> -1,
                'post_type'		=> 'wars',
                'meta_query'	=> array(
                    'relation'		=> 'AND',
                    array(
                        'key'	 	=> 'declared_on',
                        'value'	  	=> $attack_clan_id,
                        'compare' 	=> '=',
                    ),
                    array(
                        'key'	 	=> 'declared_by',
                        'value'	  	=> $defend_clan_id,
                        'compare' 	=> '=',
                    ),
                ),
            )
        );

        if(count($incoming_wars) > 0) {
            $incoming_war = true;
        }

        /* calculate war multiplier and determine mutual */
        if ($outgoing_war && $incoming_war) {
            /* mutual war */
            $war_type = "mutual";
        }
        elseif ($outgoing_war) {
            /* outgoing only */
            $war_type = "outgoing";
        }
        elseif ($incoming_war) {
            /* incoming only */
            $war_type = "incoming";
        }
        else {
            /* no war */
            $war_type = "none";
        }
    }
    /* return war type */
    return $war_type;
}


/*
	get_war_multiplier
	Params:
		$war_type : type of war ('mutual', 'outgoing', 'incoming', 'none')
	Return:
		war_multiplier : retrieved from constants
*/
function get_war_multiplier($war_type) {
    /* determine multiplier by type */
    include('constants.php');

    switch ($war_type) {
        case 'mutual':
            $war_multiplier = $WAR_POINTS_MULT_MUTUAL;
            break;
        case 'outgoing':
            $war_multiplier = $WAR_POINTS_MULT_OUTGOING;
            break;
        case 'incoming':
            $war_multiplier = $WAR_POINTS_MULT_INCOMING;
            break;
        case 'none':
            $war_multiplier = $WAR_POINTS_MULT_NONE;
            break;
        default:
            $war_multiplier = 0;
    }
    return $war_multiplier;
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
    global $MORALE_SABOTEUR, $MORALE_ATTACK_TGT_ABOVE, $MORALE_ATTACK_TGT_BELOW, $MORALE_MISSILE_TGT_ABOVE, $MORALE_MISSILE_TGT_BELOW,
           $MORALE_THIEF, $MORALE_SPY;

    $targetIsBigger = $attack_nw < $defend_nw;

    switch (strtolower($attack_type)) {
        case 'missile':
            return $targetIsBigger ? $MORALE_MISSILE_TGT_ABOVE : $MORALE_MISSILE_TGT_BELOW;
            break;
        case 'thief':
            return $MORALE_THIEF;
            break;
        case 'saboteur':
            return $MORALE_SABOTEUR;
            break;
        case 'spy':
            return $MORALE_SPY;
            break;
        case 'air_sea':
            return $targetIsBigger ? $MORALE_ATTACK_TGT_ABOVE : $MORALE_ATTACK_TGT_BELOW;
            break;
        case 'sniper':
            return 10;
            break;
        case 'regular':
            return $targetIsBigger ? $MORALE_ATTACK_TGT_ABOVE : $MORALE_ATTACK_TGT_BELOW;
            break;
        case 'ground':
            return $targetIsBigger ? $MORALE_ATTACK_TGT_ABOVE : $MORALE_ATTACK_TGT_BELOW;
            break;
        case 'regular':
            return $targetIsBigger ? $MORALE_ATTACK_TGT_ABOVE : $MORALE_ATTACK_TGT_BELOW;
            break;
        case 'ground':
            return $targetIsBigger ? $MORALE_ATTACK_TGT_ABOVE : $MORALE_ATTACK_TGT_BELOW;
            break;
    }
};


function get_attack_cost_turns($attack_type) {
    global $TURNS_MISSILE, $TURNS_SPY, $TURNS_THIEF, $TURNS_ATTACK;
    if (strtolower($attack_type) == 'thief')
        return $TURNS_THIEF;
    if (strtolower($attack_type) == 'saboteur')
        return 2;
    if (strtolower($attack_type) == 'spy')
        return $TURNS_SPY;
    if (strtolower($attack_type) == 'missile')
        return $TURNS_MISSILE;
    if (strtolower($attack_type) == 'air_sea' || 'regular' || 'ground')
        return $TURNS_ATTACK;
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

    /*check for starting bonus*/
    $startingbonus = get_user_meta($target_id, 'starting_bonus',true);
    $defensive_multi = 1;
    if($startingbonus == 'defensive'){
        $defensive_multi = 1.2;
    }
    include('units_array.php');
    include('building_array.php');
    include('constants.php');

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
            $build_life = $data['life'];
            $bld_sum_life = $build_count * $build_life;
            $stat_array['bld'][$key]['life'] = $bld_sum_life;
            $stat_array['bld'][$key]['count'] = $build_count;

            $total_bld_life += $bld_sum_life;
            $total_bld_count += $build_count;
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
        if ($unit_count > 0 && !in_array($key, $SPECIAL_UNITS)) {
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
    include('units_array.php');
    include('building_array.php');

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
    include('units_array.php');
    include('building_array.php');
    include('constants.php');

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
            $db_atk_power = $bld_count * $attack_power * 2.38 * 0.8; // 2.38 to counter reduction factors implemented. 0.8 to decrease strength a bit
            $attack_array[$target_type] += $db_atk_power;
        }

        /* add to life for all */
        $bld_life = $buildings[$key]['life'];
        $bld_life_total = $bld_life * $bld_count;
        $life_array['bld'] += $bld_life_total;

        //Store the value of this building count to overall total
         $overall_bld_total +=$bld_count;
    }

    /* get defense from units */
    foreach($units as $key => $data) {
        $unit_count = get_user_meta($target_id, $key.'_owned')[0];

        /* if defender has none of this unit continue */
        if ($unit_count < 1)
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
            $attack_array[$type] += $divided_atk_power;
        }

        /* calculate life per type */
        $unit_life = $units[$key]['life'];
        $unit_life_total = $unit_life * $unit_count;
        $unit_type = $units[$key]['type'];

        $life_array[$unit_type] += $unit_life_total;
    }
    $defense_array['life'] = $life_array;
    $defense_array['attack'] = $attack_array;

    return $defense_array;
}


function return_overall_blds_for_defender () {
    global $overall_bld_total;
    return $overall_bld_total;
}


function calculate_defense_by_type2($target_id, $power_on, $attackerRemoveArray) {
    include('units_array.php');
    include('building_array.php');
    include('constants.php');

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
        $bld_life = $buildings[$key]['life'];
        $bld_life_total = $bld_life * $bld_count;
        $life_array['bld'] += $bld_life_total;

    }

    /* get defense from units */
    foreach($units as $key => $data) {
        $unit_count = get_user_meta($target_id, $key.'_owned')[0];

        /* if defender has none of this unit continue */
        if ($unit_count < 1)
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
            $attack_array[$type] += $divided_atk_power;
        }

        /* calculate life per type */
        $unit_life = $units[$key]['life'];
        $unit_life_total = $unit_life * $unit_count;
        $unit_type = $units[$key]['type'];

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
    include('building_array.php');
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
	sum_arrays
	Params:
		$master_array
		$unit_array
	Returns:
		$master_array : values from $unit_array added to $master_array
*/
function sum_arrays($master_array, $unit_array) {
    foreach($unit_array as $unit => $damage) {
        if (array_key_exists($unit, $master_array)) {
            $master_array[$unit] += $damage;
        }
        else {
            $master_array[$unit] = $damage;
        }
    }
    return $master_array;
}


/*
	get_idle_mult
	Params:
		$attack_type : type of attack
		$unit_key : unit being attacked
	Return:
		$idle_mult : damage multiplier based on idle status
*/
function get_idle_multiplier($attack_type, $unit_key) {
    //echo $unit_key;
    $idle_multiplier = 1.0;

    return $idle_multiplier;
}

/*
	get_attack_type_multiplier($attack_type)
	Params:
		$attack_type
	Return:
		$atk_type_mult
*/
function get_attack_type_multiplier($attack_type) {
    $atk_type_mult = 1.0;
    /* TODO - hook point for attack type multiplier */
    return $atk_type_mult;
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

    include('units_array.php');
    include('building_array.php');
    include('constants.php');

    $losses = array();

    foreach($unit_array as $type => $type_stats) {

        $attack_power = 0;

        if (array_key_exists($type, $attacker_type_power))
            $attack_power = $attacker_type_power[$type];

        /* ensure we can attack this type */
        if ($attack_power < 1)
            continue;

        /* adjust attack power for attack type multiplier */
        $atk_type_mult = get_attack_type_multiplier($attack_type);
        $attack_power = $attack_power * $atk_type_mult;

        /* get total life for this type */
        $total_units = $unit_array[$type]['total_count'];

        foreach($type_stats as $unit_key => $unit_stats) {

            /* ignore totals */
            if ($unit_key == 'total_life' || $unit_key == 'total_count')
                continue;

            /* account for idle rule */
            $idle_multiplier = get_idle_multiplier($attack_type, $unit_key);
            $attack_power = $attack_power * $idle_multiplier;

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
                $PPE_level = get_user_meta($target_id, 'level_powerplant_efficiency')[0];
                $PPE_multi = 1;

                if($buildings[$unit_key] == 'powerplant' || $buildings[$unit_key] == 'advancedpowerplant' ){
                    if($PPE_level == 1){
                        $PPE_multi = 1.5;
                    }
                }

                $unit_life = $buildings[$unit_key]['life']*$PPE_multi;
                $dmg_reduction = $DAMAGE_REDUCTION_FACTOR_BLD;
                //MEGA 20180219 make buildings harder to kill if less than 300 remain
                if (return_overall_blds_for_defender() < 300) {
                    $dmg_reduction = $DAMAGE_REDUCTION_FACTOR_BLD*1.2;
                }
            }
            else {
                $unit_life = $units[$unit_key]['life'];
                $dmg_reduction = $DAMAGE_REDUCTION_FACTOR_UNIT;
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
	is_player_dead
	Params:
		$user_id : user id of target
	Return:
		$is_dead : is target dead
*/
function is_player_dead($user_id) {
    $bld_total = 0;
    foreach($buildings as $key => $data) {
        $bld_total += get_user_meta($user_id, $key)[0];
    }
    return $bld_total < 1;
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
    include('units_array.php');
    include('building_array.php');

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
 * Helper function in an attempt to avoid big clans completely raiding smaller clans or single provinces
 */
function get_clan_member_difference($attacker_ID, $defender_ID) {
    $attackerData = get_user_meta($attacker_ID);
    $defenderData = get_user_meta($defender_ID);
    $attacker_clan_ID = $attackerData['clan_id_user'][0];
    $defender_clan_ID = $defenderData['clan_id_user'][0];

    // If attacker is not in a clan, no difference
    if(empty($attacker_clan_ID)) return 0;

    // In a mutual war you always get full points,damage,etc
    $war_type = get_war_type($attacker_clan_ID,$defender_clan_ID);
    if($war_type == 'mutual') return 0;

    // Failsafe on clan
    $attacker_clan_size = count(maybe_unserialize(get_post_meta($attacker_clan_ID, 'clan_members', true)));
    if(empty($attacker_clan_size)) return 0;

    // If the defender is not in a clan, clansize is also 1
    if(empty($defender_clan_ID)) $defender_clan_size = 1;
    else $defender_clan_size = count(maybe_unserialize(get_post_meta($defender_clan_ID, 'clan_members', true)));

    // But if the attackers clan is bigger than the defender, than we get in some reduction (finally)
    return $attacker_clan_size-$defender_clan_size;
}

/**
 * Clanpoint gain reduction based on clanmembersize difference
 * Some stats:
 * If clan is 1 larger, there is 1 point difference from 15 points or higher (15=14, 25=24)
 * If clan is 2 larger, there is 1 point difference from 8 points or higher (8=7, 25=22)
 * If clan is 3 larger, there is 1 point difference from 5 points or higher (5=4, 25=20)
 * If clan is 4 larger, there is 1 point difference from 4 points or higher (4=3, 25=18)
 * If clan is 5 larger, there is 1 point difference from 3 points or higher (3=2, 25=17)
 */
function scaled_points_to_clansize($clan_points, $attacker_ID, $defender_ID) {
    $diff = get_clan_member_difference($attacker_ID, $defender_ID);
    if($diff < 1) return $clan_points; // If attacker no clan, mutual war or the attacker clan size is smaller or equal, no reduction
    $clan_points = ceil($clan_points * ((100-(($diff*35)/5))/100) ); //diff 5 = 35%
    return $clan_points;
}

/**
 * Stolen land reduction
 * Only for a clanmember-difference of 3 or larger
 * @todo: currently disabled
 */
function scaled_land_to_clansize($land_stolen, $attacker_ID, $defender_ID) {
    //$diff = get_clan_member_difference($attacker_ID, $defender_ID);
    //if($diff < 3) return $land_stolen; // If attacker no clan, mutual war or the attacker clan size is smaller or equal, no reduction
    return $land_stolen;
}

/**
 * Stolen money reduction
 * Only for a clanmember-difference of 3 or larger
 * @todo: currently disabled
 */
function scaled_money_to_clansize($money_stolen, $attacker_ID, $defender_ID) {
    //$diff = get_clan_member_difference($attacker_ID, $defender_ID);
    //if($diff < 3) return $money_stolen; // If attacker no clan, mutual war or the attacker clan size is smaller or equal, no reduction
    return $money_stolen;
}

/**
 * Damage reduction based on clan member size difference
 * Only used for buildings for now
 */
function scaled_damage_to_clansize($damage, $attacker_ID, $defender_ID) {
    $diff = get_clan_member_difference($attacker_ID, $defender_ID);
    if($diff < 1) return $damage; // If attacker no clan, mutual war or the attacker clan size is smaller or equal, no reduction
    $damage = ceil($damage * ((100-(($diff*30)/5))/100)); // diff 5 = 30%
    return $damage;
}