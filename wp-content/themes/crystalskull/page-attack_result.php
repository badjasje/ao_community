<?php
 /*
 * Template Name: Attack Results
 */
/* imports */
include('attack_functions.php');
include 'units_array.php';
include 'constants.php';


$attacking_units = $_POST;

/* retrieve attacker user data */
$user_id = get_current_user_id();
$user_data = get_user_meta($user_id);

$attack_nw = $user_data['networth'][0];
$attack_clan_id = $user_data['clan_id_user'][0];

$attack_cost_turns = 0;
$attack_cost_morale = 0;

/* retrieve target user data */
$target_id = $_SESSION['target_id'];

$target_data = get_user_meta($target_id);

/* check if target isn't dead, else redirect */
$target_status = get_user_meta($target_id,'status',true);
if($target_status == 'dead'){
	wp_redirect(get_permalink(3360).'?fail=8');
	exit;
}
$maintarget = $_SESSION['maintarget'];
$attackmode = $_SESSION['attackmode'];

$extra_morale_cost = 0;
$aggressive_multi = 1;

$life_deduct = 1;
if($attackmode == 'aggressive'){
	$extra_morale_cost = 10;
	$life_deduct = 1.1;
	$aggressive_multi = 1.15;
}

$defend_nw = $target_data['networth'][0];
$defend_clan_id = $target_data['clan_id_user'][0];

/* retrieve attack info */
$attack_type = $_SESSION['attacktype'];
$attack_array = $_SESSION['attack_array'];

/* determine war type */
$war_type = get_war_type($attack_clan_id, $defend_clan_id);

/* check if target in range */
$in_range = target_in_range($attack_type, $attack_nw, $defend_nw, $war_type);

/* validate target in range */
if (!$in_range) {
	wp_redirect(get_permalink(3360).'?fail=9');
	exit;
}

/* calculate attack cost */
$attack_cost_arr = get_attack_cost($attack_type, $attack_nw, $defend_nw);
$attack_cost_turns = $attack_cost_arr['turns'];
$attack_cost_morale = $attack_cost_arr['morale']+$extra_morale_cost;

/* retrieve attacker's current resources */
$attack_curr_turns = get_user_meta($user_id, 'turns')[0];
$attack_curr_morale = get_user_meta($user_id, 'morale')[0];

/* validate attacker has sufficient morale */
if ($attack_cost_morale > $attack_curr_morale) {
	wp_redirect(get_permalink(3360).'?fail=2');
	exit;
}

/* validate attacker has sufficient turns */
if ($attack_cost_turns > $attack_curr_turns) {
	wp_redirect(get_permalink(3360).'?fail=1');
	exit;
}

/* deduct attack cost */
$attack_new_turns = $attack_curr_turns - $attack_cost_turns;
update_user_meta($user_id, 'turns', $attack_new_turns);
$attack_new_morale = $attack_curr_morale - $attack_cost_morale;
update_user_meta($user_id, 'morale', $attack_new_morale);


/* Calculate dragon extra attack power */

$dragons = $_SESSION['attack_array']['dragon']*25;
if(empty($dragons)){
	$dragons = 0;
}

$veh_att_power = 0;
$veh_total = 0;

foreach ($attack_array as $key => $count) {
	
	/*calculate dragon extra attack power */
	if($units[$key]['type'] == 'veh'){
	$veh_att_power += $count * $units[$key]['attack'];
	$veh_total += $count;
		
	}}
	
	if($veh_total > $dragons){
		$added_dragon_damage = ($veh_att_power/$veh_total)*$dragons*0.15;
		
	}else{
		$added_dragon_damage = $veh_att_power*0.15;
	}


/* Calculate dragon extra attack power */

$apcs = $_SESSION['attack_array']['apc']*50;
if(empty($apcs)){
	$apcs = 0;
}

$inf_att_power = 0;
$inf_total = 0;

foreach ($attack_array as $key => $count) {
	
	/*calculate dragon extra attack power */
	if($units[$key]['type'] == 'inf'){
	$inf_att_power += $count * $units[$key]['attack'];
	$inf_total += $count;
		
	}}
	
	if($inf_total > $apcs){
		$added_apc_damage = ($inf_att_power/$inf_total)*$apcs*0.15;
		
	}else{
		$added_apc_damage = $inf_att_power*0.15;
	}


	
