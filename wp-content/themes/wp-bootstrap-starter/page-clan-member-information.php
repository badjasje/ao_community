<?php
/**
 * Template Name: Clan Member Information
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();
$clan = $province->getClan();
?>
<div class="row pageRow">
	<?php
	foreach($clan->getMembers() as $key => $member) {
		$province = Province::make($member);

		$sb = $province->getStartingBonus();
		$startingbonus = (!empty($sb) ? $sb['name'] : '<em>none</em>');

		// Satellites
		$totalSatellites = array('owned' => false, 'ordered' => 0, 'timeleft' => 0);
		$satellites = $province->getSatellites();
		foreach($satellites as $satellite) {
			if($satellite['num']>0) $totalSatellites['owned'] = $satellite['shortname'];
			if($satellite['in_progress']==true) { 
				$totalSatellites['ordered']++;
				$totalSatellites['timeleft'] = $satellite['timeleft'];
			}
		}

		// Research
		$totalResearch = array('owned' => 0, 'ordered' => 0);
		$researches = $province->getResearches();
		$inprogress = $province->getCurrentResearch();
		foreach($researches as $research) {
			if($research['level'] > 0) $totalResearch['owned']++;
			if($research['inProgress']) $totalResearch['ordered']++;
		}

		// Units
		$canAttack = array();
		$totalUnits = array('owned' => 0, 'ordered' => 0);
		$unitTypes = $type_array = array();
		$units = $province->getUnits();
		foreach($units as $key => $unit) {
			if(!isset($type_array[$unit['type']])) $type_array[$unit['type']] = 0;
			$totalUnits['owned'] += $unit['num'];
			$totalUnits['ordered'] += $unit['ordered'];
			if($unit['num'] > 0) $canAttack = array_merge($canAttack, $unit['attacks']);
			if($unit['num'] > 0 && $unit['sectype'] != 'special') $type_array[$unit['type']] += $unit['num'];
		}
		$canAttack = implode(',',array_unique($canAttack));
		if(empty($canAttack)) $canAttack = '<em>none</em>';
		foreach ($type_array as $type => $number) {
			if($number > 0) $unitTypes[] = '<span class="hover-tip" data-toggle="tooltip" data-original-title="Owned: '.
				$number.'" data-placement="bottom">'. $type .'</span>';
		}
		$unitTypes = (count($unitTypes) ? implode(', ',$unitTypes) : '<em>none</em>');

		// Buildings
		$totalBuildings = array('owned' => 0);
		$buildings = $province->getBuildings();
		foreach($buildings as $key => $unit) {
			$totalBuildings['owned'] += $unit['num'];
		}
		
		// Bonuses
		$open_bonusses = $open_money = $open_turns = 0;
		foreach($province->getBonuses() as $bonus) {
			if($bonus->isUsed()) continue;
			$open_bonusses++;
			$open_money += $bonus->money();
			$open_turns += $bonus->turns();
		}
		$unused_bonusses = ($open_bonusses > 0 ? '<span class="hover-tip" data-toggle="tooltip" data-original-title="Money: '.
			Format::money($open_money).', turns: '. Format::turns($open_turns) .'" data-placement="bottom">'. $open_bonusses .'</span>' : 0);
		?>
		<div id="member-<?=$member?>" class="memberinfo-main">
			<div class="blockHeader memberField"><?=$province->getName(true)?></div>

			<div class="row fw-row userRow row-no-padding">
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Networth</span>
					<span class="dataVisibleRight store-pop-span2"><?=$province->getNetworth(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Land</span>
					<span class="dataVisibleRight land"><?=$province->getLand(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Points per attack</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::number($province->getPPA())?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Targets spied</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::number($province->get('spied_current_clan'))?></span>
				</div>
			</div>

			<div class="row fw-row userRow row-no-padding">
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Turns</span>
					<span class="dataVisibleRight store-pop-span2"><?=$province->getTurns(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Money</span>
					<span class="dataVisibleRight"><?=$province->getMoney(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Morale</span>
					<span class="dataVisibleRight store-pop-span2"><?=$province->getMorale(true)?> <sup>(<?=$province->getMoralePool(true)?>)</sup></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Last online</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::time_elapsed($province->get('last_online'))?>
					</span>
				</div>
			</div>

			<div class="row fw-row userRow row-no-padding">
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Unit types</span>
					<span class="dataVisibleRight store-pop-span2"><?=$unitTypes; ?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Can attack</span>
					<span class="dataVisibleRight"><?=$canAttack?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Attacks made</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::number($province->get('attacks_made_current'))?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Attacks received</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::number($province->get('attacks_rec_current'))?></span>
				</div>
			</div>

			<div class="row fw-row userRow row-no-padding">
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Aid sent</span>
					<span class="dataVisibleRight store-pop-span2"><?=Format::money($province->get('total_aid_sent'))?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Aid received</span>
					<span class="dataVisibleRight"><?=Format::money($province->get('aid_received'))?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Power usage</span>
					<span class="dataVisibleRight store-pop-span2"><?=$province->getPower(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Satellite</span>
					<span class="dataVisibleRight store-pop-span2">
						<?=($totalSatellites['owned']!=false ? $totalSatellites['owned'].' ('.$province->getSatMorale(true).')' : 
							($totalSatellites['ordered']>0 ? '<span data-countdown="'.$totalSatellites['timeleft'].'"></span>' : '<em>none</em>')
						)?>
					</span>
				</div>
			</div>

			<div class="row fw-row userRow row-no-padding">
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Bank total</span>
					<span class="dataVisibleRight"><?=$province->getDepositFinal(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Bank available</span>
					<span class="dataVisibleRight"><?=$province->getDepositAvailable(true)?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Unused bonusses</span>
					<span class="dataVisibleRight"><?=$unused_bonusses?></span>
				</div>
				<div class="col-md-3 col-xs-6 celBlock">
					<span class="dataVisibleLeft">Start bonus</span>
					<span class="dataVisibleRight"><?=$startingbonus?></span>
				</div>
			</div>

		</div>
		<div class="row fw-row no-gutters memberinfo-buttons">

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="research" member-id="<?=$member?>" 
					class="cancelButton viewmemberinfo mainSubmit<?=($totalResearch['owned']+$totalResearch['ordered']>0?' active':'')?>">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Research <?=$totalResearch['owned']?>
					<? if(!!$inprogress) {?>
						<span class="badge" data-toggle="tooltip" data-placement="top" title="Research currently in progress: <?=$inprogress->get('name')?>">
							<i class="fa fa-circle-o-notch fa-spin"></i>
						</span>
					<? } ?>
				</button>
				<div class="memberInfo research_<?=$member?>">
				<? foreach ($researches as $key => $research) { ?>
					<span class="dataVisibleLeft"><?=$research['name']?></span>
					<span class="dataVisibleRight">Level: <?=$research['level']?></span>
					<br/>
				<? } ?>
				<? if(!!$inprogress) { ?>
					<br/>
					<strong>In progress: <?=$inprogress->get('name')?>, <span data-countdown="<?=$province->getResearchTimeLeft()?>"></span> left</strong>
				<? } ?>
				</div>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="units" member-id="<?=$member?>"
					class="cancelButton viewmemberinfo mainSubmit secondButton<?=($totalUnits['owned']+$totalUnits['ordered']>0?' active':'')?>">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Units <?=$totalUnits['owned']?> (<?=$totalUnits['ordered']?>)
				</button>
				<div class="memberInfo units_<?=$member?>">
					<? foreach($units as $key => $unit) {
						if($unit['num'] == 0 && $unit['ordered'] == 0) continue; ?>
						<span class="dataVisibleLeft"><?=$unit['normalname']?></span>
						<span class="dataVisibleRight"><?=$unit['num']?> (<?=$unit['ordered']?>)</span><br/>
						<?php
					} ?>
				</div>
			</div>

			<div class="col-md-4 celBlock" style="padding:0px">
				<button viewtype="buildings" member-id="<?=$member?>"
					class="cancelButton viewmemberinfo mainSubmit<?=($totalBuildings['owned']>0?' active':'')?>">
					<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Buildings <?=$totalBuildings['owned']?>
				</button>
				<div class="memberInfo buildings_<?=$member?>">
					<?php foreach($buildings as $key => $building){
						if($building['num'] == 0) continue; ?>
						<span class="dataVisibleLeft"><?=$building['normalname']?></span>
						<span class="dataVisibleRight"><?=$building['num']?></span><br/>
						<?php
					} ?>
				</div>
			</div>

		</div>
	<? }  ?>

</div>
<?php

get_footer();
