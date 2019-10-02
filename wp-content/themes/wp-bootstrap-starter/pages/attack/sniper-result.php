<?php
$units = Units::get();

$moralecost = Settings::get('sniper_morale_cost');
$attSnipers = $attackerData['sniper_owned'][0];
$no_snipers = $attSnipers*$_POST['attackarray']['sniper'];

if($no_snipers > $attSnipers){
	$no_snipers = $attSnipers;
}

/* Check if attacker has enough turns */
$turns = $attackerData['turns'][0];
if($turns < 2){
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* Check if attacker has enough snipers */
$ownedSnipers = get_user_meta( $userId, 'sniper_owned', true );
if($no_snipers > $ownedSnipers){
	$array['status'] = 'Not enough snipers';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* Stop the user from sending more than 10 snipers */
if($no_snipers > 10){
	$array['status'] = 'You can only send a maximum of 10 snipers';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

$defender_thiefs = $defenderData['thief_owned'][0];
$defender_spies = $defenderData['spy_owned'][0];
$defender_snipers = $defenderData['sniper_owned'][0];
$defender_saboteurs = $defenderData['saboteur_owned'][0];

$result = 'failure';
$winner_id = $target_id;

$loseRatio = $no_snipers*(1+(mt_rand(95,140)/100))+$defender_snipers/(mt_rand(27,49)/10);

if($loseRatio < 64){
	$result = 'success';
}

if($result == 'success'){
	$winner_id = $userId;
	$tot_sniper_attackpower = ($units['sniper']['attack']-30)*$no_snipers*(mt_rand(200,500)/100);
	$attackerLost = min(round($no_snipers*mt_rand(6,20)/70),$no_snipers,$attSnipers);
}

$thief_life = $units['thief']['life']*$defender_thiefs;
$spy_life = $units['spy']['life']*$defender_spies;
$sniper_life = ($units['sniper']['life']-50)*$defender_snipers;
$saboteur_life = ($units['saboteur']['life'])*$defender_saboteurs;

$tot_life = $thief_life+$spy_life+$sniper_life+$saboteur_life;

$spy_damage = $tot_sniper_attackpower*($spy_life/$tot_life);
$thief_damage = $tot_sniper_attackpower*($thief_life/$tot_life);
$sniper_damage = $tot_sniper_attackpower*($sniper_life/$tot_life);
$saboteur_damage = $tot_sniper_attackpower*($saboteur_life/$tot_life);


$snipers_lost = min(round($sniper_damage/$units['sniper']['life']),$defender_snipers);
$thiefs_lost = min(round($thief_damage/$units['thief']['life']),$defender_thiefs);
$spy_lost = min(round($spy_damage/$units['spy']['life']),$defender_spies);
$saboteur_lost = min(round($saboteur_damage/$units['saboteur']['life']),$defender_saboteurs);

$totalDefLost = $snipers_lost+$thiefs_lost+$spy_lost+$saboteur_lost;

/* determine NW lost */
$attNWlost = $attackerLost*$units['sniper']['price']*0.06;
$defNWlost = ($snipers_lost*$units['sniper']['price']*0.06)+($thiefs_lost*$units['thief']['price']*0.06)+($spy_lost*$units['spy']['price']*0.06)+($saboteur_lost*$units['saboteur']['price']*0.06);
?>
<?php if($result == 'failure'): ?>
	<div class="blockHeader">
		Your sniper<?php echo plural_func($no_snipers);?> were killed in action
	</div>
<?php else:?>
	<div class="blockHeader">
		Your sniper<?php echo plural_func($no_snipers);?> entered the base of <?php echo get_user_name($target_id);?>
		<?php if($totalDefLost == 0) { echo ' but there were no units to kill.'; } ?>
	</div>
<?php endif;?>

<div class="battleReportInfo statCol-1">Sniper report</div>
<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">Money stolen: $ <?php echo number_format(0, 0, ',', ' ');?></div>
	<div class="col-md-4 battleReportInfo statCol-3">Land stolen: <?php echo number_format(0, 0, ',', ' ');?>m<sup>2</sup></div>
	<div class="col-md-4 battleReportInfo statCol-4">
		No clan points gained
	</div>
</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.56);">
		Your networth decreased: $ <?php echo number_format($attNWlost, 0, ',', ' ');?>
	</div>
	<div class="col-md-8 battleReportInfo" style="background-color: rgba(45, 67, 81, 0.48);">
		Enemy networth decreased: $ <?php echo number_format($defNWlost, 0, ',', ' ');?>
	</div>
</div>

<div class="row statusBlockButtons">
	<div class="col-md-4 battleReportInfo statCol-2">
		Units Lost: <?php echo $attackerLost;?>
		<?php if($attackerLost > 0){ echo '<br/>Snipers: '.$attackerLost; }?>
	</div>
	<div class="col-md-4 battleReportInfo statCol-3">
		<strong>Special units killed: <?php echo $totalDefLost;?></strong>
		<?php if($totalDefLost>0){ echo '<br/>'; }?>
		<?php if($snipers_lost > 0){ echo 'Snipers: '.$snipers_lost.'<br/>'; }?>
		<?php if($thiefs_lost > 0){ echo 'Thiefs: '.$thiefs_lost.'<br/>'; }?>
		<?php if($spy_lost > 0){ echo 'Spies: '.$spy_lost.'<br/>'; }?>
		<?php if($saboteur_lost > 0){ echo 'Saboteurs: '.$saboteur_lost.'<br/>'; }?>
	</div>
	<div class="col-md-4 battleReportInfo statCol-4">
		Buildings destroyed: none
	</div>
</div>

<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Strike Again</div>

<?php
$def_unitslost = array();
$att_unitslost = array();

/* create defender units lost array */
if($snipers_lost > 0){
	$def_unitslost[] = array('type' => 'unit', 'sniper' => $snipers_lost);
}

if($thiefs_lost > 0){
	$def_unitslost[] = array('type' => 'unit', 'thief' => $thiefs_lost);
}

if($spy_lost > 0){
	$def_unitslost[] = array('type' => 'unit', 'spy' => $spy_lost);
}

/* create attacker units lost array */
if($attackerLost > 0){
	$att_unitslost[] = array('type' => 'unit', 'sniper' => $attackerLost);
}

/* update defender units */
update_user_meta($target_id, 'thief_owned', $defender_thiefs-$thiefs_lost);
update_user_meta($target_id, 'sniper_owned', $defender_snipers-$snipers_lost);
update_user_meta($target_id, 'spy_owned', $defender_spies-$spy_lost);
update_user_meta($target_id, 'saboteur_owned', $defender_saboteurs-$saboteur_lost);

/* update attacker units */
update_user_meta($userId, 'sniper_owned', $attSnipers-$attackerLost);

////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(
	'post_title'    => 'Snipers sent by '.$userId.' Defender: '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);
$new_event_id = wp_insert_post( $args );

update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
update_field('defender_lost', $def_unitslost, $new_event_id);
update_field('attacker_lost', $att_unitslost, $new_event_id);

update_field('time_attacked',$timestamp, $new_event_id);
update_field('defender_id',$target_id, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('attacktype','sniper', $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);
update_field('moralecost', $moralecost, $new_event_id);

update_field('def_total_units_lost', $totalDefLost , $new_event_id);
update_field('att_total_units_lost',$attackerLost, $new_event_id);

update_field('nw_damage_defender', $defNWlost , $new_event_id);
update_field('nw_damage_attacker', $attNWlost , $new_event_id);

update_user_meta($userId,'turns',$turns-2);
turn_spread('sniper',2);
update_user_meta($userId, 'morale', $oldmorale - $moralecost);
update_user_meta($target_id, 'new_events', get_user_meta($target_id, 'new_events',true)+1);

/* update defender land and trigger event */
$event_count = $defenderData['new_events'][0];
update_user_meta($target_id, 'new_events', $event_count + 1);
