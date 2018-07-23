<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($defender_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if($status_defender == 'death'):?>	
					
				You fired a Laser Beam Satellite and you killed <?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
				
				<?php else:?>
				
				You fired a Laser Beam Satellite at <?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
					
				<?php if($winner_id == $defender_id):?>
					
					and you missed the enemy base

				<?php else: ?>
					
					and you hit the enemy base
					
		<?php endif; endif;?>
		</div>
		
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($winner_id == $attacker_id):?>
				
									
				<strong>Defender losses: <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
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