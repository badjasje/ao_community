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
		foreach ($toplistArray['clannetworth'] as $clanid) {


			
			?>

<div class="row clan_profile_row2">

	<div class="col-md-1">
		<div class="positionNo">
			<?php echo $position+=1;?>
		</div>
	</div>


	<div class="col-md-1">
		<?php echo clan_avatar($clanid,'');?>
	</div>


	<div class="col-md-6 clan_column center_clan_col border_bottom_mobile">
		<a href="<?php echo get_permalink($clanid); ?>"><?php echo get_the_title($clanid).' (#'.$clanid.')';?></a>
	</div>

	<div class="col-md-2 clan_column">

		<span class="clan_data_left">Land</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo number_format(get_post_meta($clanid, 'clan_land',true), 0, ',', ' ')?> m&#178;
		</span>

	</div>

	<div class="col-md-2 clan_column">

		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
			$ <?php echo number_format(get_post_meta($clanid, 'clan_networth',true), 0, ',', ' ')?>
		</span>

	</div>



</div> <! // Close profile row -- >



		<?php }?>
	</div>
</div>