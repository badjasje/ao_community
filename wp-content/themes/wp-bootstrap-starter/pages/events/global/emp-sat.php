<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
	<?php if(in_array($attacker_id, $members[0])) { // Clan member is attacker?>
						
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> fired an EMP satellite at
						
						
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?> and 
						
			
			<?php if($winner_id == $attacker_id){?>
				hit the enemy base.
			<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
				
			<?php }}?>
						
						
		<?php if(in_array($defender_id, $members[0])) { //Clanmember is defender?>
			<?php echo clan_tag($attacker_id);?> <?php echo get_user_name($attacker_id);?> fired an EMP satellite at
					
			<?php echo clan_tag($defender_id);?> <?php echo get_user_name($defender_id);?>
		
			 and 
						
			<?php if($winner_id == $defender_id){?>
					missed the base.
				<?php } else { ?>
				<strong>hit the base</strong>
			<?php }}?>

				

		</div>
		
		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">
				<?php if($winner_id == $attacker_id): $powerReduction = $eventData['nw_damage_defender'][0];?>
					Power decreased by <?php echo $powerReduction;?>% for 6 hours
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