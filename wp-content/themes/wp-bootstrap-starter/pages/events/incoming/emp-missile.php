<div class="fw-row row row-no-padding">
	<div class="col-xs-2 col-no-padding eventImageCol">
		<?php echo small_avatar($attacker_id,'eventAvatar');?>
	</div>

	<div class="col-xs-10 col-no-padding" style="flex: 100;">
		<div class="eventMainMessage">
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile and
			<?php if($winner_id == $defender_id):?>
				<strong>missed</strong> your base.
			<?php else: ?>
				<strong>hit</strong> your base.
			<?php endif;?>
		</div>

		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($winner_id == $attacker_id):?>
					Power decreased by 15% for 6 hours
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