/* Calculate carrier extra attack power */

$carriers = $_SESSION['attack_array']['carrier']*25;
if(empty($carriers)){
	$carriers = 0;
}

$air_att_power = 0;
$air_total = 0;

foreach ($attack_array as $key => $count) {
	
	/* calculate carrier extra attack power */
	if($units[$key]['type'] == 'air'){
	$air_att_power += $count * $units[$key]['attack'];
	$air_total += $count;
		
	}}
	
	if($air_total > $carriers){
		$added_carrier_damage = ($air_att_power/$air_total)*$carriers*0.15;
		
	}else{
		$added_carrier_damage = $air_att_power*0.15;
	}

	
/* iterate over attack array */
/* damage by type */
$attacker_type_damage = array();

foreach ($attack_array as $key => $count) {
	
	$owned_units = get_user_meta($user_id, $key.'_owned',true);
	
	if($count > $owned_units){
		$count = $owned_units;
	}else{
		$count = $owned_units*$_SESSION[$key]['percentage'];
		
	}
	
	

	/* distribute attack power equally across types */
	$atk_types = $units[$key]['attacks'];
	$type_count = count($atk_types);
	if($units[$key]['type'] == 'veh'){
		$atk_power_total = ($count * $units[$key]['attack'])+$added_dragon_damage;
		$atk_power_distrib = $atk_power_total / $type_count;
	}
	elseif($units[$key]['type'] == 'inf'){
		$atk_power_total = ($count * $units[$key]['attack'])+$added_apc_damage;
		$atk_power_distrib = $atk_power_total / $type_count;
	}
	elseif($units[$key]['type'] == 'air'){
		$atk_power_total = ($count * $units[$key]['attack'])+$added_carrier_damage;
		$atk_power_distrib = $atk_power_total / $type_count;
	}
	else{
		$atk_power_total = $count * $units[$key]['attack'];
		$atk_power_distrib = $atk_power_total / $type_count;	
	}

	/* damage per unit */
	$attacker_single_unit_damage = array();
	foreach($atk_types as $type) {
		$one_type = array($type);

		/* calculate attack totals by type */
		if (array_key_exists($type, $attacker_type_damage))
			$attacker_type_damage[$type] += $atk_power_distrib;
		else 
			$attacker_type_damage[$type] = $atk_power_distrib;
	}
}





/* add statistics for defender and attacker */
//attacker
$attacks_made = get_user_meta($user_id, 'attacks_made', true);
update_user_meta($user_id, 'attacks_made', $attacks_made+1);
//defender
$attacks_received = get_user_meta($target_id, 'attacks_received', true);
update_user_meta($target_id, 'attacks_received', $attacks_received+1);


/* calculate power usage */
$defender_power_usage = calculate_power($target_id);
$defender_power_on = $defender_power_usage < 1;


/* calculate defense by type */
$defense_by_type = calculate_defense_by_type($target_id, $defender_power_on);
$defense_attack_type = $defense_by_type['attack'];
$defense_life_type = $defense_by_type['life'];


/* get defender breakdown to determine kills */
$defender_unit_array = create_defender_array($target_id, array_keys($attacker_type_damage));
$defender_building_total = $defender_unit_array['bld']['total_count'];


/* determine kills using unit and damage arrays */
$defender_unit_losses = calculate_unit_kills($defender_unit_array, $attacker_type_damage, $attack_type,$target_id);


/* create attacker array for calculating losses */
$attacker_unit_array = create_attacker_array($attack_array);


/* calculate attacker losses */
$attacker_unit_losses = calculate_unit_kills($attacker_unit_array, $defense_attack_type, 'defend',$target_id,$life_deduct);



/* calculate attack totals */

$attacker_total_power = 0;
foreach($attacker_type_damage as $type => $attack) {
	$valid_types = array_keys($defender_unit_array);
	//if(in_array($type, $valid_types))
		$attacker_total_power += $attack;
}
$defender_total_power = 0;
foreach($defense_attack_type as $type => $attack) {
	$valid_types = array_keys($attacker_unit_array);
	if(in_array($type, $valid_types))
		$defender_total_power += $attack;
}



