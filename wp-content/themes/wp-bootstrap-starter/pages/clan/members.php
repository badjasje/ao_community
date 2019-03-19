<?php if(!in_array($declarer_ID, $clanMembers)) { ?>

	<div class="sortMobile fw-row">
		<center>
		<strong>Sort by:</strong> <a href="" class="sort6" data-sort=".name-sort">Name</a> -
		<a href="" class="sort6 sort-number" data-sort=".nw-sort">Networth</a> -
		<a href="" class="sort6 sort-number" data-sort=".land-sort">Land</a>
		</center>
	</div>

	<div class="row headerRow fw-row row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
		<div class="col-md-1 celBlock"></div>
		<div class="col-md-3 celBlock"><strong><a href="" class="sort6" data-sort=".name-sort">Name <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-4 celBlock"><strong><a href="" class="sort6 sort-number" data-sort=".nw-sort">Networth <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-4 celBlock"><strong><a href="" class="sort6 sort-number" data-sort=".land-sort">Land <i class="fas fa-sort"></i></a></strong></div>
	</div>

	<div id="values6" class="fw-row">
		<?php
		$count = 0;
		$reverse = false;
		foreach ($clanMembers as $allUser) {
			$userId = $allUser;
			$clan_id = get_user_meta($userId, 'clan_id_user',true);
			$member_data = get_userdata($userId);

			$land = get_user_meta($userId, 'land',true);
			$last_online = get_user_meta($userId, 'last_online',true);

			$status = get_user_meta($userId,'status',true);
			if(!empty($last_online)) $last_seen = $timestamp - $last_online;

			if($count == 30) $reverse = true;
			if($reverse == true){
				$count--;
				if($count == 0) $reverse = false;
			}
			if($reverse == false) $count++;
			?>
			<div class="row fw-row userRow userRow6 row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
				<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
					<?php echo small_avatar($userId,'allUsersAvatar');?>
					<span class="mobileUserName">
						<div class="ctclField">
							<?php if($userId == $clanleader) {
								echo '<strong>CL</strong>';
							} ?>
							<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4 ){
								echo '<strong>CT</strong>';
							} ?>
						</div>
						<span class="name-sort"><?php echo get_user_name($userId);?></span>
					</span>
				</div>

				<div class="col-md-3 celBlock allUsersNameCol">
					<div class="ctclField">
						<?php if($userId == $clanleader) {
							echo '<strong>CL</strong>';
						} ?>
						<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4 ){
							echo '<strong>CT</strong>';
						} ?>
					</div>
					<span class="name-sort"><?php echo get_user_name($userId);?></span>
				</div>

				<div class="col-md-4 celBlock">
					<span class="columnDataLeft">Networth</span>
					<span class="columnDataRight nw-sort">
						<?php echo networth_range($userId);?>
					</span>
				</div>

				<div class="col-md-4 celBlock">
					<span class="columnDataLeft">Land</span>
					<span class="columnDataRight land-sort">
					<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
					</span>
				</div>
			</div> <!-- // Close profile row -->
			<?php
		} ?>
	</div>

	<?php
	// range checker
	$inRange = 'no';
	$warText = 'war';
	if($tot_networth > $dec_tot_networth/1.4 && $tot_networth < $dec_tot_networth*1.4){
		$inRange = 'yes';
		$warText = 'war';
	}
	if($warcount == 1){
		$inRange = 'yes';
		$warText = 'mutual war';
	}
	// can peace clan?
	$canPeace = false;
	if($peaceID != 0 && ($timestamp-get_the_title($peaceID) > 86400)){
		$canPeace = true;
	}
	?>
	<?php include('declare.php'); ?>

<?php } ?>

