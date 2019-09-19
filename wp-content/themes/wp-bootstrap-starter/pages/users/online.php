<div class="sortMobile">
	<center>
	<strong>Sort by:</strong> <a href="javascript:void(0);" class="sort3" data-sort=".name-sort-3">Name</a> -
	<a href="javascript:void(0);" class="sort3 sort-number" data-sort=".nw-sort-3">Networth</a> -
	<a href="javascript:void(0);" class="sort3 sort-number" data-sort=".land-sort-3">Land</a>
	</center>
</div>

<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?=$backColor?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong><a href="javascript:void(0);" class="sort3" data-sort=".name-sort-3">Name <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="javascript:void(0);" class="sort3 sort-number" data-sort=".nw-sort-3">Networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="javascript:void(0);" class="sort3 sort-number" data-sort=".land-sort-3">Land <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong>Clan</strong></div>
</div>

<div id="values3">
	<?php
	$count = 0;
	$reverse = false;
	foreach ($allUsers as $allUser) {

		$clan = $allUser->getClan();

		$last_online = get_user_meta($user_ID, 'last_online',true);
		if(!empty($last_online)) $last_seen = $timestamp - $last_online;

		if($last_seen < 7200) {
			if($count == 10) $reverse = true;
			if($reverse == true){
				$count--;
				if($count == 0) $reverse = false;
			}
			if($reverse == false) $count++;
			?>
			<div class="row fw-row userRow userRow3 row-no-padding" style="background-color: rgba(<?=$backColor?>,<?=(0.35-($count/70));?>)">
				<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
					<?=$allUser->getAvatar()?><span class="mobileUserName name-sort-3"><?=$allUser->getName()?></span>
				</div>
				<div class="col-md-4 celBlock allUsersNameCol"><?=$allUser->getLink(true)?></div>
				<div class="col-md-2 celBlock">
					<span class="columnDataLeft">Networth</span>
					<span class="columnDataRight nw-sort-3"><?=$allUser->getNetworth(true)?></span>
				</div>
				<div class="col-md-2 celBlock">
					<span class="columnDataLeft">Land</span>
					<span class="columnDataRight land-sort-3"><?=$allUser->getLand(true)?></span>
				</div>
				<div class="col-md-3 celBlock">
					<?=(!!$clan ? '<a href="'.$clan->getLink().'">'.$clan->getName().' (#'.$clan->get('id').')</a>' : 'Clanless')?>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>