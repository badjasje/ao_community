<?php
$statusMessage = 'Status: online';
$canRemove = false;
if($province->isProtected()) {
	$timer_left = $province->getProtectionTimeLeft();
	$canRemove = ($timer_left < Settings::get('nuke_protection_removal') || Round::isTest() || Round::isDev());
	$statusMessage = 'Assault protection time left: <span id="countdown_time" data-countdown="'.$timer_left.'"></span>';
}
else if($province->isDead()) {
	$statusMessage = 'Status: Dead';
}

$startingbonus = $province->getStartingBonus();

$researchInProgress = false;
if(!!$province && $r = $province->getCurrentResearch()) $researchInProgress = $r->get('name');

?>
<div class="blockHeader npMessage<?=($canRemove ?' py-0 pr-0':'')?>">
	<?=$statusMessage?>
	<? if($canRemove) { ?>
		<form method="post" id="removeProtection" class="removeProtection">
			<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
			<button type="submit" name="submit" class="mainSubmit hoverEffect"><i class="fas fa-times"></i> Remove Protection</button>
		</form>
	<? } ?>
</div>

<div class="statusBlock">
	<div class="row statusTotalRow">

		<div class="col-md-6 col-lg-4 statusRow statCol-1">
			<div class="statusInsideCol">
				<strong>Points rank</strong>
			</div>
			<div class="statusInsideCol">
				<?=$province->getPosition('moh', true)?>
			</div>

			<div class="statusInsideCol">
				<strong>Satellite power</strong>
			</div>
			<div class="statusInsideCol">
				<?=$province->getSatMorale()?>
			</div>

			<div class="statusInsideCol">
				<strong>Networth rank</strong>
			</div>
			<div class="statusInsideCol">
				<?=$province->getPosition('mog', true)?>
			</div>
			<div class="statusInsideCol">
				<strong>Defense per type <span class="hover-tip" data-toggle="tooltip" data-original-title="The coverage of defense you have against each unit type. Includes attack power of both units and defense buildings." data-placement="right">
		<i class="fa fa-info-circle" aria-hidden="true"></i>
	</span></strong>
			</div>
			<div class="statusInsideCol">
				<ul class="defByType">
				<?php foreach (base_defense_calc(get_current_user_id()) as $def):?>
				<li><?php echo $def;?></li>
				<?php endforeach;?>
				</ul>
			</div>

			<div class="statusInsideCol">
				<strong>AMS Coverage</strong>
			</div>
			<div class="statusInsideCol">
				<?=$province->getShootdownChance(true)?>
			</div>
		</div>

		<div class="col-md-6 col-lg-4 statusRow statCol-2">
			<div class="statusInsideCol">
				<strong>Conversations</strong>
			</div>
			<div class="statusInsideCol">
				<a href="/conversations/">
					<?=Format::plural($user->getMessageNum(), 'new message')?>
				</a>
			</div>

			<div class="statusInsideCol">
				<strong>Hourly income</strong>
			</div>
			<div class="statusInsideCol">
				<?=$province->getIncome(true)?>
			</div>

			<div class="statusInsideCol">
				<strong>Starting bonus</strong>
			</div>
			<div class="statusInsideCol">
				<? if($startingbonus) { ?>
					<span class="hover-tip" data-toggle="tooltip" data-title="<?=$startingbonus['description']?>" data-html="true" data-placement="left">
						<i class="fa <?=$startingbonus['icon']?>" aria-hidden="true"></i> <?=$startingbonus['name']?>
					</span>
				<? } else { ?>
					<u><a href="#startingBonus">None</a></u>
				<? } ?>
				<?php if($startingbonus['name'] == 'Land'):?>
				<div style="display: block;margin-top:5px;">You will receive new land on <?php echo date('d-m-Y H:i:s', get_user_meta( $userId, 'land_bonus_counter', true ));?></div>
				<?php endif;?>
			</div>
			

			<div class="statusInsideCol">
				<strong><u><a href="/experience-points/">Experience points</a></u></strong>
			</div>
			<div class="statusInsideCol">

			<span class="hover-tip" data-toggle="tooltip" data-title="Experience points are based on the actions you make in game. Attack, research or do various other activities to obtain more experience points." data-html="true" data-placement="left">
				<?php echo $province->get('player_xp');?>
			</span>
				
			</div>
			

			<? if(!empty($researchInProgress)) { ?>
			<div class="statusInsideCol">
				<strong>Research in progress</strong>
			</div>
			<div class="statusInsideCol">
				<?=$researchInProgress?><br><span data-countdown="<?=$province->getResearchTimeLeft()?>"></span> left
			</div>
			<? } ?>

		</div>

		<div class="col-md-6 col-lg-4 statusRow statCol-3">
			<div class="celBlock">
				<strong>Push notifications</strong>
				<ul>
					<li>Install <a href="https://t.me/assaultonlinebot" class="underline" target="_blank">Telegram</a> on your mobile
				device.</li>
					<li>Add <a href="https://t.me/assaultonlinebot" class="underline" target="_blank">assaultonlinebot</a>.</li>
					<li>Use this code <strong><?=$province->getTelegramKey()?></strong> to get instant notifications.</a></li>
				</ul>
			</div>
		</div>

	</div>
</div>

<div class="row fw-row no-gutters profileButtonRow">
	<a class="col-md-4 profileButton fourthbutton" href="/military-overview/?id=<?=$province->get('id')?>">
		<i class="fa fa-bars"></i> Military overview
	</a>

	<a class="col-md-4 profileButton clanMessageButton" href="/player-statistics/">
		<i class="fas fa-chart-line"></i> View statistics
	</a>

	<a class="col-md-4 profileButton secondButton" href="/users/profile/edit/">
		<i class="fa fa-wrench"></i> Edit profile
	</a>
</div>