<?php if(in_array($declarer_ID, $clanMembers)) {?>

	<div class="sortMobile fw-row">
		<center>
		<strong>Sort by:</strong> <a href="" class="sort6" data-sort=".name-sort">Name</a> -
		<a href="" class="sort6 sort-number" data-sort=".nw-sort">Networth</a> -
		<a href="" class="sort6 sort-number" data-sort=".land-sort">Land</a> -
		<a href="" class="sort6 sort-number" data-sort=".pts-sort">Points</a>
		</center>
	</div>

	<div class="row headerRow fw-row row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
		<div class="col-md-1 celBlock"></div>
		<div class="col-md-4 celBlock"><strong><a href="" class="sort6" data-sort=".name-sort">Name <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-3 celBlock"><strong><a href="" class="sort6 sort-number" data-sort=".nw-sort">Networth <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-2 celBlock"><strong><a href="" class="sort6 sort-number" data-sort=".land-sort">Land <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-1 celBlock"><strong><a href="" class="sort6 sort-number" data-sort=".pts-sort">Points <i class="fas fa-sort"></i></a></strong></div>
		<div class="col-md-1 celBlock"></div>
	</div>

	<div id="values6" class="fw-row">
		<?php
		$count = 0;
		$reverse = false;
		foreach ($clanMembers as $allUser) {

			$userId = $allUser;
			$clan_id = get_user_meta($userId, 'clan_id_user',true);
			$member_data = get_userdata($userId);

			$land = get_user_meta($userId, 'land',true);
			$last_online = get_user_meta($userId, 'last_online',true);

			$userPts = get_user_meta($userId, 'current_clan_points',true);
			$pts = 0;
			$pts = isset($userPts) ?  $userPts : 0;
			$pts = !empty($userPts) ?  $userPts : 0;
			$status = get_user_meta($userId,'status',true);
			if(!empty($last_online)) $last_seen = $timestamp - $last_online;

			if($count == 30) $reverse = true;

			if($reverse == true) {
				$count--;
				if($count == 0) $reverse = false;
			}
			if($reverse == false) $count++;
			?>
			<div class="row fw-row userRow userRow6 row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
				<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
					<?php echo small_avatar($userId,'allUsersAvatar');?>
					<span class="mobileUserName">
						<div class="ctclField">
							<?php if($userId == $clanleader ){
								echo '<strong>CL</strong>';
							} ?>
							<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4 ){
								echo '<strong>CT</strong>';
							} ?>
						</div>
						<span class="name-sort"><?php echo get_user_name($userId);?></span>
					</span>
				</div>

				<div class="col-md-4 celBlock allUsersNameCol">
					<div class="ctclField">
						<?php if($userId == $clanleader ){
							echo '<strong>CL</strong>';
						} ?>
						<?php if($userId == $ct_1 || $userId == $ct_2 || $userId == $ct_3 || $userId == $ct_4 ){
							echo '<strong>CT</strong>';
						} ?>
					</div>
					<span class="name-sort"><?php echo get_user_name($userId);?></span>
				</div>

				<div class="col-md-3 celBlock">
					<span class="columnDataLeft">Networth</span>
					<span class="columnDataRight nw-sort">
						<?php echo networth_range($userId);?>
					</span>
				</div>

				<div class="col-md-2 celBlock">
					<span class="columnDataLeft">Land</span>
					<span class="columnDataRight land-sort">
						<?php echo number_format($land, 0, ',', ' '); ?> m<sup>2</sup>
					</span>
				</div>

				<div class="col-md-1 celBlock">
					<span class="columnDataLeft">Points</span>
					<span class="columnDataRight pts-sort">
						<?php echo number_format($pts, 0, ',', ' '); ?> pts
					</span>
				</div>

				<div class="col-md-1 celBlock">
					<?php if($declarer_ID == $clanleader && $declarer_ID != $userId){?>
						<a href="/kick.php?id=<?php echo $userId;?>&clan=<?php echo $clan_id;?>"
							onclick="return confirm('Are you sure you want to kick <?php echo $member_data->display_name.' (#'.$userId.')';?> from your clan? Your clan will lose <?php echo round($pts*0.25);?> clan points.')">Kick</a>
					<?php } ?>
					<?php if(in_array($declarer_ID, $ctArray) && !in_array($userId, $ctArray) && $userId != $clanleader){?>
						<a href="/kick.php?id=<?php echo $userId;?>&clan=<?php echo $clan_id;?>"
							onclick="return confirm('Are you sure you want to kick <?php echo $member_data->display_name.' (#'.$userId.')';?> from your clan? Your clan will lose <?php echo round($pts*0.25);?> clan points.)')">Kick</a>
					<?php } ?>
				</div>
			</div> <!-- // Close profile row -->
			<?php
		} ?>
	</div>
<?php } ?>