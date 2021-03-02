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
if(!!$reports['units']) {
	$repUnits = $reports['units']->getEntities();
}
if(!!$reports['buildings']) {
	$repBuildings = $reports['buildings']->getEntities();
}
?>
<div class="row pageRow">
	<div class="row fw-row no-gutters profileButtonRow">
		<a class="col-md-4 profileButton" href="/attack/?id=<?=$target_id?>">
			<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
		</a>
		<a class="col-md-4 profileButton" href="/users/profile/?id=<?=$target_id?>">
			<i class="fa fa-user" aria-hidden="true"></i> &nbsp;Profile
		</a>
		<a class="col-md-4 profileButton" href="/spy-report-overview/?id=<?=$member->getClanId()?>">
			<i class="fas fa-address-card" aria-hidden="true"></i> &nbsp;Clan reports
		</a>
	</div>

	<?
	echo $province->get_spy_buttons($target_id);
	?>

	<div class="pageSpacer"></div>

	<div class="aoTable grey">
		<div class="blockHeader"><?=$member->getLink(true)?></div>
		<div class="row fw-row userRow row-no-padding">
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Networth current</span>
				<span class="dataVisibleRight"><?=$member->getNetworth(true)?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Networth registered</span>
				<span class="dataVisibleRight"><?=(isset($reports['networth'])?$reports['networth']:'')?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Land current</span>
				<span class="dataVisibleRight"><?=$member->getLand(true)?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Land registered</span>
				<span class="dataVisibleRight"><?=(isset($reports['land'])?$reports['land']:'')?></span>
			</div>
		</div>
		<div class="row fw-row userRow row-no-padding">
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Units spied date</span>
				<span class="dataVisibleRight"><?=(!!$reports['units']?$reports['units']->getDate(true):'')?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Buildings spied date</span>
				<span class="dataVisibleRight"><?=(!!$reports['buildings']?$reports['buildings']->getDate(true):'')?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Unit types</span>
				<span class="dataVisibleRight"><?=implode(', ', $reports['type_array'])?></span>
			</div>
			<div class="col-md-3 celBlock">
				<span class="dataVisibleLeft">Can attack</span>
				<span class="dataVisibleRight"><?=implode(', ', $reports['attack_array'])?></span>
			</div>
		</div>
		<div class="row fw-row no-gutters">
			<div class="col-md-6 celBlock py-0">
				<div class="blockHeader bg-red w-100">Units</div>
				<div class="px-3 py-2">
					<? if(count($repUnits)) { ?>
						<? foreach($repUnits as $normalname => $amount){ ?>
							<span class="dataVisibleLeft"><?=$normalname?></span>
							<span class="dataVisibleRight"><?=$amount?></span><br/>
						<? } ?>
						<div class="mt-3 small">
							Last spied by
							<?=Province::make($reports['units']->get('province_id'))->getName(false)?>
							<? if($reports['units']->getEnhanced()>0) { ?>
							<strong>Enhanced <?=$reports['units']->getEnhanced()?> times</strong>
							<? } ?>
						</div>
					<? } ?>
				</div>
			</div>
			<div class="col-md-6 celBlock py-0">
				<div class="blockHeader bg-blue w-100">Buildings</div>
				<div class="px-3 py-2">
					<? if(count($repBuildings)) { ?>
						<? foreach($repBuildings as $normalname => $amount){ ?>
							<span class="dataVisibleLeft"><?=$normalname?></span>
							<span class="dataVisibleRight"><?=$amount?></span><br/>
						<? }?>
						<div class="mt-3 small">
							Last spied by
							<?=Province::make($reports['buildings']->get('province_id'))->getName(false)?>
							<? if($reports['buildings']->getEnhanced()>0) { ?>
							<strong>Enhanced <?=$reports['buildings']->getEnhanced()?> times</strong>
							<? } ?>
						</div>
					<? } ?>
				</div>
			</div>
		</div>

	</div>

	<div class="pageSpacer"></div>

</div>
<?php

get_footer();