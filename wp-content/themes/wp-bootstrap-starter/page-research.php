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

	<?
	if($researchInProgress !== false && $researchQueued === false) {
		helpText('Queueing research takes extra turns', 'research', 'warning');
	}
	else if(!$hasResearch) {
		helpText('Every hour of research adds to your networth', 'research', 'reminder');
	}
	?>
</div>
<?
get_footer();
