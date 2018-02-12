<div class="tab-pane <?php echo $activeTab === 'provicenw' ? 'active' : ''; ?>"  id="provicenw" role="tabpanel">

<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-1"></div>
		<div class="col-md-4"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Networth</strong></div>
		<div class="col-md-4"><strong>Clan</strong></div>
	</div>
			<?php


			$position   = 0;

			foreach ($toplistArray['provnw'] as $user) :
				$user_ID = $user;
				$userData = get_user_meta($user_ID);
				$user_NW = $userData['networth'][0];
				$user_land = $userData['land'][0];
				$clan_id = $userData['clan_id_user'][0];?>


<div class="row clan_profile_row2">
		
	<div class="col-md-1">
		<div class="positionNo">
			<?php echo $position += 1; ?>
		</div>
	</div>
	
	
	<div class="col-md-1">
		<?php echo small_avatar($user_ID,'');?>
	</div>
	
	
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">
		<?php echo get_user_name($user_ID);?>		
	</div>
	
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2">
			<?php echo networth_range($user_ID);?>
		</span>
	</div>
	

	
	<div class="col-md-4 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
				echo 'Clanless';}else{
				echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
					}?>	
	
	</div>
</div> <! // Close profile row -- >

<?php endforeach; ?>
</div>


</div> <!-- Close tab pane 1 -->