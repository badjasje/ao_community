<?php $kicked_clan = $eventData['attacker_clan_id'][0];?>
<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($defender_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php echo get_user_name($defender_id);?> 
	</div>
		
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($outcome == 'kicked'):?>
					was kicked by <?php echo get_user_name($attacker_id);?>
					<?php echo $clan_points;?> clan points lost.
				<?php elseif($outcome == 'joined'):?>
					joined your clan.
				<?php elseif($outcome == 'left'):?>
					left your clan.
					<?php echo $clan_points;?> clan points lost.
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