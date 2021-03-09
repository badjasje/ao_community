<?php
/**
 * Template Name: Spy reports single
 */
get_header();

if(!is_user_logged_in()) {
	wp_redirect(home_url('/'));
}

$user = CurrentUser::make();
$province = $user->getProvince();

$target_id = $_GET['id'];
if(empty($target_id) || !is_numeric($target_id)) {
	wp_redirect(get_permalink(3486));
}
$member = Province::make($target_id);
$reports = $province->getReports($target_id, true);

$repUnits = $repBuildings = [];
$unitProvince = $buildingProvince = false;
if(!!$reports['units']) {
	$repUnits = $reports['units']->getEntities();
	$unitProvince = Province::make($reports['units']->get('province_id'));
}
if(!!$reports['buildings']) {
	$repBuildings = $reports['buildings']->getEntities();
	$buildingProvince = Province::make($reports['buildings']->get('province_id'));
}
?>
<div class="row pageRow">

	<?/*<div class="row fw-row no-gutters profileButtonRow">
		<a class="col-md-4 profileButton" href="/attack/?id=<?=$target_id?>">
			<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
		</a>
		<a class="col-md-4 profileButton" href="/users/profile/?id=<?=$target_id?>">
			<i class="fa fa-user" aria-hidden="true"></i> &nbsp;Profile
		</a>

	</div>*/?>

	<div class="aoTable grey">
		<div class="blockHeader"><?=$member->getLink(true)?></div>

		<div class="row fw-row userRow row-no-padding">
			<div class="col-md-4 statusRow statCol-4">
				<div class="row fw-row userRow row-no-padding">
					<div class="col-md-12 celBlock">
						<span class="dataVisibleLeft">Current Networth</span>
						<span class="dataVisibleRight"><?=$member->getNetworth(true)?></span>
					</div>
					<div class="col-md-12 celBlock">
						<span class="dataVisibleLeft">Current Land</span>
						<span class="dataVisibleRight"><?=$member->getLand(true)?></span>
					</div>
					<? if(!!$reports['units']) { ?>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Unit types</span>
							<span class="dataVisibleRight"><?=implode(', ', $reports['type_array'])?></span>
						</div>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Can attack</span>
							<span class="dataVisibleRight"><?=implode(', ', $reports['attack_array'])?></span>
						</div>
					<? } ?>
				</div>
			</div>
			<div class="col-md-4 statusRow statCol-2">
				<div class="row fw-row userRow row-no-padding">
					<div class="col-md-12 celBlock"><strong>Units report</strong></div>
					<? if(!!$reports['units']) { ?>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Networth</span>
							<span class="dataVisibleRight"><?=$reports['units']->getNetworthRegistered(true)?></span>
						</div>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Land</span>
							<span class="dataVisibleRight"><?=$reports['units']->getLandRegistered(true)?></span>
						</div>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Report by <?=$unitProvince->getName(false)?> on</span>
							<span class="dataVisibleRight"><?=$reports['units']->getDate(true)?></span>
						</div>
					<? } else echo '<div class="col-md-12"><em>No reports yet</em></div>'; ?>
				</div>
			</div>
			<div class="col-md-4 statusRow statCol-3">
				<div class="row fw-row userRow row-no-padding">
					<div class="col-md-12 celBlock"><strong>Buildings report</strong></div>
					<? if(!!$reports['buildings']) { ?>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Networth</span>
							<span class="dataVisibleRight"><?=$reports['buildings']->getNetworthRegistered(true)?></span>
						</div>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Land</span>
							<span class="dataVisibleRight"><?=$reports['buildings']->getLandRegistered(true)?></span>
						</div>
						<div class="col-md-12 celBlock">
							<span class="dataVisibleLeft">Report by <?=$buildingProvince->getName(false)?> on</span>
							<span class="dataVisibleRight"><?=$reports['buildings']->getDate(true)?></span>
						</div>
					<? } else echo '<div class="col-md-12"><em>No reports yet</em></div>'; ?>
				</div>
			</div>

		</div>

		<div class="row fw-row row-no-padding">
			<div class="col-md-6 p-0">
				<? if(count($repUnits)) { ?>
					<div class="blockHeader bg-red w-100">Units</div>
					<div class="px-3 py-2">
						<? foreach($repUnits as $normalname => $amount){ ?>
							<span class="dataVisibleLeft"><?=$normalname?></span>
							<span class="dataVisibleRight"><?=$amount?></span><br/>
						<? } ?>
						<? if($reports['units']->getEnhanced()>0) { ?>
							<div class="mt-3 small">
								<strong>Report enhanced <?=$reports['units']->getEnhanced()?> times</strong>
							</div>
						<? } ?>
					</div>
				<? } ?>
			</div>
			<div class="col-md-6 p-0">
				<? if(count($repBuildings)) { ?>
					<div class="blockHeader bg-blue w-100">Buildings</div>
					<div class="px-3 py-2">
						<? foreach($repBuildings as $normalname => $amount){ ?>
							<span class="dataVisibleLeft"><?=$normalname?></span>
							<span class="dataVisibleRight"><?=$amount?></span><br/>
						<? }?>
						<? if($reports['buildings']->getEnhanced()>0) { ?>
							<div class="mt-3 small">
								<strong>Report enhanced <?=$reports['buildings']->getEnhanced()?> times</strong>
							</div>
						<? } ?>
					</div>
				<? } ?>
			</div>
		</div>

		<div class="row fw-row row-no-padding">
			<div class="col-md-<?=$member->getClanId()?3:4?> p-0 profileButtonRow">
				<a href="<?=Request::siteUrl()?>/attack/?id=<?=$member_id?>" class="cancelButton profileButton">
					<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
				</a>
			</div>
			<? if($member->getClanId()) { ?>
			<div class="col-md-3 p-0 profileButtonRow">
				<a class="cancelButton profileButton secondButton" href="/spy-report-overview/?id=<?=$member->getClanId()?>">
					<i class="fas fa-address-card" aria-hidden="true"></i> &nbsp;Clan reports
				</a>
			</div>
			<? } ?>
			<div class="col-md-<?=$member->getClanId()?6:8?> p-0">
				<?=$province->get_spy_buttons($target_id)?>
			</div>
		</div>

	</div>

	<div class="pageSpacer"></div>

</div>
<?php

get_footer();