<div class="sortMobile">
	<center>
	<strong>Sort by:</strong> <a href="" class="sort2" data-sort=".name-sort-2">Name</a> - 
	<a href="" class="sort2 sort-number" data-sort=".nw-sort-2">Networth</a> -
	<a href="" class="sort2 sort-number" data-sort=".land-sort-2">Land</a>
	</center>
</div>

<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong><a href="" class="sort2" data-sort=".name-sort-2">Name <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="" class="sort2 sort-number" data-sort=".nw-sort-2">Networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="" class="sort2 sort-number" data-sort=".land-sort-2">Land <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong>Clan</strong></div>
</div>
	

<div id="values2">
<?php 
	
	$count = 0;
	$reverse = false;
	foreach ($allUsers as $allUser) {
		
		$user_ID = $allUser->ID;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);
		$networth = get_user_meta($user_ID, 'networth',true);
	
		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);
	
		
		$status = get_user_meta($user_ID,'status',true);
		if($status == 'banned' ){ continue; }
		
		$in_range = target_in_range('', $networth_you, $networth, '');
		
		if (!$in_range) {
			continue;
		}

	
				
				if($count == 10){
					$reverse = true;
				}
				if($reverse == true){
					$count--;
					
					if($count == 0){
						$reverse = false;
					}
				}
				if($reverse == false){
					$count++;
				}
				
			?>
			
	<div class="row fw-row userRow userRow2 row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo small_avatar($user_ID,'allUsersAvatar');?><span class="mobileUserName name-sort-2"><?php echo get_user_name($user_ID);?></span>
		</div>
	
	<div class="col-md-4 celBlock allUsersNameCol name-sort-2">

		<?php echo get_user_name($user_ID);?>		

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Networth</span>
		<span class="columnDataRight nw-sort-2">
		
			<?php echo networth_range($user_ID);?>
					
		</span>

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Land</span>
		<span class="columnDataRight land-sort-2">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>
	
	<div class="col-md-3 celBlock">
		
		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>	
	
	</div>
</div> 
<?php  }?>
</div>