/* calculate loss totals */
$attacker_loss_totals = calculate_losses($attacker_unit_losses);
$defender_loss_totals = calculate_losses($defender_unit_losses);


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
			
			$owned_blds = get_user_meta($target_id, $key,true);
			
			if($killed > $owned_blds){
				$killed = $owned_blds;
			}
			
			$lost_building_count+=$killed;
			}
			else {
			$type = 'unit';
			$count_key = $key.'_owned';
			$killed = round($killed*$defender_unit_loss_decrease);
			
			$owned_units = get_user_meta($target_id, $key.'_owned',true);
			
			if($killed > $owned_units){
				$killed = $owned_units;
			}
			$lost_unit_count+=$killed;
			
	}
}}



if($defender_total_power*1.2 <= $attacker_total_power){
	$result = 'success';
	$winner_id = $user_id;
}
else{
	$result = 'failure';
	$winner_id = $target_id;
}

	
if (
	$lost_building_count == 0 && $lost_unit_count == 0
) {
	/* attacker did no damage */
	$result       = 'failure';
	$land_stolen  = 0;
	$money_stolen = 0;
	$winner_id = $target_id;
	$attacker_extra_losses = 1.1;
	$defender_loss_decrease = 0.5;
	$defender_unit_loss_decrease = 0.9;
}




/* translate array structure for display + calculate & deduct losses */
$def_unitslost = array();
foreach($defender_unit_losses as $unit_type => $breakdown) {
	foreach($breakdown as $key => $killed) {
		if ($unit_type == 'bld') {
			
			$type = 'bld';
			$count_key = $key;
			
			if($maintarget != 'none'){
				
				if($maintarget == $buildings[$key]['targetname']){
					$killed = round($killed*$defender_loss_decrease*1.35);
					}else{
					$killed = round($killed*$defender_loss_decrease*0.60);	
				}
					
				
			}else{
				
				$killed = round($killed*$defender_loss_decrease);
			}
			
			
			
			 
			$owned_blds = get_user_meta($target_id, $key,true);
			
			if($killed > $owned_blds){
				$killed = $owned_blds;
			}
			
			$defender_buildings_lost+=$killed;
			$defender_networth_lost+=$killed*$buildings[$key]['price']*($buildings[$key]['networth']/100);
		}
		else {
			$type = 'unit';
			$count_key = $key.'_owned';
			$killed = round($killed*$defender_unit_loss_decrease);
			
			$owned_units = get_user_meta($target_id, $key.'_owned',true);
			
			if($killed > $owned_units){
				$killed = $owned_units;
			}
			
			$defender_units_lost+=$killed;
			$defender_networth_lost+=$killed*$units[$key]['price']*($units[$key]['networth']/100);
		}
		$def_unitslost[] = array(
			'type' => $type,
			$key => $killed
		);
		$prev_units = get_user_meta($target_id, $count_key)[0];
		$new_units = max($prev_units - $killed, 0);
		update_user_meta($target_id, $count_key, $new_units);
	}
}

$attacker_units_lost = 0;
$attacker_networth_lost = 0;


$att_unitslost = array();
foreach($attacker_unit_losses as $unit_type => $breakdown) {
	if(null==$breakdown)
		continue;
	foreach($breakdown as $key => $killed) {
		$killed = round($killed*$attacker_extra_losses*$life_deduct);
		
		$owned_units = get_user_meta($user_id, $key.'_owned',true);
			
			if($killed > $owned_units*$_SESSION[$key]['percentage']){
				$killed = $owned_units*$_SESSION[$key]['percentage'];
			}
		
		
		$attacker_networth_lost+=$killed*$units[$key]['price']*($units[$key]['networth']/100);
		
		$attacker_units_lost+=$killed;
		$att_unitslost[] = array(
			'type' => 'unit',
			$key => $killed
		);
		$prev_units = get_user_meta($user_id, $key.'_owned')[0];
		$new_units = max($prev_units - $killed, 0);
		update_user_meta($user_id, $key.'_owned', $new_units);
	}
}

