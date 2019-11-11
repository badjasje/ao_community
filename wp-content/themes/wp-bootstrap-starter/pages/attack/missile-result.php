<?php
$missiles = Missiles::get();
$buildings = Buildings::get();
$units = Units::get();

$winner_ID = $userId;
$maintarget = ($debug ? $_POST['maintarget'] : filter_input(INPUT_POST, 'maintarget', FILTER_SANITIZE_STRING));
$attackmode = ($debug ? $_POST['attackmode'] : filter_input(INPUT_POST, 'attackmode', FILTER_SANITIZE_STRING));
$attackmode = ($attackmode == 'aggressive' ? 'aggressive' : 'normal');
$defender_lost = array();

// Need Silos to launch missile
if($attackerData['silo'][0] <= 0){
	$array['status'] = 'Not enough missile silos';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

// Check if attacker has enough turns
$turns = $attackerData['turns'][0];
if($turns < 3){
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

// Check if attacker has enough missiles
$key = $_POST['missiletype'];
$missile_type = $_POST['missiletype'];
$owned_miss = $attackerData[$key.'_owned'][0];

if($owned_miss <= 0 ){
	$array['status'] = 'Not enough missiles of this type';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$shotdown = false;
$AMS = $defenderData['antimissile'][0];
$power = $defenderData['power'][0];
$def_land = $defenderData['builtland'][0];
$startingbonus = $defenderData['starting_bonus'][0];
$defensive_multi = 1;
if($startingbonus == 'defensive'){
	$defensive_multi = 1.25;
}

$shootdown_chance = min((($AMS*100)/$def_land)*100,75);
$shootdown = rand(1, 100);

if($shootdown < $shootdown_chance){
	$shotdown = true;
}

if($AMS == 0 || $power > 100){
	$shotdown = false;
}

/* AMS-Satellite */
$defSat = $defenderData['sat_owned'][0];
$satMorale = $defenderData['sat_morale'][0];

if($satMorale >= 20 && $power < 100){
	if($defSat == 'amssat'){
		$shotdown = true;
		update_user_meta($target_id, 'sat_morale', $satMorale-20);
	}
}

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

$missile_research = $attackerData['level_missile_accuracy'][0];

$missile_hit = rand(1,100);

if($missile_hit >= 90) {
	$result = 'success';
} else {
	$result = 'failure';
}

if($missile_research == 1) {
	$missile_hit = rand(1,100);
	if($missile_hit >= 50) {
		$result = 'success';
	} else {
		$result = 'failure';
	}
}

if($missile_research >= 2) {
	$missile_hit = rand(1,100);
	if($missile_hit > 5) {
		$result = 'success';
	} else {
		$result = 'failure';
	}
}

if($shotdown == true){
	$result = 'failure';
}

// Update some basic stats, turns, morale and launched missile
update_user_meta($userId, 'morale', $oldmorale - $moralecost);
update_user_meta($userId, 'turns', $turns - 3);
update_user_meta($userId, $key.'_owned',$owned_miss-1);

// Check if silos have been sabotaged
$silo1Status = $attackerData['silo_disable_1'][0];
$silo2Status = $attackerData['silo_disable_2'][0];
$disabled = false;

if($silo1Status == 'active' || $silo2Status == 'active'){
	$shotdown = false;
	$result = 'failure';
	$disabled = true;
}

/* calculate attack power and divide power */
$attackpower   = $missiles[$key]['attack']*0.87;
$divided_power = $attackpower / count($missiles[$key]['attacks']);

$attacks = $missiles[$key]['attacks'];
foreach ($attacks as $attack) {
	if ($attack == 'sea') {
		$SEA_ATT_power += $divided_power * (rand(9, 11) / 10);
	}
	if ($attack == 'air') {
		$AIR_ATT_power += $divided_power * (rand(9, 11) / 10);
	}
	if ($attack == 'inf') {
		$INF_ATT_power += $divided_power * (rand(9, 11) / 10);
	}
	if ($attack == 'veh') {
		$VEH_ATT_power += $divided_power * (rand(9, 11) / 10);
	}
	if ($attack == 'bld') {
		$BLD_ATT_power += $divided_power * (rand(9, 11) / 10);
	}
}

$airdamage = $AIR_ATT_power;
$infdamage = $INF_ATT_power;
$vehdamage = $VEH_ATT_power;
$seadamage = $SEA_ATT_power;
$blddamage = $BLD_ATT_power*1.2;

// Attack power scaled to number of out-of-war attacks within X days between two provinces, where first Y aren't counted,
// only applied outside of war
if($war_type == 'none') {
	$airdamage = scaled_power_pvp($airdamage, $userId, $target_id);
	$infdamage = scaled_power_pvp($infdamage, $userId, $target_id);
	$vehdamage = scaled_power_pvp($vehdamage, $userId, $target_id);
	$seadamage = scaled_power_pvp($seadamage, $userId, $target_id);
	$blddamage = scaled_power_pvp($blddamage, $userId, $target_id);
}

/*if($defenderData['land'][0] < 7500){
	$reduction = $defenderData['land'][0]/7500;
	if($reduction <= 0.5){
		$reduction = 0.5;
	}
	$blddamage = $blddamage*$reduction;
}*/

// Scale building damage on clan size difference
$blddamage = scaled_damage_to_clansize($blddamage, $userId, $target_id);

// DEFENDING //
$_total_air_units_def = 0;
$_total_inf_units_def = 0;
$_total_veh_units_def = 0;
$_total_sea_units_def = 0;

if($result == 'success') {

	foreach ($units as $key => $order) {
		$units_defending = $defenderData[$key . '_owned'][0];
		$unittype = $units[$key]['type'];

		if ($unittype == 'sea') {
			$_total_sea_units_def += $units_defending;
		}
		if ($unittype == 'air') {
			$_total_air_units_def += $units_defending;
		}
		if ($unittype == 'inf') {
			$_total_inf_units_def += $units_defending;
		}
		if ($unittype == 'veh') {
			$_total_veh_units_def += $units_defending;
		}
	}

	/// MISSILES KILLING DEFENDER UNITS ///
	$TOTAL_ATT_DAMAGE = 0;
	foreach ($units as $key => $order) {
		$unittype = $units[$key]['type'];

		//AIR
		if ($unittype == 'air') {
			$def_units_owned = $defenderData[$key . '_owned'][0];

			if ($def_units_owned > 0) {
				$percentage = $def_units_owned / $_total_air_units_def;

				if($units[$key]['sectype'] == 'bk') {
					$damage = ($airdamage * $percentage)*0.85;
				} else {
					$damage = $airdamage * $percentage;
				}

				$TOTAL_ATT_DAMAGE += $damage;
				$units_lost = round($damage / $units[$key]['life']);
				if ($units_lost > 0) {
					if ($def_units_owned < $units_lost) {
						update_user_meta($target_id, $key . '_owned', 0);
						$defender_lost[] = array('type' => 'unit', $key => $def_units_owned);
					} else {
						update_user_meta($target_id, $key . '_owned', $def_units_owned - $units_lost);
						$defender_lost[] = array('type' => 'unit', $key => $units_lost);
					}
				}
			}
		}

		//INF
		if ($unittype == 'inf') {
			$def_units_owned = $defenderData[$key . '_owned'][0];

			if ($def_units_owned > 0) {
				$percentage = $def_units_owned / $_total_inf_units_def;

				if($units[$key]['sectype'] == 'bk'){
					$damage = ($infdamage * $percentage)*0.65;
				}else{
					$damage = $infdamage * $percentage;
				}

				$TOTAL_ATT_DAMAGE += $damage;
				$units_lost = round($damage / $units[$key]['life']);
				if ($units_lost > 0) {
					if ($def_units_owned < $units_lost) {
						update_user_meta($target_id, $key . '_owned', 0);
						$defender_lost[] = array('type' => 'unit', $key => $def_units_owned);
					} else {
						update_user_meta($target_id, $key . '_owned', $def_units_owned - $units_lost);
						$defender_lost[] = array('type' => 'unit', $key => $units_lost);
					}
				}
			}
		}

		//VEH
		if ($unittype == 'veh') {
			$def_units_owned = $defenderData[$key . '_owned'][0];

			if ($def_units_owned > 0) {
				$percentage = $def_units_owned / $_total_veh_units_def;

				if($units[$key]['sectype'] == 'bk'){
					$damage = ($vehdamage * $percentage)*0.85;
				} else {
					$damage = $vehdamage * $percentage;
				}

				$TOTAL_ATT_DAMAGE += $damage;
				$units_lost = round($damage / $units[$key]['life']);

				if ($units_lost > 0) {
					if ($def_units_owned < $units_lost) {
						update_user_meta($target_id, $key . '_owned', 0);
						$defender_lost[] = array('type' => 'unit', $key => $def_units_owned);
					} else {
						update_user_meta($target_id, $key . '_owned', $def_units_owned - $units_lost);
						$defender_lost[] = array('type' => 'unit', $key => $units_lost);
					}
				}
			}
		}

		//SEA
		if ($unittype == 'sea') {
			$def_units_owned = $defenderData[$key . '_owned'][0];

			if ($def_units_owned > 0) {
				$percentage = $def_units_owned / $_total_sea_units_def;

				if($units[$key]['sectype'] == 'bk') {
					$damage = ($seadamage * $percentage)*0.85;
				} else {
					$damage = $seadamage * $percentage;
				}

				$TOTAL_ATT_DAMAGE += $damage;
				$units_lost = round($damage / $units[$key]['life']);

				if ($units_lost > 0) {
					if ($def_units_owned < $units_lost) {
						update_user_meta($target_id, $key . '_owned', 0);
						$defender_lost[] = array('type' => 'unit', $key => $def_units_owned);
					} else {
						update_user_meta($target_id, $key . '_owned', $def_units_owned - $units_lost);
						$defender_lost[] = array('type' => 'unit', $key => $units_lost);
					}
				}
			}
		}
	}

	// KILLING BUILDINGS OF DEFENDER //

	/* calculate total number of buildings by defender */
	$_total_bld_def = 0;
	foreach ($buildings as $key => $building) {
		$def_bld_owned = $defenderData[$key][0];
		$_total_bld_def += $def_bld_owned;
	}

	foreach ($buildings as $key => $building) {

		/* get building by type */
		$def_bld_owned = $defenderData[$key][0];

		/* check if defender owns building */
		if ($def_bld_owned > 0) {
			$percentage = $def_bld_owned / $_total_bld_def;

			$damage = $blddamage * $percentage;
			$TOTAL_ATT_DAMAGE += $damage;

			$buildings_lost = round($damage / ($building['life']*$defensive_multi));
			if ($buildings_lost > 0) {
				if ($def_bld_owned < $buildings_lost) {
					update_user_meta($target_id, $key, 0);
					$defender_lost[] = array('type' => 'bld', $key => $def_bld_owned);
				} else {
					update_user_meta($target_id, $key, $def_bld_owned - $buildings_lost);
					$defender_lost[] = array('type' => 'bld', $key => $buildings_lost);
				}
			}
		}
	}
}

// WRAPPING MISSILE UP //
$land_stolen = 0;
$money_stolen = 0;
$attacker_lost = 0;

$def_unitslost = $defender_lost;
$att_unitslost = 0;

$def_NW_lost           = 0;
$att_NW_lost           = 0;
$def_lostunits_tot     = 0;
$def_lostbuildings_tot = 0;

/* add stats */

// attacker
$missiles_launched = $attackerData['missiles_launched'][0];
update_user_meta($userId, 'missiles_launched', $missiles_launched+1);

// defender
$missiles_received = $defenderData['missiles_received'][0];
update_user_meta($target_id, 'missiles_received', $missiles_received+1);

if($result == 'success'){
	/* add stats */

	// attacker
	$missiles_hit = $attackerData['missiles_hit'][0];
	update_user_meta($userId, 'missiles_hit', $missiles_hit+1);

	// defender
	$missiles_hit_rec = $attackerData['missiles_hit_rec'][0];
	update_user_meta($target_id, 'missiles_hit_rec', $missiles_hit_rec+1);

	foreach ($units as $unitkey => $order) {
		foreach ($def_unitslost as $key => $def_unitlost) {
			if (isset($def_unitlost[$unitkey])) {
				if ($def_unitlost['type'] == 'unit') {
					$def_unitlost = array_values($def_unitlost);
					$def_unitlost = $def_unitlost[1];

					$def_lostunits_tot += $def_unitlost;
					$def_NW_lost += $def_unitlost * $units[$unitkey]['price'] * ($units[$unitkey]['networth'] / 100);
				}
			}
		}
	}

}

if($result == 'success'){
	foreach ($buildings as $buildingkey => $order) {
		foreach ($def_unitslost as $key => $def_bld_lost) {
			if (isset($def_bld_lost[$buildingkey])) {
				if ($def_bld_lost['type'] == 'bld') {

					$def_bld_lost = array_values($def_bld_lost);
					$def_bld_lost = $def_bld_lost[1];

					$def_lostbuildings_tot += $def_bld_lost;
					$def_NW_lost += $def_bld_lost * $buildings[$buildingkey]['price'] * ($buildings[$buildingkey]['networth'] / 100);
				}
			}

		}
	}
}

$land_stolen  = 0;
$money_stolen = 0;

$killed = false;
if($result == 'success'){

	if ($def_lostbuildings_tot >= $_total_bld_def) {
		$killed = true;
		update_user_meta($target_id, 'status', 'dead');
		update_user_meta($target_id, 'networth', 0);
		update_user_meta($target_id, 'land', 0);
		after_death($target_id);
	}
}

////// CALCULATE CLAN POINTS //////
$clan_points = 0;
$unit_points = 0;

$attackerClanData = get_post_meta($attacker_clan_ID);
$old_CP = $attackerClanData['clan_points'][0];

if($war_type != 'none' && $result == 'success') {

	/* check if defender is killed */
	if ($killed == true) {
		/* add stats */
		// attacker
		$kills_made = $attackerData['kills_made'][0];
		update_user_meta($userId, 'kills_made', $kills_made+1);

		// defender
		$times_killed = $defenderData['times_killed'][0];
		update_user_meta($target_id, 'times_killed', $times_killed+1);
	}

	$def_total_units = $_total_air_units_def+$_total_inf_units_def+$_total_veh_units_def+$_total_sea_units_def;

	if($def_total_units != 0 && $def_lostunits_tot != 0){
		$unit_points = $def_lostunits_tot/$def_total_units;
	}

	//if ($killed != true) {
		/* MEGA logic to make nuke NW account also for province NW, reducing it's reward at very low Networth.
			The division on NW lost will increase the difference between low and high nw done in terms of pts. HIGHER division = more range
			The division on the defender NW will decrease the overall points which nukes offer. HIGHER division = less pts */

		/* New function MEGA should award reasonable points */
		/* 5-1-2019 Kevin edit, just 90% awarded as per suggestions yo */
		$clan_points = (($def_NW_lost/1100)+((sqrt($def_NW_lost)/25) * ((sqrt($networth_def*1.5)/4.1)/100)))*0.9;

		/* MORE MEGA HAXXX. Reduce pts earned above a certain NW also*/
		if ($networth_def > 290000) {
			$reductionFactor =  (0.05 * sqrt($networth_def)) - 25; // Jaap: (sqrt(($networth_def)/1.5/65)/2)-25;
			$reductionPc = 1+$reductionFactor/100;
			$clan_points = $clan_points/$reductionPc;
		}
		/* HAX END */
	//}

	if($war_type == 'incoming') $clan_points = round($clan_points/2);

	// points cap
	if($clan_points < 1) $clan_points = 1;
	$clan_points = min(ceil($clan_points), Settings::get('points_cap'));

	if ($killed == true) {
		if($war_type == 'mutual') $clan_points = Settings::get('points_kill_mutual');
		elseif($war_type == 'incoming') $clan_points = Settings::get('points_kill_incoming');
		elseif($war_type == 'outgoing') $clan_points = Settings::get('points_kill_outgoing');
	}

	// Jaap, points based on clansize
	$clan_points = scaled_points_to_clansize($clan_points, $userId, $target_id);
	// Jaap, points based on difference between clanpoints totals
	$clan_points = scaled_points_to_clanpoints($clan_points, $userId, $target_id);

	if($debug) debug_var('Clan points', $clan_points);
}
// End MEGA 20180215

if($def_NW_lost <= 1){
	$clan_points == 0;
}

/* 24H pts update */
$_pts = get_post_meta($attacker_clan_ID, '24h_pts', true);
update_post_meta($attacker_clan_ID,'24h_pts',$_pts+$clan_points);

if($result == 'success'):

	/* add stats */
	// attacker
	$nw_damage_missiles = $attackerData['nw_damage_missiles'][0];
	update_user_meta($userId, 'nw_damage_missiles', $nw_damage_missiles+$def_NW_lost);


	//defender
	$nw_damage_missiles_rec = $defenderData['nw_damage_missiles_rec'][0];
	update_user_meta($target_id, 'nw_damage_missiles_rec', $nw_damage_missiles_rec+$def_NW_lost);
	?>
	<div class="blockHeader">Your missile hit the base of <?php echo get_user_name($target_id);?>
	<?php if ($killed == true):?>
		<u>and killed this player</u>
	<?php endif;?>
	</div>

<?php else:	?>
	<?php
		$clan_points = 0;
		$winner_ID = $target_id;
	?>
	<?php if($result == 'failure' && $shotdown != true && $disabled != true): ?>
		<div class="blockHeader">Your missile missed the base of <?php echo get_user_name($target_id);?></div>
	<?php endif;?>

	<?php if($result == 'failure' && $shotdown == true && $disabled != true): ?>
		<div class="blockHeader">Your missile was shot down by <?php echo get_user_name($target_id);?></div>
	<?php endif;?>

	<?php if($result == 'failure' && $shotdown == false && $disabled == true): ?>
		<div class="blockHeader">Your missile silo was sabotaged. You lost your missile and your missile silo.</div>
		<?php
		update_user_meta($userId, 'silo', $attackerData['silo'][0]-1);
		if($silo1Status == 'active'){
			update_user_meta($userId, 'silo_disable_1', 'inactive');
		}else{
			update_user_meta($userId, 'silo_disable_2', 'inactive');
		}
		?>
	<?php endif;?>
<?php endif; ?>

<div class="battleReportInfo statCol-1">Missile report</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">Money stolen: $ <?php echo number_format(0, 0, ',', ' ');?></div>
	<div class="col-md-4 battleReportInfo statCol-3">Land stolen: <?php echo number_format(0, 0, ',', ' ');?>m<sup>2</sup></div>
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
		Your networth decreased: $<?php echo number_format($missiles[$missile_type]['price']*0.09, 0, ',', ' ');?>
	</div>
	<div class="col-md-8 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.48);">
		Enemy networth decreased: $<?php echo number_format($def_NW_lost, 0, ',', ' ');?>
	</div>
</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">
		1 <?php echo $missiles[$missile_type]['normalname'];?> lost
	</div>
	<div class="col-md-4 battleReportInfo statCol-3">
		Units killed:
		<?php
			echo $def_lostunits_tot;
		?></strong><br/>
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
		Buildings destroyed:
		<?php
			echo $def_lostbuildings_tot;

		?></strong><br/>
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
////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(
	'post_title'    => 'Missile launched by '.$userId.' Defender: '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);
$new_event_id = wp_insert_post( $args );

update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());

update_field('time_attacked',$timestamp, $new_event_id);
update_field('war_status', $war_type, $new_event_id);
update_field('nw_damage_defender',$def_NW_lost, $new_event_id);
update_field('missile_type',$missile_type, $new_event_id);

if($result == 'success'){
	update_field('defender_lost', $def_unitslost, $new_event_id);
	update_field('total_buildings_lost',$def_lostbuildings_tot, $new_event_id);
	update_field('def_total_units_lost',$def_lostunits_tot, $new_event_id);
	update_field('clan_points', $clan_points, $new_event_id);
}
if($disabled == false){
	update_field('defender_id',$target_id, $new_event_id);
}
update_field('winner_id',$winner_ID, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('attacktype',$attack_type, $new_event_id);
update_field('outcome',$result, $new_event_id);
update_field('maintarget', $maintarget, $new_event_id);
update_field('attackmode', $attackmode, $new_event_id);
update_field('moralecost', $moralecost, $new_event_id);

if($shotdown == true){
	update_field('shotdown','shotdown', $new_event_id);
}
update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);

if($killed == true){
	kill_event($userId,$target_id,$result,$defender_clan_ID,$attacker_clan_ID);
	update_field('status_defender','death', $new_event_id);
	update_field('attacktype','missile', $new_event_id);
}

update_user_meta($userId,'turns',$turns-3);
turn_spread('regular_missile',3);
update_user_meta($target_id, 'new_events', $defenderData['new_events'][0]+1);

$user_pts = $attackerData['user_clan_points'][0];
update_user_meta($userId,'user_clan_points',$user_pts+$clan_points);

update_post_meta($attacker_clan_ID,'clan_points',$old_CP+$clan_points);

// Update attacker points for current clan
$userAttPts = $attackerData['current_clan_points'][0];
update_user_meta($userId, 'current_clan_points', $userAttPts+$clan_points);

/* Add globals to defender */
$clan = $defender_clan_ID;
$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
	foreach ($clan_members[0] as $member) {
		$globals = get_user_meta($member, 'new_global_events', true);
		update_user_meta($member, 'new_global_events', $globals+1);
	}
}

