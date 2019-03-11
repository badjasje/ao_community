<div class="tab-pane active" id="all" role="tabpanel">
<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColorCNW;?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock" style="max-width: 25% !important">Name</a></strong></div>
	<div class="col-md-4 celBlock" style="max-width: 23% !important">Networth</strong></div>
	<div class="col-md-3 celBlock" style="max-width: 23% !important">Land</div>
	<div class="col-md-3 celBlock" style="max-width: 18% !important">Opt-In War Status</div>
</div>


<?php

	$count = 0;
	$reverse = false;
	$position = 0;
	foreach ($toplistArray['clannetworth'] as $clan) {

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

	<div class="row fw-row userRow row-no-padding" style="background-color: rgba(<?php echo $backColorCNW;?>, <?php echo 0.35-($count/70);?>);">
		<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol"><div class="positionNo"><?php echo $position;?></div>
			<?php echo clan_avatar($clanId,'allUsersAvatar');?>
			<span class="mobileClanName">
				<a href="<?php echo get_the_permalink($clanId);?>">
					<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)
				</a>
			</span>
		</div>

	<div class="col-md-4 celBlock allUsersNameCol" style="max-width: 25% !important">
		<a href="<?php echo get_the_permalink($clanId);?>">
			<?php echo get_the_title($clanId);?> (#<?php echo $clanId;?>)
		</a>
	</div>
	<div class="col-md-4 celBlock" style="max-width: 23% !important">
		<span class="columnDataLeft">Networth</span>
		<span class="columnDataRight store-pop-span2">
			$ <?php echo number_format(get_post_meta($clanId, 'clan_networth',true), 0, ',', ' ')?>
		</span>

	</div>
	<div class="col-md-3 celBlock style="max-width: 23% !important">
		<span class="columnDataLeft">Land</span>
		<span class="columnDataRight land">
			<?php echo number_format(get_post_meta($clanId, 'clan_land',true), 0, ',', ' ')?> m&#178;
		</span>
	</div>

	<div class="col-md-3 celBlock" style="max-width: 18% !important">
		<span class="columnDataRight land">
                        <?php $optout = get_post_meta($clanId, 'optout_status',true);
                        if ($optout == 1) {
                          ?><font color="red">Opted Out</font><?php
                        }
                        else {
                          echo "Declare Eligible";
                        } ?>
		</span>
	</div>
</div> <!-- //Close profile row -->

<?php  }?>

</div>
