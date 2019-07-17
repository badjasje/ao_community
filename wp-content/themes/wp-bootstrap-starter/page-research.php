<?php
/**
 * Template Name: Research Page
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$hasResearch = false;
$researches = $province->getResearches();
$researchInProgress = $province->getCurrentResearch();
$researchQueued = $province->getQueuedResearch();
$researchTimeLeft = $province->getResearchTimeLeft();

?>
<div class="row pageRow">

	<div class="blockHeader spaceNotice">
		You may only research one science at a time. Each research takes different amount of time to complete and cost <?=Settings::get('turns_research')?> turns.
		Queueing a research cost <?=Settings::get('turns_queue_research')?> turns.
		Every hour of research adds <?=Format::money(Settings::get('nw_research'))?> to your networth.
		<? if($province->hasStartingBonus('defensive')) { ?>
		Your defensive startbonus gives <?=(1-Settings::get('startbonus_defensive_research_time'))*100?>% time deduction when researching.
		<? } ?>
		<?/*You may cancel your research while researching. Aborting a research costs no additional turns but all research for the aborted science will be lost.
		Your amount of Research Labs deducts the time it takes to research by 0%.
		@todo: show current research & new research*/?>
	</div>

	<form id="research">
		<div class="row unitRow headerRow">
			<div class="col-md-3 celBlock nameBlock">Name</div>
			<div class="col-md-4 celBlock">Effect</div>
			<div class="col-md-2 celBlock">Time</div>
			<div class="col-md-3 celBlock"></div>
		</div>
		<? foreach ($researches as $key => $research) {
			if($research['level']>0) $hasResearch=true;
			?>
			<div id="research_<?=$key?>" class="itemRow">

				<div class="row unitRow <?=($research['inProgress']?' loader':'')?>">
					<div class="col-md-3 celBlock nameBlock">
						<?=$research['name']?>
						<sup>Current level: <?=$research['level']?> / <?=$research['maxlevel']?></sup>
					</div>
					<div class="col-md-4 celBlock">
						<?=($research['level'] < $research['maxlevel'] ? $research['level_description'] : '<strong>Maximum level reached.</strong>')?>
					</div>
					<div class="col-md-2 celBlock">
						<? if($research['level'] < $research['maxlevel']) { ?>
							<span class="hover-tip" data-toggle="tooltip" data-original-title="<?=$research['nw']?> networth added when completing this research" data-placement="bottom">
								<span class="mobileSpan">Time: </span>
								<?=$research['duration']?> hours <i class="fa fa-info-circle" aria-hidden="true"></i>
							</span>
						<? } ?>
					</div>
					<div class="col-md-3 celBlock nopadding">
						<?php if($researchQueued == false || $researchInProgress == false) {?>
							<div class="researchselector">
								<input class="hidden" type="radio" name="research" id="<?=$key?>" value="<?=$key?>">
								<label class="researchlabel mainSubmit <?=$key?>_button <?=($research['level']>=$research['maxlevel']?'disabled':'hoverEffect')?>" for="<?=$key?>">
									<?=($researchInProgress!==false?'Queue select':'Select')?>
								</label>
							</div>
						<?php } else { ?>
							<div class="researchselector">
								<label class="mainSubmit disabled">No Selection Possible</label>
							</div>
						<?php } ?>
					</div>
				</div>

				<? if($research['inProgress']) {?>
					<div class="blockHeader fw-row">
						<i class="fa fa-circle-notch fa-spin"></i> Time left:&nbsp;
						<div class="timeLeft" id="countdown_time" data-countdown="<?=$researchTimeLeft?>"></div>
					</div>
				<? } ?>

				<? if($research['queued']) { ?>
					<div class="blockHeader fw-row">
						<i class="fa fa-clock"></i> Research Queued
					</div>
				<? } ?>
			</div>
		<? } ?>

		<? if($researchQueued === false || $researchInProgress === false) { ?>
			<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
			<input id="researchsubmit" type="submit" value="<?=($researchInProgress!==false?'Queue research':'Research')?>" class="mainSubmit hoverEffect">
		<? } ?>
	</form>
</div>
<?
get_footer();
