<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if($status_defender == 'death'):?>	
						
			You were attacked by <?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and <strong>you died</strong> 
					
			<?php else:?>
					
			You were attacked by <?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and you 
						
			<?php if($winner_id == $defender_id):?>
			<strong>won</strong> the battle.
						
			<?php if($defender_points != 0):?>
			<br/><?php echo $defender_points;?> clan point<?php if($defender_points>1){echo 's';}?> gained for successful base defense.
			<?php endif;?>
						
		<?php else: ?>
						
			<strong>lost</strong> the battle. 
			
						
		<?php endif; endif;?>
		</div>
		
		<div class="row eventResultRow">
		
				<div class="col-md-12 col-no-padding">
					<p>In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> and 
					<strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.</p>
					<p>Attacker losses: <?php echo $att_tot_unitslost;?> units
				
				<?php
				if($att_tot_unitslost > 0){echo '<br/>';}
				foreach ($units as $key => $order) {
					foreach ($att_unitslost as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
			
				?>
				</p>
				<p>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings
				<?php
				foreach ($units as $key => $order) { if($def_tot_unitslost > 0){echo '<br/>';}
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php if($def_tot_buildingslost > 0){echo '<br/>';}
				foreach ($buildings as $key => $order) { 
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				</p>
				
				<?php if(($tomahawkHit)>0):?>
				<br/><br/><?php echo ($tomahawkHit);?> tomahawk<?php echo plural_func($tomahawkHit);?> hit your base<br/>
				<?php endif;?>
				<?php if($tomahawkDown > 0):?>
				<?php echo $tomahawkDown;?> tomahawk<?php echo plural_func($tomahawkDown);?> shot down
				<?php endif;?>				
				</div>
			
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