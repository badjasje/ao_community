<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if(in_array($attacker_id, $members[0])): // attack by clanmember ?>
						
		
		<!-- attacker -->
		<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> attacked
		
		<!-- defender -->	
		<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> and 
						
		
		<?php if($winner_id == $attacker_id){?>
		won the battle.<br/>
		
		<?php if($clan_points != 0  && !empty($clan_points)):?>
			<?php echo $clan_points;?> clan points gained.
		<?php endif;?>
		
		<?php } else { ?>
		
		<strong>lost the battle</strong>
		<?php }?>
	
	<?php endif;?>
						
						
	
	<?php if(in_array($defender_id, $members[0])): // defense by clan member ?>
						
						
		<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> was attacked by
						
						
		<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> and 
						
		<?php if($winner_id == $attacker_id){?>
		lost the battle. <br/>
	
		
		<?php } else { ?>
		<strong>won the battle.</strong>
			<?php if($defender_points != 0):?>
				<br/><?php echo $defender_points;?> clan point<?php if($defender_points>1){echo 's';}?> gained for successful base defense.
			<?php endif;?>
		<?php }?>
	<?php endif;?>
		</div>
		
		<div class="row eventResultRow">
		
				<div class="col-md-12 col-no-padding">
					<p>In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> and 
					<strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.</p>
					<p><strong>Attacker losses: <?php echo $att_tot_unitslost;?> units</strong>
				
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
				<p><strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong>
				<?php if($def_tot_unitslost > 0){echo '<br/>';}
				foreach ($units as $key => $order) {
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