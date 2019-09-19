<div class="sortMobile">
	<center>
	<strong>Sort by:</strong> <a href="?tab=online&sortby=name-sort-3" class="sort3" data-sort=".name-sort-3">Name</a> -
	<a href="?tab=online&sortby=nw-sort-3" class="sort3 sort-number" data-sort=".nw-sort-3">Networth</a> -
	<a href="?tab=online&sortby=land-sort-3" class="sort3 sort-number" data-sort=".land-sort-3">Land</a>
	</center>
</div>

<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?=$backColor?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong><a href="?tab=online&sortby=name-sort-3" class="sort3" data-sort=".name-sort-3">Name <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="?tab=online&sortby=nw-sort-3" class="sort3 sort-number" data-sort=".nw-sort-3">Networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="?tab=online&sortby=land-sort-3" class="sort3 sort-number" data-sort=".land-sort-3">Land <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong>Clan</strong></div>
</div>

<div id="values3">
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
		if(!empty($last_online)){
			$last_seen = $timestamp - $last_online;
		}

		if($status != 'banned' && $last_seen < 7200 && !empty($last_online)) {
			if($count == 10) $reverse = true;
			if($reverse == true){
				$count--;
				if($count == 0) $reverse = false;
			}
			if($reverse == false) $count++;
			?>
			<div class="row fw-row userRow userRow3 row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
				<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
					<?php echo small_avatar($user_ID,'allUsersAvatar');?><span class="mobileUserName name-sort-3"><?php echo get_user_name($user_ID);?></span>
				</div>
				<div class="col-md-4 celBlock allUsersNameCol">
					<?php echo get_user_name($user_ID);?>
				</div>
				<div class="col-md-2 celBlock">
					<span class="columnDataLeft">Networth</span>
					<span class="columnDataRight nw-sort-3">
						<?php echo networth_range($user_ID);?>
					</span>
				</div>
				<div class="col-md-2 celBlock">
					<span class="columnDataLeft">Land</span>
					<span class="columnDataRight land-sort-3">
						<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
					</span>
				</div>
				<div class="col-md-3 celBlock">
					<?php if($clan_id == 0){
						echo 'Clanless';
					}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
					}?>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>