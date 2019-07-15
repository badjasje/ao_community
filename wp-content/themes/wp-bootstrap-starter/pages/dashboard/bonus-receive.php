<?php
foreach($province->getBonuses() as $bonus) {
	if(!$bonus->isUsed()) {
		?>
		<div class="clanBonus fw-row">
			<div class="blockHeader">You can now receive a clan bonus of <?=$bonus->money(true)?> and <?=$bonus->turns(true)?> turns.</div>
			<div class="blockHeader spaceNotice">Auto receive <span data-countdown="<?=$bonus->timeLeft()?>"></span></div>
			<form method="post" class="retrieveBonusForm" class="fw-row">
				<input type="hidden" name="id" value="<?=$bonus->get('id')?>">
				<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				<input type="submit" name="submit" value="Receive Bonus" class="mainSubmit retrieveBonus">
			</form>
			<div class="pageSpacer"></div>
		</div>
		<?
	}
}