/* recalculate built land */
$builtland = 0;
foreach ($buildings as $key => $building) {
	$ownedbuildings = get_user_meta($target_id, $key)[0];
	if ($ownedbuildings > 0) {
		$builtland += $ownedbuildings * $LAND_PER_BUILDING;
	}
}
update_user_meta($target_id, 'builtland', ceil($builtland));
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php

/* resources stolen */
$land_stolen  = 0;
$money_stolen = 0;

/* if success calculate resources stolen */
if($result == 'success'){
	
	$money     = get_user_meta($target_id, 'money')[0];
	$land      = get_user_meta($target_id, 'land')[0];
	$builtland = get_user_meta($target_id, 'builtland')[0];
	$freeland  = $land - $builtland;

	$startingbonus = get_user_meta($user_id, 'starting_bonus', true);
	if($startingbonus == 'offensive'){
	$land_stolen   = max(ceil($freeland * ($STOLEN_LAND_RATIO*2*$aggressive_multi) * resource_dice_roll()), 0);
	$money_stolen  = max(ceil($money * ($STOLEN_MONEY_RATIO*2*$aggressive_multi) * resource_dice_roll()), 0);
	}
	else{
	$land_stolen   = max(ceil($freeland * $STOLEN_LAND_RATIO * $aggressive_multi * resource_dice_roll()), 0);
	$money_stolen  = max(ceil($money * $STOLEN_MONEY_RATIO * $aggressive_multi * resource_dice_roll()), 0);
	}

	$attackermoney = get_user_meta($user_id, 'money')[0];
	$attackerland  = get_user_meta($user_id, 'land')[0];

	/* take money and land */
	update_user_meta($user_id, 'money', $attackermoney + $money_stolen);
	update_user_meta($user_id, 'land', $attackerland + $land_stolen);
	
	update_user_meta($target_id, 'money', $money - $money_stolen);
	update_user_meta($target_id, 'land', $land - $land_stolen);
	
	/* add stats */
	// attacker
	
	$money_gained_combat = get_user_meta($user_id, 'money_gained_combat', true);
	update_user_meta($user_id, 'money_gained_combat', $money_gained_combat+$money_stolen);
	
	$land_gained_combat = get_user_meta($user_id, 'land_gained_combat', true);
	update_user_meta($user_id, 'land_gained_combat', $land_gained_combat+$land_stolen);
	
	// defender
	
	$money_lost_combat = get_user_meta($target_id, 'money_lost_combat', true);
	update_user_meta($target_id, 'money_lost_combat', $money_lost_combat+$money_stolen);
	
	$land_lost_combat = get_user_meta($target_id, 'land_lost_combat', true);
	update_user_meta($target_id, 'land_lost_combat', $land_lost_combat+$land_stolen);
	
	
}

$killed = false;
if ($defender_buildings_lost >= $defender_building_total) {
	$killed = true;
	kill_player($target_id);
}
/* not a kill - handle damage */
else {
	$defender_networth_lost += $land_stolen * 0.85;
	$defender_networth = get_user_meta($target_id, 'networth')[0];
	$defender_new_nw = round($defender_networth - ceil($defender_networth_lost));
	
}

/* calculate clan points */
$clan_points = 0;
$unit_points = 0;

