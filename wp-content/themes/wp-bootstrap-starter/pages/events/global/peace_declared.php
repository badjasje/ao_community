<?php 	
	$declaring_clan = $eventData['attacker_clan_id'][0];
	$declared_clan = $eventData['defender_clan_id'][0];?>
<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if($clan_ID == $declaring_clan):?>
				
				Declared peace on <a href="<?php echo get_the_permalink($declared_clan);?>">
				<?php echo get_the_title($declared_clan);?> (#<?php echo $declared_clan;?>)</a>
				
				<?php elseif($clan_ID == $declared_clan):?>
				
				<a href="<?php echo get_the_permalink($declaring_clan);?>">
				<?php echo get_the_title($declaring_clan);?> (#<?php echo $declaring_clan;?>)</a> 
				declared peace against your clan.
				
		<?php endif;?>
	</div>
		
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				Message: <?php echo $eventData['dec_message'][0];?>
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