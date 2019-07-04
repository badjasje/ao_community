<?php
foreach($province->getBonuses() as $bonus) {
	if(!$bonus->isUsed()) {
		?>
		<div class="clanBonus fw-row">
			<div class="blockHeader">You can now receive a clan bonus of <?=$bonus->money(true)?> and <?=$bonus->turns(true)?> turns.</div>
			<div class="blockHeader spaceNotice">Auto receive <span data-countdown="<?=$bonus->timeLeft()?>"></span></div>
			<button type="button" class="mainSubmit retrieveBonus" data-id="<?=$bonus->get('id')?>">Receive Bonus</button>
			<div class="pageSpacer"></div>
		</div>
		<?
	}
}
