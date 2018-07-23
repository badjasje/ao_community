<div class="tab-pane active" id="all" role="tabpanel">
<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock">Name</a></strong></div>
	<div class="col-md-3 celBlock">Average networth</strong></div>
	<div class="col-md-3 celBlock">Total networth</strong></div>
	<div class="col-md-1 celBlock">Members</div>
</div>
	
	
<?php 
	
	$count = 0;
	$reverse = false;
	foreach ($clans as $clan) {
		
		$clanId = $clan->ID;
		$clanNetworth = get_post_meta($clanId, 'clan_networth', true);
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
				
			?>
			
	<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
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
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Average networth</span>
		<span class="columnDataRight store-pop-span2">
			<?php echo clan_avg_networth_range($clanId);?>					
		</span>
	</div>
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Total networth</span>
		<span class="columnDataRight store-pop-span2">
			$ <?php echo number_format($clanNetworth, 0, ',', ' ');?>				
		</span>
	</div>
	<div class="col-md-1 celBlock">
		<span class="columnDataLeft">Members</span>
		<span class="columnDataRight land">
		<?php echo $clanMembers;?>
		</span>
	</div>

</div> <! // Close profile row -->

<?php  }?>

</div>