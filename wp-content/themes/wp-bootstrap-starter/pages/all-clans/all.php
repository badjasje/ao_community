<div class="tab-pane <?php echo $activeTab === 'all' ? 'active' : ''; ?>" id="all" role="tabpanel">
	
<div class="sortMobile">
	<center>
	<strong>Sort by:</strong> <a href="" class="sort4" data-sort=".name-sort-4">Name</a> - 
	<a href="" class="sort4 sort-number" data-sort=".avg-nw-sort-4">Avg. netw.</a> -
	<a href="" class="sort4 sort-number" data-sort=".tot-nw-sort-4">Tot. netw.</a> -
	<a href="" class="sort4 sort-number" data-sort=".members-sort-4">Members</a>
	</center>
</div>	

<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong><a href="" class="sort4" data-sort=".name-sort-4">Name <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong><a href="" class="sort4 sort-number" data-sort=".avg-nw-sort-4">Average networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong><a href="" class="sort4 sort-number" data-sort=".tot-nw-sort-4">Total networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-1 celBlock"><strong><a href="" class="sort4 sort-number" data-sort=".members-sort-4">Members <i class="fas fa-sort"></i></a></strong></div>
</div>
	
<div id="values4">
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
			
	<div class="row fw-row userRow clanrow1 row-no-padding" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
			<?php echo clan_avatar($clanId,'allUsersAvatar');?>
			<span class="mobileClanName name-sort-4">
				<a href="<?php echo get_the_permalink($clanId);?>">
					<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)	
				</a>
			</span>
		</div>
	
	<div class="col-md-4 celBlock allUsersNameCol name-sort-4">
		<a href="<?php echo get_the_permalink($clanId);?>">
			<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)	
		</a>
	</div>
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Average networth</span>
		<span class="columnDataRight avg-nw-sort-4">
			<?php echo clan_avg_networth_range($clanId);?>					
		</span>
	</div>
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Total networth</span>
		<span class="columnDataRight tot-nw-sort-4">
			<?php echo clan_networth_range($clanId);?>				
		</span>
	</div>
	<div class="col-md-1 celBlock">
		<span class="columnDataLeft">Members</span>
		<span class="columnDataRight members-sort-4">
		<?php echo $clanMembers;?>
		</span>
	</div>

</div> <! // Close profile row -->

<?php  }?>
</div>
</div>