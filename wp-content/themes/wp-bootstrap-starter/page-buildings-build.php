<?php
/**
 * Template Name: Buildings build
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$buildingsPerTurn = $province->getBuildingsPerTurn();
$buildings = $province->getBuildings();

?>
<div class="row pageRow">
	<form class="form" name="build" id="buildings" method="post">
		<div class="blockHeader spaceNotice">
			<p>
				You can build a maximum of <span class="maxbuild"><?=$province->getMaxBuild()?></span> buildings,
				your unused land of <span class="freelandheader"><?=$province->getFreeLand(true)?></span> has room for
				<span class="buildspace"><?=$province->getBuildSpace()?></span> buildings.
				(You build <span id="buildingsPerTurn"><?=$buildingsPerTurn?></span> buildings per turn,
				<span class="landpb" data-amount="<?=Settings::get('land_per_building')?>"><?=Format::land(Settings::get('land_per_building'))?></span>
				of land per building)<br>
				The cost to demolish a building is <?=(Settings::get('demolish_price_multi')*100)?>% of the original price.
				<? if($province->getPower() > 50) { ?>Keep your power level around 20% to survive attacks longer.<? } ?>
			</p>
			<div class="text-right small">
				<a href="javascript:void(0);" class="descriptionToggle" data-type="buildings"><span>Show</span> descriptions &nbsp; <i class="fa fa-align-justify"></i></a>
			</div>
		</div>

		<table>
			<tr class="unitRow headerRow">
				<th class="nameBlock">Name</th><th class="price">Price</th><th class="attacklife">Att / Life</th>
				<th class="targets">Targets</th><th class="owned">Owned</th><th class="max">Max</th>
				<th class="buildBlock">Build</th><th class="demoBlock">Demo<span class="d-none d-md-inline-block">lish</span></th>
			</tr>
			<?php $count=0;
			foreach($buildings as $buildingKey => $building) {
				$canAttack = is_array($building['attacks']) && count($building['attacks']) ? implode(', ', $building['attacks']) : 'N/A';
				$count++;
				?>
				<tr class="unitRow <?=$buildingKey?>"
					data-nw="<?=$building['networthPerUnit']?>"
					data-buildprice="<?=$building['buildprice']?>"
					data-demoprice="<?=$building['demoprice']?>"
					data-key="<?=$buildingKey?>">
					<td class="nameBlock buildings_heading"><?=$building['normalname']?></td>
					<td class="price"><?=Format::money($building['buildprice'])?></td>
					<td class="attacklife"><?=$building['attack']?>/<?=$building['life']?></td>
					<td class="targets"><?=$canAttack?></td>
					<td class="owned demomax" data-amount="<?=$building['maxdemo']?>"><?=$building['num']?></td>
					<td class="maxBlock buildmax" data-amount="<?=$building['maxbuild']?>"><?=$building['maxbuild']?></td>
					<td class="inputBlock buildBlock">
						<input class="unitInput" min="0" max="<?=$building['maxbuild']?>" tabindex="<?=$count?>" type="number" name="build[<?=$buildingKey?>]">
					</td>
					<td class="inputBlock demoBlock">
						<input class="unitInput" min="0" max="<?=$building['maxdemo']?>" tabindex="<?=($count+count($buildings))?>" type="number" name="demo[<?=$buildingKey?>]">
					</td>
				</tr>
				<tr class="descriptionRow">
					<td colspan="8">
						<?=(isset($building['description'])?$building['description']:'')?>
						It adds <?=$building['networth']?>% networth, <?=Format::money($building['networthPerUnit'])?> per building.
						<? if($building['occupied']>0) {?>There are <span class="occupied"><?=ceil($building['occupied']/$building['housing'])?></span> of them occupied.<? } ?>
						<div class="d-block d-md-none">
							Attack/life: <?=$building['attack']?>/<?=$building['life']?>, targets: <?=$canAttack?>
						</div>
					</td>
				</tr>
				<?
			}
			?>
		</table>

		<div class="row statusBlockButtons">
			<div class="col-md-3 totalsField statCol-1">Buildings: <span id="total"></span></div>
			<div class="col-md-3 totalsField statCol-2">Total cost: $ <span id="order_total"></span></div>
			<div class="col-md-3 totalsField statCol-3">Turns required: <span id="turn_total"></span></div>
			<div class="col-md-3 totalsField statCol-4">New Nw.: $ <span id="networth_new"></span></div>
		</div>

		<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
		<input type="submit" value="Build / Demolish" class="mainSubmit hoverEffect">
	</form>
</div>
<?php
get_footer();