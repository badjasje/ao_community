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
		<?php if($status_defender == 'death'):?>	
					
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> hit your base with a <?php echo $missile_name;?> and <strong>you died</strong> 
			
			<?php else:?>
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched a <?php echo $missile_name;?> at your base and 
				
			<?php if($winner_id == $defender_id):?>
			<?php if($eventData['shotdown'][0] == 'shotdown'){?>
					you shot down the missile.
				<?php } else {?>
				<strong>missed</strong> your base.
				<?php }?>

				
			<?php else: ?>
				
				<strong>hit</strong> your base. 
				
			<?php endif; endif;?>
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