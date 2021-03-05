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
$in_range = []; // array of Id's
if(!!$myClan) {
	foreach($clans as $clan) {
		if($clan->inRange($myClan->get('id'))) $in_range[] = $clan->get('id');
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

				<? if(!!$myClan) { ?>
					<option disabled name="clan" value="0">Clans in range &rarrb;</option>
					<? foreach($clans as $clan) {
						if(!in_array($clan->get('id'), $in_range)) continue;
						?>
						<option class="inrange" name="clan" value="/spy-report-overview/?id=<?=$clan->get('id')?>">
							<strong><?=$clan->getName()?></strong> <?=$clan->getTag()?>
						</option>
					<? }?>
				<? } ?>

				<option disabled name="clan" value="0">Clans out of range &rarrb;</option>

				<? foreach ($clans as $clan) {
					if(in_array($clan->get('id'), $in_range)) continue; ?>
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
		if(!!$reports['units']) {
			$repUnits = $reports['units']->getEntities();
		}
		if(!!$reports['buildings']) {
			$repBuildings = $reports['buildings']->getEntities();
		}
		?>
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
				<div class="col-md-4 celBlock py-0">
					<a href="<?=Request::siteUrl()?>/attack/?id=<?=$member_id?>" class="cancelButton profileButton">
						<i class="fa fa-crosshairs" aria-hidden="true"></i> &nbsp;Attack
					</a>
				</div>
				<div class="col-md-4 celBlock py-0">
					<button viewtype="units" member-id="<?=$member_id?>"
						class="<?=count($repUnits)?'active':'disabled'?> cancelButton profileButton viewmemberinfo">
						<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units
					</button>
					<div class="memberInfo units_<?=$member_id?>">
						<? if(count($repUnits)) {
							foreach($repUnits as $normalname => $amount){ ?>
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
				<div class="col-md-4 celBlock py-0">
					<button viewtype="buildings" member-id="<?=$member_id?>"
						class="<?=count($repBuildings)?'active':'disabled'?> cancelButton profileButton viewmemberinfo">
						<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings
					</button>
					<div class="memberInfo buildings_<?=$member_id?>">
						<? if(count($repBuildings)) {
							foreach($repBuildings as $normalname => $amount){ ?>
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
			<?=$province->get_spy_buttons($member_id)?>
		</div>

		<div class="pageSpacer"></div>
	<? } ?>
</div>
<?php
get_footer();