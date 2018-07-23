<div class="fw-row row row-no-padding">
<div class="col-xs-2 col-no-padding eventImageCol">
	<?php echo small_avatar($attacker_id,'eventAvatar');?>
</div>
	
	
<div class="col-xs-10 col-no-padding" style="flex: 100;">
	<div class="eventMainMessage">
		<?php if(in_array($attacker_id, $members[0])) { // Clanmember is attacker ?>
			
			<?php if($eventData['shotdown'][0] == 'shotdown'){?>
			
			
				<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the EMP missile of
			
			
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php } else {?>
						
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile at
						
						
				<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
			
				<?php if($winner_id == $attacker_id){?>
			
				hit the enemy base.
				
	
			
				<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
			
			<?php }}} ?>
						
						
		
		
		
		<?php if(in_array($defender_id, $members[0])) { // Clanmember is defender ?>
			<?php if($eventData['shotdown'][0] == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the EMP missile of
			
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php }else {?>
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile at 
				
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a>
						
						
			
						
			
			<?php if($winner_id == $attacker_id){?>
				and <strong>hit</strong> the base.

			<?php } else { ?>
		
				and <strong>missed the base.</strong>
						
			<?php }}}?>
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