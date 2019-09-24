<?php
/**
 * Template Name: Explore
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$exploredToday = $province->get('explored_today');
$perturnm2 = $province->getExplorationRate();
$soldLandToday = $province->get('land_sold_today');
$freeLand = $province->getFreeLand(true);

$maxLand = $province->getMaxExploreLand();
$maxAmount = floor($maxLand/$perturnm2);
$maxSell = $province->getMaxSellLand();

$activeTab = isset($_GET['tab']) ? Request::get('tab') : 'explore';
?>
<div id="exploresell" class="row pageRow">

	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem <?=($activeTab == 'explore' ? 'active' : '')?>" href="?tab=explore" data-toggle="tab" data-target="#explore">Explore</a>
		<a class="nav-item nav-link navItem <?=($activeTab == 'sell' ? 'active' : '')?>" href="?tab=sell" data-toggle="tab" data-target="#sell">Sell</a>
	</nav>

	<div class="tab-content">
		<div class="tab-pane <?=($activeTab == 'explore' ? 'active' : '')?>" id="explore" role="tabpanel">
			<div class="blockHeader spaceNotice">
				You can explore <span id="exprate"><?=Format::land($perturnm2)?></span> of land each turn.
				<? if($maxSell > 700) { ?>You will lose unused land when attacked.<? } ?>
				<? if($province->getTurns() > 150 && $province->getMoney() < 70000) { ?>
					Low on money? Use some turns to explore and sell.
				<? } ?>
				<div class="explNotice">
					<?php if($exploredToday == 0) {?>
						You haven't explored any land today. You can explore
					<?php } else { ?>
						<? if(!Round::isDev() && !Round::isTest()) { ?>
							You have explored <strong><?=Format::land($exploredToday)?></strong> today.
						<? } else { ?>
							There is no maximum explore per day on test.
						<? } ?>
						You can explore an additional
					<?php } ?>
					<span class="maxexp" data-max="<?=$maxAmount?>"><strong><?=Format::land($maxLand)?></strong> <i>(<?=$maxAmount?> turns)</i></span>
				</div>
			</div>
			<form id="exploreform">
				<div class="row no-gutters">
					<div class="col-md-4 no-gutters collabel">
						<span>Turns to explore</span>
					</div>
					<div class="col-md-4 no-gutters">
						<input class="inputnr<?=($maxAmount==0?' disabled':'')?>" min="0" max="<?=$maxAmount?>" placeholder="Enter amount" type="number" id="turnsinput" name="turns">
					</div>
					<div class="col-md-4 no-gutters maxexp mainSubmit<?=($maxAmount==0?' disabled':'')?>" data-max="<?=$maxAmount?>">ALL TURNS</div>
				</div>
				<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				<input type="submit" value="Explore" class="mainSubmit<?=($maxAmount==0?' disabled':'')?>">
			</form>
		</div>
		<div class="tab-pane <?=($activeTab === 'sell' ? 'active' : '')?>"  id="sell" role="tabpanel">
			<div class="blockHeader spaceNotice sellNotice">
				<?=Format::land(1)?> has a value of <?=Format::money(Settings::get('money_per_land'))?>. You have <?=$freeLand?> of free land.
				<? if(!Round::isDev() && !Round::isTest()) { ?>
					You have sold <strong><?=Format::land($soldLandToday)?></strong> today.
				<? } else { ?>
					There is no maximum sell per day on test.
				<? } ?>
				You can sell an additional <strong class="maxsell" data-max="<?=$maxSell?>"><?=Format::land($maxSell)?></strong>
			</div>
			<form id="sellform">
				<div class="row no-gutters">
					<div class="col-md-4 no-gutters collabel">
						Amount of land to sell
					</div>
					<div class="col-md-4 no-gutters">
						<input class="inputnr" min="0" max="<?=$maxSell?>" placeholder="Enter amount" type="number" id="landinput" name="land" >
					</div>
					<div class="col-md-4 no-gutters maxsell mainSubmit" data-max="<?=$maxSell?>">ALL LAND</div>
				</div>
				<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				<input type="submit" value="Sell" class="mainSubmit">
			</form>
		</div>
	</div>
</div>
<?php

get_footer();
