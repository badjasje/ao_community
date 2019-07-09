<div class="fw-row row row-no-padding">

	<div class="col-xs-2 col-no-padding eventImageCol">
		<?php echo small_avatar($defender_id,'eventAvatar');?>
	</div>

	<div class="col-xs-10 col-no-padding" style="flex: 100;">
		<div class="eventMainMessage">
			<?php if($status_defender == 'death'):?>
				You attacked <?php clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> and killed this player
			<?php else:?>
				You attacked <?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> and you
				<?php if($winner_id == $attacker_id):?>
					&nbsp;won the battle.
				<?php else: ?>
					&nbsp;lost the battle.
				<?php endif; ?>
			<?php endif;?>
		</div>
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if(!empty($attackmode) && !empty($maintarget)) { ?>
				<p>Attackmode: <em><?=$attackmode?></em>, maintarget: <em><?=$maintarget?></em>, morale: <em><?=$moralecost?>%</em></p>
				<?php } ?>
				<p>
					In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> and
					<strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.
				</p>
				<p>
					<strong>Attacker losses: <?php echo $att_tot_unitslost;?> units</strong>
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
				<p>
					<strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong>
					<?php if($def_tot_unitslost > 0){echo '<br/>';}
					foreach ($units as $key => $order) {
						foreach ($def_unitslost as $def_unitlost) {
							if (isset($def_unitlost[$key])) {
								echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
							}
						}
					}

					if($def_tot_buildingslost > 0){echo '<br/>';}

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

</div><!-- end fw-row -->