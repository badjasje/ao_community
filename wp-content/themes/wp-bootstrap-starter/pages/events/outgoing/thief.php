<?php
$thiefs_lost = $eventData['thiefs_lost'][0];

/* set unknown avatar if attacker wins */
if($winner_id != $defender_id){
	$attacker_id = 0;
}
?>
<div class="fw-row row row-no-padding">
	<div class="col-xs-2 col-no-padding eventImageCol">
		<?php echo small_avatar($defender_id,'eventAvatar');?>
	</div>

	<div class="col-xs-10 col-no-padding" style="flex: 100;">
		<div class="eventMainMessage">
			You sent thief<?php echo plural_func($thiefs_lost);?> to <?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
			<?php if($winner_id != $defender_id):?>
			<?php elseif($winner_id == $defender_id):?>
				but you were caught.
			<?php endif;?>
		</div>
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($winner_id != $defender_id):?>
					and stole <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong>
				<?php elseif($winner_id == $defender_id):?>
					<?php echo $thiefs_lost;?> thief<?php echo plural_func($thiefs_lost);?> lost.
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