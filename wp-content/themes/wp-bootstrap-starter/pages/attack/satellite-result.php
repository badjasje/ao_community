<?php
$units = Units::get();
$buildings = Buildings::get();
$satellites = Satellites::get();

$target_id = $_POST['target_id'];
$maintarget = ($debug ? $_POST['maintarget'] : filter_input(INPUT_POST, 'maintarget', FILTER_SANITIZE_STRING));
$attackmode = ($debug ? $_POST['attackmode'] : filter_input(INPUT_POST, 'attackmode', FILTER_SANITIZE_STRING));
$attackmode = ($attackmode == 'aggressive' ? 'aggressive' : 'normal');

$SEA_ATT_power   = 0;
$AIR_ATT_power   = 0;
$INF_ATT_power   = 0;
$VEH_ATT_power   = 0;
$BLD_ATT_power   = 0;

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

$winner_ID = $userId;

$turns = $attackerData['turns'][0];

/* check if user has enough turns */
if($turns < Settings::get('turns_satellite')){
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$sat_morale = $attackerData['sat_morale'][0];
if ($sat_morale < 100) {
	$array['status'] = 'Insufficient satellite power';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$sat_owned = $attackerData['sat_owned'][0];
if ($sat_owned != 'laser') {
	$array['status'] = 'You cannot do that';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$blddamage = rand(6500,8000);

// Attack power scaled to number of out-of-war attacks within X days between two provinces, where first Y aren't counted,
// only applied outside of war
if($war_type == 'none') {
	$blddamage = scaled_power_pvp($blddamage, $userId, $target_id);
}
/*if($defenderData['land'][0] < 7500){
	$reduction = $defenderData['land'][0]/7500;
	if($reduction <= 0.5){
		$reduction = 0.5;
	}
	$blddamage = $blddamage*$reduction;
}*/

$startingbonus = $defenderData['starting_bonus'][0];
$defensive_multi = 1;
if($startingbonus == 'defensive'){
	$defensive_multi = 1.25;
}

// Scale building damage on clan size difference
$blddamage = scaled_damage_to_clansize($blddamage, $userId, $target_id);

update_user_meta($userId,'sat_morale',$sat_morale-100);
$result = 'success';

$sat_status = $defenderData['stealth_sat_status'][0];
if($sat_status == 'active'){
	$result = 'failure';
	$blddamage = 0;
}

$hit = rand(1,100);
if($hit > 97){
	$result = 'failure';
	$blddamage = 0;
}

$defender_lost = array();

// KILLING BUILDINGS OF DEFENDER //
$_total_bld_def = 0;
foreach ($buildings as $key => $building) {
    $def_bld_owned = $defenderData[$key][0];
    $_total_bld_def += $def_bld_owned;
}

foreach ($defender->getBuildings() as $key => $building) {

	//bld
	$def_bld_owned = $defenderData[$key][0];
	if ($def_bld_owned > 0) {
		$percentage = $def_bld_owned / $_total_bld_def;

        $damage = $blddamage * $percentage;

        $buildings_lost = round($damage / ($building['life']*$defensive_multi));
        if ($buildings_lost > 0) {
			if ($def_bld_owned < $buildings_lost) {
				if(!$attacker->isShadowBanned()) update_user_meta($target_id, $key, 0);
				$defender_lost[] = array('type' => 'bld', $key => $def_bld_owned);
			} else {
				if(!$attacker->isShadowBanned()) update_user_meta($target_id, $key, $def_bld_owned - $buildings_lost);
				$defender_lost[] = array('type' => 'bld', $key => $buildings_lost);
			}
		}
	}
}

$land_stolen = 0;
$money_stolen = 0;
$attacker_lost = 0;

// CHECK IF PLAYER IS DEAD
if ($_total_bld_def <= 0) {
    update_user_meta($target_id, 'status', 'dead');
    update_user_meta($target_id, 'networth', 0);
	$array['status'] = 'This player is dead';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$def_unitslost = $defender_lost;
$att_unitslost = 0;

$def_NW_lost           = 0;
$att_NW_lost           = 0;
$def_lostunits_tot     = 0;
$def_lostbuildings_tot = 0;

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

$killed = false;
if(!$attacker->isShadowBanned() && $def_lostbuildings_tot >= $_total_bld_def) {
	if($debug) wtf('kill_player', $def_lostbuildings_tot, $_total_bld_def);
	else $killed = $defender->dies($attacker->get('id'));
}

////// CALCULATE CLAN POINTS //////
$clan_points_old_att = get_post_meta($attacker_clan_ID,'clan_points',true);

/* calculate clan points */
$clan_points = 0;
$unit_points = 0;

if($war_type != 'none' && $result == 'success') {
	//if ($killed != true) {
		$clan_points = 7.8 * log($def_NW_lost/1.4 / 400);

		if($clan_points < 1) $clan_points = 1;

		/* determine points multiplier due to war */
		$war_multiplier = get_war_multiplier($war_type);
		$clan_points = ceil($clan_points * $war_multiplier);
	//}

	// Points cap
	$clan_points = min($clan_points, Settings::get('points_cap'));

	/* determine points multiplier due to war */
	if ($killed == true) {
		/* add stats */

		// attacker
		$kills_made = $attackerData['kills_made'][0];
		update_user_meta($userId, 'kills_made', $kills_made+1);

		// defender
		if(!$attacker->isShadowBanned()) {
			$times_killed = $defenderData['times_killed'][0];
			update_user_meta($target_id, 'times_killed', $times_killed+1);
		}

		if($war_type == 'mutual') $clan_points = Settings::get('points_kill_mutual');
		elseif($war_type == 'incoming') $clan_points = Settings::get('points_kill_incoming');
		elseif($war_type == 'outgoing') $clan_points = Settings::get('points_kill_outgoing');
	}
	else {
		// Jaap, points based on clansize
		$clan_points = scaled_points_to_clansize($clan_points, $userId, $target_id);
		// Jaap, points based on difference between clanpoints totals
		$clan_points = scaled_points_to_clanpoints($clan_points, $userId, $target_id);
	}

	$clan_points = ceil($clan_points);
	if($attacker->isShadowBanned()) $clan_points = 0;

	if($debug) debug_var('Clan points', $clan_points);

	/* add points */
	$attackerClan->addToMeta('clan_points', $clan_points);
	$attackerClan->addToMeta('ua_total', 1);
	$attackerClan->addToMeta('24h_pts', $clan_points);
}

if($result == 'success') { ?>

	<div class="blockHeader">Your satellite hit the base of <?php echo get_user_name($target_id);?>
		<?php if ($killed == true):?>
			<u>and killed this player</u>
		<?php endif;?>
	</div>

	<div class="battleReportInfo statCol-1">Satellite report</div>
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
			Your networth decreased: $<?php echo number_format(0, 0, ',', ' ');?>
		</div>
		<div class="col-md-8 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.48);">
			Enemy networth decreased: $<?php echo number_format($def_NW_lost, 0, ',', ' ');?>
		</div>
	</div>

	<div class="row statusBlockButtons">
		<div class="col-md-4 battleReportInfo statCol-2">
			No units lost
		</div>
		<div class="col-md-4 battleReportInfo statCol-3">
			No units killed
		</div>
		<div class="col-md-4 battleReportInfo statCol-4">
			Buildings destroyed:
			<?php echo $def_lostbuildings_tot; ?></strong><br/>
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

	<script>
	(function($) {
		$(document).ready(function() {
			<?php $userDataSecondary = get_user_meta($userId);?>
			// Dynamic update of header statistics
			$('#morale').html('<?php echo $userDataSecondary['morale'][0];?>');
			$('#turns').html('<?php echo $userDataSecondary['turns'][0];?>');
			$('#land').html('<?php echo $userDataSecondary['land'][0];?>');
			$('#money').html('<?php echo $userDataSecondary['money'][0];?>');
			$('#networth').html('<?php echo $userDataSecondary['networth'][0];?>');
		});
	})(jQuery);
	</script>
	<?php
}

if($result == 'failure'){
	$winner_ID == $target_id;
	?>
	<div class="blockHeader">Your satellite missed the base of <?php echo get_user_name($target_id);?></div>

	<div class="battleReportInfo statCol-1">Satellite report</div>
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
			Your networth decreased: $<?php echo number_format(0, 0, ',', ' ');?>
		</div>
		<div class="col-md-8 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.48);">
			Enemy networth decreased: $<?php echo number_format($def_NW_lost, 0, ',', ' ');?>
		</div>
	</div>

	<div class="row statusBlockButtons">
		<div class="col-md-4 battleReportInfo statCol-2">
			No units lost
		</div>
		<div class="col-md-4 battleReportInfo statCol-3">
			No units killed
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
	<?php
}

$old_CP = $attackerData['user_clan_points'][0];
update_user_meta($userId, 'user_clan_points', $old_CP+$clan_points);

// Update attacker points for current clan
$userAttPts = $attackerData['current_clan_points'][0];
update_user_meta($userId, 'current_clan_points', $userAttPts+$clan_points);

////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(
	'post_title'    => 'Satellite attack made by '.$userId.' Defender: '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);

$new_event_id = wp_insert_post( $args );
update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
update_field('defender_lost', $def_unitslost, $new_event_id);
update_field('attacker_lost', $att_unitslost, $new_event_id);
update_field('land_lost', $land_stolen, $new_event_id);
update_field('money_lost', $money_stolen, $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);
update_field('total_buildings_lost',$def_lostbuildings_tot, $new_event_id);

update_field('nw_damage_defender',$def_NW_lost, $new_event_id);

update_field('clan_points', $clan_points, $new_event_id);

update_field('defender_id',$target_id, $new_event_id);
update_field('winner_id',$winner_ID, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('attacktype',$attack_type, $new_event_id);
update_field('outcome',$result, $new_event_id);
update_field('maintarget', $maintarget, $new_event_id);
update_field('attackmode', $attackmode, $new_event_id);

if($killed == true){
	kill_event($userId,$target_id,$result,$defender_clan_ID,$attacker_clan_ID);
	update_field('status_defender','death', $new_event_id);
}

update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);

update_user_meta($userId,'turns', $turns-Settings::get('turns_satellite'));
turn_spread('laser_satellite', Settings::get('turns_satellite'));

if(!$attacker->isShadowBanned()) {
	update_user_meta($target_id, 'new_events', $defenderData['new_events'][0]+1);

	/* Add globals to defender */
	$clan = $defender_clan_ID;
	$clan_members = get_post_meta($clan,'clan_members');

	if(!empty($clan) || $clan != 0){
		foreach ($clan_members[0] as $member) {
			$globals = get_user_meta($member, 'new_global_events', true);
			update_user_meta($member, 'new_global_events', $globals+1);
		}
	}
}

/* Add globals to attacker */
$clan_att = $attacker_clan_ID;
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
	foreach ($clan_members_att[0] as $member_att) {
		$globals = get_user_meta($member_att, 'new_global_events', true);
		update_user_meta($member_att, 'new_global_events', $globals+1);
	}
}