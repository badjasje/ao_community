<?php
/**
 * Template Name: Send aid
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$aid_sent = $province->get('aid_sent_today');
$maxAmount = round(min(Settings::get('max_aid'), $province->getMoney()));

$can_send = current_time('timestamp') >= (Round::startDate()+Settings::get('start_round_no_aid'));

$clan = $province->getClan();
$members = array();
if($clan) {
	$clanmembers = $clan->getMembers();//ID's
	foreach($clanmembers as $member_id) {
		$member = Province::make($member_id);
		if($member_id != $province->get('id') && $member->getNetworth() <= $province->getNetworth()) $members[] = $member;
	}
}
?>
<div class="row pageRow">
	<div class="fw-row">

		<div class="blockHeader">
			<? if($can_send) { ?>
			You can send aid <?=Settings::get('max_aid_times')?> times per day, with a maximum of <?=Format::money(Settings::get('max_aid'))?> per aid.
			<? } else { ?>
			You cannot send aid in the first <?=(Settings::get('start_round_no_aid')/60/60)?> hours of the round.
			<? } ?>
		</div>
		<div class="blockHeader spaceNotice">
			You have sent aid <span id="aidssent"><?=$aid_sent?></span> times today
		</div>

		<? if(count($members)) {?>
			<form id="aid">
				<div class="row no-gutters">
					<div class="col-md-6 no-gutters">
						<div class="row no-gutters">
							<div class="attackDropdown statCol-1 no-gutters">Player to aid</div>
							<div class="attackDropdown statCol-2 no-gutters p-0">
								<select name="receiver" class="attackTypeInput">
									<? foreach ($members as $member) { ?>
										<option name="receiver" value="<?=$member->get('id')?>"><?=$member->getName()?> (#<?=$member->get('id')?>)</option>
									<? } ?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-6 no-gutters">
						<div class="row no-gutters">
							<div class="col-sm-6 bankCol">
								<input class="inputnr" min="0" max="<?=$maxAmount?>"<?=(!$can_send?' disabled':'')?> placeholder="Enter amount" type="number" id="amount" name="amount" />
							</div>
							<div id="maxaid" class="col-sm-6 bankCol mainSubmit">
								MAX
							</div>
						</div>
					</div>
				</div>

				<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				<input type="submit" value="Send aid" class="mainSubmit"<?=(!$can_send?' disabled':'')?>>
			</form>
		<? } else if($aid_sent < Settings::get('max_aid_times'))  { ?>
			<div class="blockHeader spaceNotice">
			<?
			if(!$clan || count($clanmembers)==1) echo 'Clanmates can help each other by sending aid.';
			else echo 'You can only help clanmates who have a lower networth than you.';
			?>
			</div>
		<? } ?>

	</div>

</div>
<?php
get_footer();