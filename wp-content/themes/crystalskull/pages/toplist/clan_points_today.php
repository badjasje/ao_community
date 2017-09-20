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
		$args = array(
			'orderby'    	=> 'meta_value_num',
			'posts_per_page' => -1,
			'post_type'		=>	'clan',
			'meta_key' 		=> '24h_pts',
			'order'     	 => 'DESC');
		$clans = get_posts($args);

		foreach ($clans as $clan) {



			?>
			
	<div class="row clan_profile_row2">
		
	<div class="col-md-1">
		<div class="positionNo">
			<?php echo $position+=1;?>
		</div>
	</div>
	
	
	<div class="col-md-1">
		<?php echo clan_avatar($clan->ID,'');?>
	</div>
	
	
	<div class="col-md-6 clan_column center_clan_col border_bottom_mobile">
		<a href="<?php echo get_permalink($clan); ?>"><?php echo $clan->post_title.' (#'.$clan->ID.')';?></a>		
	</div>
	
	

	
	<div class="col-md-4 clan_column">
		
		<span class="clan_data_left">Clan points</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo ceil(get_post_meta($clan->ID, '24h_pts',true));?>
		</span>
	
	</div>
</div> <! // Close profile row -- >
			

		<?php }?>
	</div>
</div>