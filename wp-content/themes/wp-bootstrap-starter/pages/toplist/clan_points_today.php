<div class="tab-pane active" id="all" role="tabpanel">
<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor24h;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock">Name</a></strong></div>
	<div class="col-md-4 celBlock">Clan points</strong></div>
	<div class="col-md-3 celBlock">Members</div>
</div>


<?php

	$count = 0;
	$reverse = false;
	$position = 0;
	foreach ($toplistArray['24h_pts'] as $clan) {

		$clanId = $clan;
		$clanMembers = count(maybe_unserialize(get_post_meta($clanId, 'clan_members', true)));
		if($clanMembers == 0){
			continue;
		}
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

	<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor24h;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol"><div class="positionNo"><?php echo $position;?></div>
			<?php echo clan_avatar($clanId,'allUsersAvatar');?>
			<span class="mobileClanName">
				<a href="<?php echo get_the_permalink($clanId);?>">
					<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)
				</a>
			</span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol">
		<a href="<?php echo get_the_permalink($clanId);?>">
			<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)
		</a>
	</div>
	<div class="col-md-4 celBlock">
		<span class="columnDataLeft">Clan points</span>
		<span class="columnDataRight store-pop-span2">
			<?php echo ceil(get_post_meta($clan, '24h_pts',true));?>
		</span>

	</div>
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Members</span>
		<span class="columnDataRight land">
		<?php echo $clanMembers;?>
		</span>
	</div>

</div> <!-- //Close profile row -->

<?php  }?>

</div>