if($war_type != 'none' && $result == 'success') {
	
	$def_total_units = 0;
	foreach($defender_unit_array as $type => $unit_stats) {
		$def_total_units += $unit_stats['total_count'];
	}			
		
	if($def_total_units != 0 && $defender_units_lost != 0){
		$unit_points = $defender_units_lost*0.0225;    // OLD  /$def_total_units; 
	}

	$defender_networth = get_user_meta($target_id, 'networth')[0];
	if ($killed != true) {
		/* calculate points using weights - minimum of 1 */
		/*$clan_points = ceil(1 +
			(($defender_networth_lost / $defender_networth) * $POINTS_NET_WEIGHT) +
			($unit_points * $POINTS_UNITS_WEIGHT) 
		);*/
		//$clan_points = ceil(1 + $defender_networth_lost*0.00022) + $unit_points - ($attacker_networth_lost*0.000025);
		$clan_points = 5 * log($defender_networth_lost/2.4 / 400)*$aggressive_multi; 
		if($clan_points < 1){
			$clan_points = 1;
		}
		$clan_points = ceil($clan_points);
		/* points cap */
		if($clan_points > $POINTS_CAP) {
			$clan_points = $POINTS_CAP;
		}
	}
	
	/* determine points multiplier due to war */
	$war_multiplier = get_war_multiplier($war_type);
	$clan_points = ceil($clan_points * $war_multiplier);	
	
	
	
	if ($killed == true) {
		/* add stats */
		// attacker
		
		$kills_made = get_user_meta($user_id, 'kills_made', true);
		update_user_meta($user_id, 'kills_made', $kills_made+1);
		
		// defender
		
		$times_killed = get_user_meta($target_id, 'times_killed', true);
		update_user_meta($target_id, 'times_killed', $times_killed+1);
		
		if($war_type == 'mutual') {
			$clan_points = 50;
		}
		elseif($war_type == 'incoming') {
			$clan_points = 25;
		}
		elseif($war_type == 'outgoing') {
			$clan_points = 25;
		}
	}



	/* add points */
	$starting_points = get_post_meta($attack_clan_id,'clan_points',true);
	update_post_meta($attack_clan_id,'clan_points',$starting_points+$clan_points);
	/* add attacks for UA */
	$starting_attacks = get_post_meta($attack_clan_id,'ua_total',true);
	update_post_meta($attack_clan_id,'ua_total',$starting_attacks+1);
	
	/* 24H pts update */
	$_pts = get_post_meta($attack_clan_id, '24h_pts', true);
	update_post_meta($attack_clan_id,'24h_pts',$_pts+$clan_points);
}

?>	
<?php
	/* add stats */
	//attacker
	
	$units_killed = get_user_meta($user_id, 'units_killed', true);
	update_user_meta($user_id, 'units_killed', $units_killed+$defender_units_lost);
	
	$nw_damage_attacks = get_user_meta($user_id, 'nw_damage_attacks', true);
	update_user_meta($user_id, 'nw_damage_attacks', $nw_damage_attacks+$defender_networth_lost);
	
	$buildings_killed = get_user_meta($user_id, 'buildings_killed', true);
	update_user_meta($user_id, 'buildings_killed', $buildings_killed+$defender_buildings_lost);
	
	
	
	
	// defender
	
	$nw_damage_lost = get_user_meta($target_id, 'nw_damage_lost', true);
	update_user_meta($target_id, 'nw_damage_lost', $nw_damage_lost+$defender_networth_lost);
	
	$units_lost = get_user_meta($target_id, 'units_lost', true);
	update_user_meta($target_id, 'units_lost', $units_lost+$defender_units_lost);
	
	$buildings_lost = get_user_meta($target_id, 'buildings_lost', true);
	update_user_meta($target_id, 'buildings_lost', $buildings_lost+$defender_buildings_lost);
	

	
	
	
	?>
	
<center>
<?php if ($result == 'success'): ?>
<?php
/* add statistics for defender and attacker */
//attacker
$succesful_attacks = get_user_meta($user_id, 'succesful_attacks', true);
update_user_meta($user_id, 'succesful_attacks', $succesful_attacks+1);



//defender
$attacks_lost = get_user_meta($target_id, 'attacks_lost', true);
update_user_meta($target_id, 'attacks_lost', $attacks_received+1);





	
	?>
	<h2>S U C C E S S</h2>
	<p>You won the battle against <strong>
	<a href="/users/profile/?id=<?php echo $target_id;?>">
		<?php
			$playername = get_userdata($target_id);
			echo $playername->display_name;
			echo ' (#' . $target_id . ')';
		?>
	</a>
<?php
	if ($killed == true) {
		echo ' <u>and killed this player</u>';
	}
?>
</strong></p>
<?php else:	?>
	<h2>F A I L U R E</h2>
	<p>You lost the battle against <a href="/users/profile/?id=<?php
	echo $target_id;
?>">
<strong>

