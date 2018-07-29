<?php
	$missile_type = $eventData['missile_type'][0];
	
	if(empty($missile_type)){
		$missile_name = 'Missile';
	}
	if($missile_type == 'nuke'){ $missile_name = 'Nuclear Missile'; }
	if($missile_type == 'chemical'){ $missile_name = 'Chemical Missile'; }
	if($missile_type == 'bio'){ $missile_name = 'Biochemical Missile'; }
	if($missile_type == 'moab'){ $missile_name = 'MOAB'; }
	
?>

<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if(in_array($attacker_id, $members[0])) { // Clanmember is attacker ?>
			<?php if($eventData['shotdown'][0] == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> shot down the <?php echo $missile_name;?> of
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?>
			
			
			<?php }else {?>
						
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> launched a <?php echo $missile_name;?> at
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> and 
						
			
			<?php if($winner_id == $attacker_id){?>
			
				hit the enemy base.
				
				<?php if($clan_points != 0  && !empty($clan_points)):?>
					<?php echo $clan_points;?> clan point<?php echo plural_func($clan_points);?> gained.
				<?php endif;?>
			
			<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
			
			<?php }}}?>
						
						
		
		
		
		<?php if(in_array($defender_id, $members[0])) { // Clanmember is defender ?>
			<?php if($eventData['shotdown'][0] == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> shot down the <?php echo $missile_name;?> of
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?>
			
			
			<?php }else {?>
			
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> launched a <?php echo $missile_name;?> at 
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
						
						
			
			<?php if($winner_id == $attacker_id){?>
				and <strong>hit</strong> the base.

			<?php } else { ?>
		
				and <strong>missed the base.</strong>
						
			<?php }}}?>
		</div>
		
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($winner_id == $attacker_id):?>
				
									
				<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
				?><br/>

				<strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
				<?php
				foreach ($units as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php else:?>
				No losses!
				<?php endif;?>
			</div>
		
		
		
		
	</div>
	
</div>
<div class="row statusBlockButtons eventFooter">

		<div class="col-md-3 totalsField statCol-1">
			<?php echo human_time_diff( $timeattacked, $timestamp );?> ago
		</div>
		<div class="col-md-3 totalsField statCol-2">
			Defender NW lost: $ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?>
		</div>
		<div class="col-md-3 totalsField statCol-3">
			Attacker NW lost: $ <?php echo number_format($attacker_NW_lost, 0, ',', ' '); ?>
		</div>
		<div class="col-md-3 totalsField statCol-4">
			Land stolen: <?php echo number_format($landlost, 0, ',', ' '); ?>m<sup>2</sup>
		</div>
</div>