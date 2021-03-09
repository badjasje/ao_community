<?php
/**
 * Template Name: Spy report overview
 */
get_header();

if(!is_user_logged_in()) {
	wp_redirect(home_url('/'));
}

$user = CurrentUser::make();
$province = $user->getProvince();
$myClan = $province->getClan();

$clans = Clan::getAll();
$in_range = $out_range = [];
if(!!$myClan) {
	foreach($clans as $clan) {
		if($clan->inRange($myClan->get('id'))) $in_range[] = $clan;
		else $out_range[] = $clan;
	}
}

$clan_ID = $_GET['id'];
if(empty($clan_ID) || !is_numeric($clan_ID)) {
	wp_redirect(get_permalink(3486));
}

$viewClan = Clan::make($clan_ID);
if(empty($viewClan->get('id'))) {
	wp_redirect(get_permalink(3486));
}
?>
<div class="row pageRow">
	<div class="attackDropdown statCol-2 p-0 w-100">
		<form>
			<select id="clan" name="clan" class="attackTypeInput redirectOnChange">
				<option disabled selected name="clan" value="<?=$clan_ID?>">
					Currently viewing: <?=$viewClan->getName()?> <?=$viewClan->getTag()?>
				</option>

				<? if(!!$myClan && count($in_range)) { ?>
					<option disabled name="clan" value="0">Clans in range &rarrb;</option>
					<? foreach($in_range as $clan) { ?>
						<option class="inrange" name="clan" value="/spy-report-overview/?id=<?=$clan->get('id')?>">
							<strong><?=$clan->getName()?></strong> <?=$clan->getTag()?>
						</option>
					<? }?>
				<? } ?>

				<option disabled name="clan" value="0">Clans out of range &rarrb;</option>

				<? foreach ($out_range as $clan) { ?>
					<option name="clan" value="/spy-report-overview/?id=<?=$clan->get('id')?>">
						<strong><?=$clan->getName()?></strong> <?=$clan->getTag()?>
					</option>
				<? } ?>
			</select>
		</form>
	</div>

	<div class="blockHeader spaceNotice"></div>

	<?php
	foreach($viewClan->getMembers() as $member_id) {
		$member = Province::make($member_id);
		$reports = $province->getReports($member_id, true);

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
						<div class="col-md-12 celBlock"><strong>Units report <?=(!!$unitProvince ? 'by '.$unitProvince->getName(false) : '')?></strong></div>
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
								<span class="dataVisibleLeft">Date</span>
								<span class="dataVisibleRight"><?=$reports['units']->getDate(true)?></span>
							</div>
						<? } else echo '<div class="col-md-12"><em>No reports yet</em></div>'; ?>
					</div>
				</div>
				<div class="col-md-4 statusRow statCol-3">
					<div class="row fw-row userRow row-no-padding">
						<div class="col-md-12 celBlock"><strong>Buildings report <?=(!!$buildingProvince ? 'by '.$buildingProvince->getName(false) : '')?></strong></div>
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
								<span class="dataVisibleLeft">Date</span>
								<span class="dataVisibleRight"><?=$reports['buildings']->getDate(true)?></span>
							</div>
						<? } else echo '<div class="col-md-12"><em>No reports yet</em></div>'; ?>
					</div>
				</div>

			</div>

			<div class="row fw-row row-no-padding">
				<div class="col-md-6 p-0">
					<? if(count($repUnits)) { ?>
						<button viewtype="units" member-id="<?=$member_id?>"
							class="active cancelButton profileButton bg-red viewmemberinfo">
							<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units
						</button>
						<div class="memberInfo w-100 units_<?=$member_id?>">
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
						<button viewtype="buildings" member-id="<?=$member_id?>"
							class="active cancelButton bg-blue profileButton viewmemberinfo">
							<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings
						</button>
						<div class="memberInfo w-100 buildings_<?=$member_id?>">
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
				<div class="col-md-4 p-0 profileButtonRow">
					<a href="<?=Request::siteUrl()?>/attack/?id=<?=$member_id?>" class="cancelButton secondButton profileButton">
						<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
					</a>
				</div>
				<div class="col-md-8 p-0">
					<?=$province->get_spy_buttons($member_id)?>
				</div>
			</div>
		</div>

		<div class="pageSpacer"></div>
	<? } ?>
</div>
<?php
get_footer();