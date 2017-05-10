<div class="storeDetails-heads button_block sortingHeadMob">
	<center>
	<strong>Sort:</strong> <a href="" class="sort3" data-sort=".memberField">Name</a> - 
	<a href="" class="sort3 sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort3 sort-number" data-sort=".land">Land</a>
	</center>
</div>

<div class="row toplist_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-1"></div>
		<div class="col-md-4"><strong><a href="" class="sort3" data-sort=".memberField">Name</a></strong></div>
		<div class="col-md-2"><strong><a href="" class="sort3 sort-number" data-sort=".store-pop-span2">Networth</a></strong></div>
		<div class="col-md-2"><strong><a href="" class="sort3 sort-number" data-sort=".land">Land</a></strong></div>
		<div class="col-md-3"><strong>Clan</strong></div>
	</div>
	
<div id="values3">
	
	<?php 

	$NRmembers = count($allUsers);
	$counter = 0;
	foreach ($allUsers as $allUser) {
		
		$user_ID = $allUser->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
		$networth = get_user_meta($user_ID, 'networth',true);
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
		$inRangeClass = '';
		if (($networth > $networth_you/$ATTACK_RANGE_MULT && $networth < $networth_you*$ATTACK_RANGE_MULT)){
			$inRangeClass = 'inRange';
		}
		
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
		if (($networth > $networth_you/$ATTACK_RANGE_MULT && $networth < $networth_you*$ATTACK_RANGE_MULT)){
		if($last_seen < 1728000 && !empty($last_online[0])){
			?>
			
	<div class="row clan_profile_row3">
		<div class="col-md-1">
			<?php echo small_avatar($user_ID,'');?>
		</div>
	
	<div class="col-md-4 clan_column center_clan_col border_bottom_mobile">

		<a class="memberField <?php echo get_user_meta($user_ID,'status',true);?>" href="/users/profile/?id=<?php echo $user_ID;?>">
			<?php echo $member_data->display_name.' (#'.$user_ID.')';?></a> 
			<?php if(!empty($last_online)){
					if($last_seen < 7200 && !empty($last_online[0])){
						echo ' <span style="color:#ff0000">*</span>';
						}
					}?>			

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Networth</span>
		<span class="clan_data_right store-pop-span2 <?php echo $inRangeClass;?>">
		
			$ <?php echo number_format($networth, 0, ',', ' '); ?>
					
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Land</span>
		<span class="clan_data_right land">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-3 clan_column center_clan_col">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
</div> <! // Close profile row -->

<?php  }}}?>

<div id="result"></div>
</div>
</div>
