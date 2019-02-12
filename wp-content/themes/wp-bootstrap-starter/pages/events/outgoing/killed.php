<div class="fw-row row row-no-padding">

	<div class="col-xs-2 col-no-padding eventImageCol">
		<?php echo small_avatar($defender_id,'eventAvatar');?>
	</div>

	<div class="col-xs-10 col-no-padding" style="flex: 100;">
		<div class="eventMainMessage" style="background-color:#a30000;">
			You killed <?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
		</div>
		<div class="row eventResultRow">
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