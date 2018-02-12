<div class="tab-pane <?php echo $activeTab === 'clanpointstoday' ? 'active' : ''; ?>" id="clanpointstoday" role="tabpanel">


<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-1"></div>
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-4"><strong>Clan points</strong></div>

	</div>
	

		<?php

		$position = 0;
		foreach ($toplistArray['24h_pts'] as $clan) {



			?>
			
	<div class="row clan_profile_row2">
		
	<div class="col-md-1">
		<div class="positionNo">
			<?php echo $position+=1;?>
		</div>
	</div>
	
	
	<div class="col-md-1">
		<?php echo clan_avatar($clan,'');?>
	</div>
	
	
	<div class="col-md-6 clan_column center_clan_col border_bottom_mobile">
		<a href="<?php echo get_permalink($clan); ?>"><?php echo get_the_title($clan).' (#'.$clan.')';?></a>		
	</div>
	
	

	
	<div class="col-md-4 clan_column">
		
		<span class="clan_data_left">Clan points</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo ceil(get_post_meta($clan, '24h_pts',true));?>
		</span>
	
	</div>
</div> <! // Close profile row -- >
			

		<?php }?>
	</div>
</div>