/* add globals attacker */
$clan_att = $attacker_clan_ID;
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
	foreach ($clan_members_att[0] as $member_att) {
		$globals = get_user_meta($member_att, 'new_global_events', true);
		update_user_meta($member_att, 'new_global_events', $globals+1);
	}
}

$warcheck = get_posts(
	array(
		'numberposts'	=> -1,
		'post_type'		=> 'wars',
		'meta_query'	=> array(
			'relation'		=> 'AND',
			array(
				'key'	 	=> 'declared_on',
				'value'	  	=> array($clan_att,$defender_clan_ID),
				'compare' 	=> 'IN',
			),
			array(
				'key'	 	=> 'declared_by',
				'value'	  	=> array($clan_att,$defender_clan_ID),
				'compare' 	=> 'IN',
			),
		),
	)
);
if(is_array($warcheck)) {
	$warstatID = get_post_meta($warcheck[0]->ID, 'war_array_id', true);
}

// Update war stats array for defender clan
$war_array_def = maybe_unserialize(get_post_meta($defender_clan_ID, 'war_array', true));

if(!is_array($war_array_def)){
	$war_array_def = array();
}

$war_array_def[$warstatID]['nw_dmg_rec'] += $def_NW_lost;
if(!isset($war_array_def[$warstatID]['missiles_received'])) $war_array_def[$warstatID]['missiles_received'] = 0;
$war_array_def[$warstatID]['missiles_received'] += 1;
if($result == 'success'){
	if(!isset($war_array_def[$warstatID]['missiles_hit_def'])) $war_array_def[$warstatID]['missiles_hit_def'] = 0;
	$war_array_def[$warstatID]['missiles_hit_def'] += 1;
}

