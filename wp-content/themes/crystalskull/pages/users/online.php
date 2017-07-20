<div class="storeDetails-heads button_block sortingHeadMob">
	<center>
	<strong>Sort:</strong> <a href="" class="sort4" data-sort=".memberField">Name</a> - 
	<a href="" class="sort4 sort-number" data-sort=".store-pop-span2">Networth</a> -
	<a href="" class="sort4 sort-number" data-sort=".land">Land</a>
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
		$timestamp = current_time('timestamp');
		$user_ID = $allUser->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		$extraClass = '';
		$counter++;
		if($counter == $NRmembers){
			$extraClass = '_last';
			
		}
	
		
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}
	
		if($last_seen < 7200 && !empty($last_online)) {
			?>
			
	<div class="row clan_profile_row4">
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

<?php  }}?>

<div id="result"></div>
</div>
</div>
