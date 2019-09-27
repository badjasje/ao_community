<div class="sortMobile">
	<center>
	<strong>Sort by:</strong> <a href="javascript:void(0);" class="sort" data-sort=".name-sort">Name</a> -
	<a href="javascript:void(0);" class="sort sort-number" data-sort=".nw-sort">Networth</a> -
	<a href="javascript:void(0);" class="sort sort-number" data-sort=".land-sort">Land</a>
	</center>
</div>

<div class="row headerRow row-no-padding" style="border-bottom:1px solid #fff;background-color: rgba(<?=$backColor?>, 0.75);border-top:1px solid #fff;">
	<div class="col-md-1 celBlock"></div>
	<div class="col-md-4 celBlock"><strong><a href="javascript:void(0);" class="sort" data-sort=".name-sort">Name <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="javascript:void(0);" class="sort sort-number" data-sort=".nw-sort">Networth <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-2 celBlock"><strong><a href="javascript:void(0);" class="sort sort-number" data-sort=".land-sort">Land <i class="fas fa-sort"></i></a></strong></div>
	<div class="col-md-3 celBlock"><strong>Clan</strong></div>
</div>

<div id="values">
	<?php
	$count = 0;
	$reverse = false;
	foreach ($allUsers as $allUser) {
		$clan = $allUser->getClan();

		if($count == 10) $reverse = true;
		if($reverse == true){
			$count--;
			if($count == 0) $reverse = false;
		}
		if($reverse == false) $count++;
		?>
		<div class="row fw-row userRow userRow1 row-no-padding" style="background-color:rgba(<?=$backColor?>,<?=(0.35-($count/70))?>);">
			<div class="col-md-1 col-no-padding sea_heading allUsersAvatarCol">
				<?=$allUser->getAvatar('allUsersAvatar')?><span class="mobileUserName"><?=$allUser->getName()?></span>
			</div>
			<div class="col-md-4 celBlock allUsersNameCol name-sort"><?=$allUser->getLink(true)?></div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Networth</span>
				<span class="columnDataRight nw-sort"><?=$allUser->getNetWorth(true)?></span>
			</div>
			<div class="col-md-2 celBlock">
				<span class="columnDataLeft">Land</span>
				<span class="columnDataRight land-sort"><?=$allUser->getLand(true)?></span>
			</div>
			<div class="col-md-3 celBlock"><?=(!!$clan ? '<a href="'.$clan->getLink().'">'.$clan->getName().' (#'.$clan->get('id').')</a>' : 'Clanless')?></div>
		</div>
		<?php
	}
	?>
</div>