if($killed == true){
	$war_array_def[$warstatID]['deaths'] += 1;
}

$war_array_def[$warstatID]['bds_lost'] += $def_lostbuildings_tot;
$war_array_def[$warstatID]['units_lost'] += $def_lostunits_tot;

update_post_meta($defender_clan_ID, 'war_array', maybe_serialize($war_array_def));

// Update war stats array for attacker clan
$war_array_att = maybe_unserialize(get_post_meta($attacker_clan_ID, 'war_array', true));

if(!is_array($war_array_att)){
	$war_array_att = array();
}

$war_array_att[$warstatID]['nw_dmg_done'] += $def_NW_lost;
$war_array_att[$warstatID]['clan_points'] += $clan_points;
if(!isset($war_array_def[$warstatID]['missiles_sent'])) $war_array_def[$warstatID]['missiles_sent'] = 0;
$war_array_att[$warstatID]['missiles_sent'] += 1;
if($result == 'success'){
	if(!isset($war_array_def[$warstatID]['missiles_hit_att'])) $war_array_def[$warstatID]['missiles_hit_att'] = 0;
	$war_array_att[$warstatID]['missiles_hit_att'] += 1;
}
$war_array_att[$warstatID]['bds_killed'] += $def_lostbuildings_tot;
$war_array_att[$warstatID]['units_killed'] += $def_lostunits_tot;

if($killed == true){
	$war_array_def[$warstatID]['kills'] += 1;
}

update_post_meta($attacker_clan_ID, 'war_array', maybe_serialize($war_array_att));

count_all_stats($target_id);
count_all_stats($userId);
update_user_meta($userId, 'user_lock', 0);
