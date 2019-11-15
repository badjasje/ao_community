<?php
/**
 * Template Name: Unit turn build
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$unitTypes = Settings::get('unit_types');
$space = $province->getUnitTypeSpace();
$usedSpace = $province->getUnitTypeUsedSpace();
$units = $province->getUnits();
$unitsPerTurn = $province->getUnitsPerTurn();
$buildings = Buildings::get();
$unitTypeBuildingNames = array();
foreach($buildings as $building) {
	if(!isset($building['houses'])) continue;
	$unitTypeBuildingNames[$building['houses']] = $building['normalname'];
}

$activeTab = isset($_GET['tab']) ? Request::get('tab') : 'air';
?>
<div class="row pageRow">

	<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
		<?php foreach($unitTypes as $key => $unitType) { ?>
		<a class="nav-item nav-link navItem <?=($activeTab==$key?'active':'')?>" data-toggle="tab" data-target="#<?=$key?>" href="?tab=<?=$key?>"><?=$unitType?></a>
		<?php } ?>
	</nav>

	<form class="form" name="turnbuild" id="turnbuild">
		<div class="tab-content current build_content tabbed-table">
			<?php foreach($unitTypes as $key => $unitType) { ?>
				<div class="tab-pane smallTable unitBuildTable <?=($activeTab == $key ? 'active' : '')?>" id="<?=$key?>" role="tabpanel">
			        <div class="blockHeader spaceNotice">
						<p>
							Your empty <?=$unitTypeBuildingNames[$key]?> allow you to build a maximum of <span id="<?=$key?>spacecount"><?=($space[$key]-$usedSpace[$key])?></span>
							<?=strtolower($unitType)?>. <strong><?=$unitsPerTurn[$key]?> units </strong>built per turn for <?=strtolower($unitType)?>
						</p>
						<div class="text-right small">
							<a href="javascript:void(0);" class="descriptionToggle" data-type="turnbuild"><span>Show</span> descriptions &nbsp; <i class="fa fa-align-justify"></i></a>
						</div>
					</div>
					<table>
						<tr class="unitRow headerRow">
							<th class="nameBlock">Name</th><th class="price">Price</th><th class="attacklife">Att / Life</th>
							<th class="targets">Targets</th><th class="owned">Owned</th><th class="max">Max</th><th class="buildBlock">Build</th>
						</tr>
						<? $count = 0;
						foreach($units as $unitKey => $unit) {
            				if ($unit['type'] != $key) continue;
							$canAttack = is_array($unit['attacks']) && count($unit['attacks']) ? implode(', ', $unit['attacks']) : 'N/A';
							$count++;
							?>
							<tr class="unitRow <?=$unitKey?>"
								data-nw="<?=$unit['networthPerUnit']?>"
								data-buildprice="<?=$unit['buildprice']?>"
								data-key="<?=$unitKey?>"
								data-bpt="<?=$unitsPerTurn[$key]?>"
								data-space="<?=$unit['space']?>"
								<?=($unit['sectype']=='special' ? ' data-specialspace="'.$unit['specialspace'].'"' : '')?>>
								<td class="nameBlock"><?=$unit['normalname']?></td>
								<td class="price"><?=Format::money($unit['buildprice'])?></td>
								<td class="attacklife"><?=$unit['attack']?>/<?=$unit['life']?></td>
								<td class="targets"><?=$canAttack?></td>
								<td class="owned"><?=$unit['num']?><?=($unit['ordered']>0?' ('.$unit['ordered'].')':'')?></td>
								<td class="maxBlock buildmax" data-amount="<?=$unit['maxbuild']?>"><?=$unit['maxbuild']?></td>
								<td class="inputBlock buildBlock">
									<input class="unitInput" min="0" max="<?=$unit['maxbuild']?>" tabindex="<?=$count?>" type="number" name="build[<?=$unitKey?>]">
								</td>
							</tr>
							<tr class="descriptionRow<?=(empty($unit['description'])?' d-md-none':'')?>">
								<td colspan="7">
									<?=(isset($unit['description'])?$unit['description'].'<br>':'')?>
									<div class="d-block d-md-none">
										Attack: <?=$unit['attack']?>, Life: <?=$unit['life']?>, Targets: <?=$canAttack?>
									</div>
								</td>
							</tr>
						<? } ?>
					</table>
				</div>
			<? } ?>

			<div class="row statusBlockButtons">
				<div class="col-md-3 totalsField statCol-1">Units: <span id="total"></span></div>
				<div class="col-md-3 totalsField statCol-2">Total cost: $ <span id="order_total"></span></div>
				<div class="col-md-3 totalsField statCol-3">Turns required: <span id="turn_total"></span></div>
				<div class="col-md-3 totalsField statCol-4">New Nw.: $ <span id="networth_new"></span></div>
			</div>

			<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
			<input type="submit" value="Turn build" class="mainSubmit hoverEffect">
		</div>
	</form>

</div>
<?
get_footer();
