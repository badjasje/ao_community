<div class="tab-pane <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>" id="clannw" role="tabpanel" >

<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-1"></div>
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-4"><strong>Clan networth</strong></div>

	</div>
	
	
		<?php

		$position = 0;
		$args = array(
			'orderby'    	=> 'meta_value_num',
			'post_type'		=>	'clan',
			'posts_per_page' => -1,
			'meta_key' 		=> 'clan_networth',
			'order'     	 => 'DESC');
		$clans = get_posts($args);
		foreach ($clans as $clan) {

			$clan_members = get_post_meta($clan->ID,'clan_members');

			$tot_networth = 0;
			foreach ($clan_members[0] as $member) {
				$networth = get_user_meta($member, 'networth');
				$tot_networth+=$networth[0];}
			update_post_meta($clan->ID,'clan_networth',ceil($tot_networth));
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
		
		<span class="clan_data_left">Clan networth</span>
		<span class="clan_data_right store-pop-span2">
			$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth',true), 0, ',', ' ')?>
		</span>
	
	</div>
</div> <! // Close profile row -- >
			
			

		<?php }?>
	</div>
</div>