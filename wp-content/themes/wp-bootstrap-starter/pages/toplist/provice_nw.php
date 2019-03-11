<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorPNW;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong>Name</strong></div>
	<div class="col-md-2 celBlock"><strong>Networth</strong></div>
	<div class="col-md-2 celBlock"><strong>Land</strong></div>
	<div class="col-md-3 celBlock"><strong>Clan</strong></div>
</div>

<?php

	$count = 0;
	$reverse = false;
	$position = 0;
	foreach ($toplistArray['provnw'] as $user) :

		$user_ID = $user;
		$clan_id = get_user_meta($user_ID, 'clan_id_user',true);
		$member_data = get_userdata($user_ID);

		$land = get_user_meta($user_ID, 'land',true);
		$last_online = get_user_meta($user_ID, 'last_online',true);


		$status = get_user_meta($user_ID,'status',true);
		if(!empty($last_online)){
		$last_seen = $timestamp - $last_online;
		}

			if($status != 'banned' && $last_seen < 1728000 && !empty($last_online[0])){

				if($count == 12){
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
		$position++;
			?>

	<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColorPNW;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol"><div class="positionNo"><?php echo $position;?></div>
			<?php echo small_avatar($user_ID,'allUsersAvatar');?><span class="mobileUserName"><?php echo get_user_name($user_ID);?></span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol">

		<?php echo get_user_name($user_ID);?>

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Networth</span>
		<span class="columnDataRight store-pop-span2">

			<?php echo networth_range($user_ID);?>

		</span>

	</div>
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Land</span>
		<span class="columnDataRight land">
		<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
		</span>
	</div>

	<div class="col-md-3 celBlock">

		<?php if($clan_id == 0){
						echo 'Clanless';}else{
						echo '<a href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
							}?>

	</div>
</div> <!-- //Close profile row -->

<?php } endforeach;?>