<?php
	$playername = get_userdata($target_id);
	echo $playername->display_name;
	echo ' (#' . $target_id . ')';
?>

</strong></a>
<?php
	if ($killed == true) {
		echo ' <u>but managed to kill this player</u>';
	}
?>
</p>
<?php
	endif;
?>

<center>
<table class="responsive-table">
	<tbody>
	<tr>
		<th colspan="3" class="report_header"><center>Battle Report</center></th>
	</tr>
	<tr>
		<td class="report_content">Money Stolen: <strong>$ 
			<?php
				echo number_format($money_stolen, 0, ',', ' ');
			?>
			</strong>
		</td>
		<td class="report_content">Land Stolen: <strong>
			<?php
				echo number_format($land_stolen, 0, ',', ' ');
			?> 
			m<sup>2</sup></strong>
		</td>
		<td class="report_content">
			<?php 
			if ($war_type != 'none') {
				?>Clan points gained: 
				<?php 
				echo $clan_points;
				?>
				<?php 
			}
			else { 
				?>No clan points gained 
				<?php 
			}
			?>
		</td>
	</tr>
	<tr>
		<th class="report_content" colspan="3"><center>Your networth decreased: <strong>$
			<?php
				echo number_format($attacker_networth_lost, 0, ',', ' ');
			?></strong></center>
		</td>
	</tr>
	<tr>
		<th class="report_content" colspan="3"><center>Enemy networth decreased: <strong>$
			<?php
				echo number_format($defender_networth_lost, 0, ',', ' ');
			?></strong></center>
		</td>
	</tr>
	<tr>
		<td class="report_content"><strong>Units Lost: 
			<?php
				echo $attacker_units_lost;
			?></strong><br/>
			<?php
			foreach ($units as $key => $order) {
				foreach ($att_unitslost as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . '<br/>';
					}
				}
			}
			?>
		</td>		
		<td class="report_content"><strong>Units Killed: 
			<?php
				echo $defender_units_lost;
			?></strong><br/>
			<?php
			foreach ($units as $key => $order) {
				foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . '<br/>';
					}
				}
			}
			?>
		</td>
			
		<td class="report_content"><strong>Buildings destroyed: 
			<?php
				echo $defender_buildings_lost;
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
		</td>
	</tr>
	</tbody>
</table>

<a class="btn btn-general" href="/attack/result/"><i class="fa fa-refresh" aria-hidden="true"></i> STRIKE AGAIN</a>
<?php 



/* create event post */
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
	'post_title'    => 'Attack made by '.$user_id.' Defender: '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $user_id
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
update_field('attacker_id',$user_id, $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);
update_field('attacktype',$attack_type, $new_event_id);
update_field('outcome',$result, $new_event_id);

update_field('nw_damage_defender',$defender_networth_lost, $new_event_id);
update_field('nw_damage_attacker',$attacker_networth_lost, $new_event_id);

update_field('defender_clan_id',$defend_clan_id, $new_event_id);
update_field('attacker_clan_id',$attack_clan_id, $new_event_id);

/* Add globals to defender */

$clan = get_user_meta($target_id, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}

/* Add globals to attacker */

$clan_att = get_user_meta($user_id, 'clan_id_user', true);
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
foreach ($clan_members_att[0] as $member_att) {
	$globals = get_user_meta($member_att, 'new_global_events', true);
	update_user_meta($member_att, 'new_global_events', $globals+1);
}}


update_field('clan_points', $clan_points, $new_event_id);
count_all_stats($target_id);
count_all_stats($user_id);

if($killed == true){
			update_post_meta($new_event_id, 'status_defender', 'death');
			update_user_meta($target_id,'status','dead');
			after_death($target_id);
			}

/* update defender land and trigger event */
$event_count = get_user_meta($target_id, 'new_events')[0];
update_user_meta($target_id, 'new_events', $event_count + 1);


/* update attacker points */
$user_pts = get_user_meta($user_id, 'user_clan_points')[0];
update_user_meta($user_id,'user_clan_points',$user_pts+$clan_points);
$last_ids = get_user_meta($user_id, 'last_attacked', true);
update_user_meta($user_id, 'last_attacked', $target_id.','.$last_ids);

?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>