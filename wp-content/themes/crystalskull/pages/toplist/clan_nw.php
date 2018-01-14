<div class="tab-pane <?php echo $activeTab === 'clannw' ? 'active' : ''; ?>" id="clannw" role="tabpanel" >

<div class="row toplist_block">
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-1"></div>
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Land</strong></div>
		<div class="col-md-2"><strong>Networth</strong></div>

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
			$tot_land = 0;
			foreach ($clan_members[0] as $member) {
				$tot_networth += get_user_meta($member, 'networth')[0];
				$tot_land     += get_user_meta($member, 'land', true);
            }
			update_post_meta($clan->ID,'clan_networth',ceil($tot_networth));
			update_post_meta($clan->ID,'clan_land',    ceil($tot_land));
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

	<div class="col-md-2 clan_column">

		<span class="clan_data_left">Land</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo number_format(get_post_meta($clan->ID, 'clan_land',true), 0, ',', ' ')?> m&#178;
		</span>

	</div>

	<div class="col-md-2 clan_column">

		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
			$ <?php echo number_format(get_post_meta($clan->ID, 'clan_networth',true), 0, ',', ' ')?>
		</span>

	</div>



</div> <! // Close profile row -- >



		<?php }?>
	</div>
</div>