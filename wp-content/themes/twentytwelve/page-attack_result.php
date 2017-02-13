<?php
/**
 * Template Name: Attack Result Template
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
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
$attack_cost_morale = $attack_cost_arr['morale'];

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

	

/* damage by type */
$attacker_type_damage = array();
/* iterate over attack array */
foreach ($attack_array as $key => $count) {

	/* distribute attack power equally across types */
	$atk_types = $units[$key]['attacks'];
	$type_count = count($atk_types);
	$atk_power_total = $count * $units[$key]['attack'];
	$atk_power_distrib = $atk_power_total / $type_count;

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

//error_log("attack_array: ".print_r($attack_array, true));
//error_log("attacker_type_damage: ".print_r($attacker_type_damage, true));

/* calculate power usage */
$defender_power_usage = calculate_power($target_id);
$defender_power_on = $defender_power_usage < 1;

/* calculate defense by type */
$defense_by_type = calculate_defense_by_type($target_id, $defender_power_on);
$defense_attack_type = $defense_by_type['attack'];
$defense_life_type = $defense_by_type['life'];

//error_log("defense_by_type: ".print_r($defense_by_type, true));

/* get defender breakdown to determine kills */
$defender_unit_array = create_defender_array($target_id, array_keys($attacker_type_damage));
$defender_building_total = $defender_unit_array['bld']['total_count'];

//error_log("defender_unit_array: ".print_r($defender_unit_array, true));

/* determine kills using unit and damage arrays */
$defender_unit_losses = calculate_unit_kills($defender_unit_array, $attacker_type_damage, $attack_type,$target_id);

//error_log("defender_unit_losses: ".print_r($defender_unit_losses, true));

/* create attacker array for calculating losses */
$attacker_unit_array = create_attacker_array($attack_array);

//error_log("attacker_unit_array: ".print_r($attacker_unit_array, true));

/* calculate attacker losses */
$attacker_unit_losses = calculate_unit_kills($attacker_unit_array, $defense_attack_type, 'defend',$target_id);

//error_log("attacker_unit_losses: ".print_r($attacker_unit_losses, true));

/* calculate attack totals */
$attacker_total_power = 0;
foreach($attacker_type_damage as $type => $attack) {
	$valid_types = array_keys($defender_unit_array);
	if(in_array($type, $valid_types))
		$attacker_total_power += $attack;
}
$defender_total_power = 0;
foreach($defense_attack_type as $type => $attack) {
	$valid_types = array_keys($attacker_unit_array);
	if(in_array($type, $valid_types))
		$defender_total_power += $attack;
}

//error_log("attacker_total_power:$attacker_total_power  |  defender_total_power:$defender_total_power");

/* calculate loss totals */
$attacker_loss_totals = calculate_losses($attacker_unit_losses);
$defender_loss_totals = calculate_losses($defender_unit_losses);

$attacker_units_lost = $attacker_loss_totals['units'];
$attacker_networth_lost = $attacker_loss_totals['networth'];

$defender_buildings_lost = $defender_loss_totals['buildings'];
$defender_units_lost = $defender_loss_totals['units'];
$defender_networth_lost = $defender_loss_totals['networth'];

//error_log("attacker_loss_totals: ".print_r($attacker_loss_totals, true));
//error_log("defender_loss_totals: ".print_r($defender_loss_totals, true));

/* determine win/loss */
if ($attacker_total_power <= 0 ||
	$defender_total_power >= $attacker_total_power ||
	$defender_buildings_lost == 0 && $defender_units_lost == 0
) {
	/* attacker did no damage */
	$result       = 'failure';
	$land_stolen  = 0;
	$money_stolen = 0;
	$winner_id = $target_id;
}
else {
	$result = 'success';
	$winner_id = $user_id;
}

/* translate array structure for display and deduct losses */
$def_unitslost = array();
foreach($defender_unit_losses as $unit_type => $breakdown) {
	foreach($breakdown as $key => $killed) {
		if ($unit_type == 'bld') {
			$type = 'bld';
			$count_key = $key;
		}
		else {
			$type = 'unit';
			$count_key = $key.'_owned';
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
$att_unitslost = array();
foreach($attacker_unit_losses as $unit_type => $breakdown) {
	if(null==$breakdown)
		continue;
	foreach($breakdown as $key => $killed) {
		$att_unitslost[] = array(
			'type' => 'unit',
			$key => $killed
		);
		$prev_units = get_user_meta($user_id, $key.'_owned')[0];
		$new_units = max($prev_units - $killed, 0);
		update_user_meta($user_id, $key.'_owned', $new_units);
	}
}

get_header(); 

?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class();?>>
<div class="entry-content">

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


	$land_stolen   = max(ceil($freeland * $STOLEN_LAND_RATIO * resource_dice_roll()), 0);
	$money_stolen  = max(ceil($money * $STOLEN_MONEY_RATIO * resource_dice_roll()), 0);

	$attackermoney = get_user_meta($user_id, 'money')[0];
	$attackerland  = get_user_meta($user_id, 'land')[0];

	/* take money and land */
	update_user_meta($user_id, 'money', $attackermoney + $money_stolen);
	update_user_meta($user_id, 'land', $attackerland + $land_stolen);
	
	update_user_meta($target_id, 'money', $money - $money_stolen);
	update_user_meta($target_id, 'land', $land - $land_stolen);
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
	update_user_meta($target_id, 'networth', $defender_new_nw);
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
		$unit_points = $defender_units_lost*0.0325;    // OLD  /$def_total_units; 
	}

	$defender_networth = get_user_meta($target_id, 'networth')[0];
	if ($killed != true) {
		/* calculate points using weights - minimum of 1 */
		/*$clan_points = ceil(1 +
			(($defender_networth_lost / $defender_networth) * $POINTS_NET_WEIGHT) +
			($unit_points * $POINTS_UNITS_WEIGHT) 
		);*/
		$clan_points = ceil(1 + $defender_networth_lost*0.00025) + $unit_points - ($attacker_networth_lost*0.000025);
		/* points cap */
		if($clan_points > $POINTS_CAP) {
			$clan_points = $POINTS_CAP;
		}
	}
	
	/* determine points multiplier due to war */
	$war_multiplier = get_war_multiplier($war_type);
	$clan_points = ceil($clan_points * $war_multiplier);	
	
	
	
	if ($killed == true) {
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



	/* add ponts */
	$starting_points = get_post_meta($attack_clan_id,'clan_points',true);
	update_post_meta($attack_clan_id,'clan_points',$starting_points+$clan_points);
}

?>	

<center>
<?php if ($result == 'success'): ?>
	<h2>S U C C E S S</h2>
	<p>You won the battle against <strong>
	<a href="/users/profile/?id=<?php echo $target_id;?>">
		<?php
			$playername = get_userdata($target_id);
			echo $playername->nickname;
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
	echo $playername->nickname;
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
<table>
	<tr>
		<th colspan="3" class="report_header"><center>Battle Report</center></th>
	</tr>
	<tr>
		<td>Money Stolen: <strong>$ 
			<?php
				echo number_format($money_stolen, 0, ',', ' ');
			?>
			</strong>
		</td>
		<td>Land Stolen: <strong>
			<?php
				echo number_format($land_stolen, 0, ',', ' ');
			?> 
			m<sup>2</sup></strong>
		</td>
		<td>
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
		<th colspan="3"><center>Your networth decreased: <strong>$
			<?php
				echo number_format($attacker_networth_lost, 0, ',', ' ');
			?></strong></center>
		</td>
	</tr>
	<tr>
		<th colspan="3"><center>Enemy networth decreased: <strong>$
			<?php
				echo number_format($defender_networth_lost, 0, ',', ' ');
			?></strong></center>
		</td>
	</tr>
	<tr>
		<td><strong>Units Lost: 
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
		<td><strong>Units Killed: 
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
			
		<td><strong>Buildings destroyed: 
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
</table>

<?php 

/* recalculate built land */
$builtland = 0;
foreach ($buildings as $key => $building) {
	$ownedbuildings = get_user_meta($target_id, $key)[0];
	if ($ownedbuildings > 0) {
		$builtland += $ownedbuildings * $LAND_PER_BUILDING;
	}
}


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


update_field('defender_clan_id',$defend_clan_id, $new_event_id);
update_field('attacker_clan_id',$attack_clan_id, $new_event_id);




update_field('clan_points', $clan_points, $new_event_id);

if($killed == true){
			update_post_meta($new_event_id, 'status_defender', 'death');
			update_user_meta($target_id,'status','dead');
			after_death($target_id);
			}

/* update defender land and trigger event */
$event_count = get_user_meta($target_id, 'new_events')[0];
update_user_meta($target_id, 'new_events', $event_count + 1);
update_user_meta($target_id, 'builtland', ceil($builtland));

/* update attacker points */
$user_pts = get_user_meta($user_id, 'user_clan_points')[0];
update_user_meta($user_id,'user_clan_points',$user_pts+$clan_points);

?>

</div><!-- .entry-content -->
</article><!-- #post -->
<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>
		
			