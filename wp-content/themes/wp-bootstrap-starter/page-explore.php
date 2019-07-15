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
			<div class="blockHeader">
				Current exploration rate is <span id="exprate"><?=Format::land($perturnm2)?></span> per turn
			</div>
			<div class="blockHeader spaceNotice explNotice">
				<?php if($exploredToday == 0) {?>
					You haven't explored any land today. You can explore
					<span class="maxexp" data-max="<?=$maxAmount?>"><strong><?=Format::land($maxLand)?></strong> <i>(<?=$maxAmount?> turns)</i></span>
				<?php } else { ?>
					You have explored <strong><?=Format::land($exploredToday)?></strong> today.
					You can explore an additional
					<span class="maxexp" data-max="<?=$maxAmount?>"><strong><?=Format::land($maxLand)?></strong> <i>(<?=$maxAmount?> turns)</i></span>
				<?php } ?>
			</div>
			<form id="exploreform">
				<div class="row no-gutters">
					<div class="col-md-4 no-gutters collabel">
						<span>Turns to explore</span>
					</div>
					<div class="col-md-4 no-gutters">
						<input class="inputnr" min="0" max="<?=$maxAmount?>" placeholder="Enter amount" type="number" id="turnsinput" name="turns">
					</div>
					<div class="col-md-4 no-gutters maxexp mainSubmit" data-max="<?=$maxAmount?>">ALL TURNS</div>
				</div>
				<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				<input type="submit" value="Explore" class="mainSubmit">
			</form>
		</div>
		<div class="tab-pane <?=($activeTab === 'sell' ? 'active' : '')?>"  id="sell" role="tabpanel">
			<div class="blockHeader spaceNotice sellNotice">
				<?=Format::land(1)?> has a value of <?=Format::money(Settings::get('money_per_land'))?>. You have <?=$freeLand?> of free land.
				You have sold <strong><?=Format::land($soldLandToday)?></strong> today.
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
if($province->getTurns() > 150 && $province->getMoney() < 70000) {
	helpText('Low on money? Use some turns to explore and sell', 'explore', 'reminder');
}
if($maxSell > 700) {
	helpText('You will lose unused land when attacked', 'explore', 'reminder');
}

get_footer();
