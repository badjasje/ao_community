<?php
/**
 * Template Name: Bank
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();
$rates = $province->getBankInterestRates();
$all_rates = Bank::getRates(true);
$deposits = $province->getDeposits();
$dep_num = $province->getDepositNum();
$max_dep = $province->getMaxDeposit();
$max_input = floor(min($max_dep,$province->getMoney()));
$bm = $province->getResearches('bank_management');
$bank_level = $bm['level'];
$withdraw_penalty = ($bank_level >= 2 ? ($bm['level'.$bank_level.'_withdraw'] * 100) : 0);
$market_close = floor(Market::timeLeft() / 60 / 60 / 24);

$disabled = (!Bank::isOpen() || $dep_num >= $province->getMaxDeposits() ? true : false);
?>
<div id="bank" class="row pageRow">
	<div class="blockHeader spaceNotice">
		<? if(count($rates)) {?>Your current interest rate starts at <?=current($rates)?>%.<?}?>
		You can have a maximum of <?=Format::plural($province->getMaxDeposits(),'deposit')?>.
		Each deposit has a minimum of <?=$province->getMinDeposit(true)?> and a maximum of
		<span class="maxdep" data-max="<?=$max_input?>"><?=$province->getMaxDeposit(true)?></span>.
		<? if($province->hasStartingBonus('finance')) { ?>
		Your finance startbonus gives 50% more deposit.
		<? } ?>
		You currently have <span class="totaldeposits"><?=$dep_num?></span> <?=($dep_num==1?'deposit':'deposits')?>.
		<? if(count($rates) != count($all_rates)) {?><b>Market closes in <?=$market_close?> days.</b><?}?>
	</div>

	<form id="bankform" method="post">
		<div class="row no-gutters">
			<div class="col-6 col-sm-3 statCol-1 label">Days to deposit</div>
			<div class="col-6 col-sm-3">
				<select name="days" id="days" class="statCol-2 <?=($disabled?' disabled':'')?>">
				<?php foreach($rates as $rateDay => $rate) {?>
					<option name="days" value="<?=$rateDay?>">
						<?=$rateDay?> days (<?=$rate?>% daily interest)
					</option>
				<?php } ?>
				</select>
			</div>
			<div class="col-sm-3">
				<input min="<?=$province->getMinDeposit()?>" max="<?=$max_input?>" placeholder="Enter amount" type="number" id="amount" name="amount">
			</div>
			<div class="col-sm-3">
				<div class="mainSubmit maxdep<?=($disabled?' disabled':'')?>" data-max="<?=$max_input?>">MAX</div>
			</div>
		</div>
		<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
		<input type="submit" value="Deposit money" class="mainSubmit<?=($disabled?' disabled':'')?>">
	</form>
	<div class="pageSpacer"></div>

	<div class="blockHeader spaceNotice">
		Below you can see a list of all your current deposits.
		<? if($bank_level >= 2) { ?>
		Because of Bank Management research level 2 or higher, your locked deposits may be withdrawn for a <?=$withdraw_penalty?>% fee and no interest.
		<? } ?>
	</div>
	<div id="banklist" class="aoTable">
		<div class="row unitRow headerRow">
			<div class="col-md-3 celBlock">Deposited</div>
			<div class="col-md-3 celBlock">Including interest</div>
			<div class="col-md-3 celBlock">Time left</div>
			<div class="col-md-3 celBlock"></div>
		</div>
		<form class="withdraw hidden" method="post">
			<div class="row unitRow fw-row">
				<div class="col-md-3 celBlock">
					<span class="columnDataLeft">Deposited</span><span class="columnDataRight deposited"></span>
				</div>
				<div class="col-md-3 celBlock">
					<span class="columnDataLeft">Including interest</span><span class="columnDataRight finalamount"></span>
				</div>
				<div class="col-md-3 celBlock">
					<span class="columnDataLeft">Release date</span><span class="columnDataRight timeleft"></span>
				</div>
				<div class="col-md-3 celBlock nopadding btns">
					<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
				</div>
			</div>
		</form>
		<div class="spaceNotice blockHeader text-center noDeposits<?=($dep_num > 0?' hidden':'')?>">
			There are no deposits yet.<br>
			Remember: money in the bank cannot be stolen until you withdraw
		</div>
		<? if($dep_num > 0) { ?>
			<? foreach($deposits as $id => $deposit) { ?>
			<form class="withdraw" method="post">
				<div class="row unitRow fw-row">
					<div class="col-md-3 celBlock">
						<span class="columnDataLeft">Deposited</span>
						<span class="columnDataRight deposited"><?=$deposit->deposited(true)?></span>
					</div>
					<div class="col-md-3 celBlock">
						<span class="columnDataLeft">Including interest</span>
						<span class="columnDataRight finalamount"><?=$deposit->finalAmount(true)?></span>
					</div>
					<div class="col-md-3 celBlock">
						<span class="columnDataLeft">Release date</span>
						<span class="columnDataRight timeleft" data-countdown="<?=$deposit->timeLeft()?>"></span>
					</div>
					<div class="col-md-3 celBlock nopadding btns">
						<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
						<input type="hidden" name="available" class="available" value="<?=$deposit->availableAmount(true)?>">
						<input type="hidden" name="depositid" class="depositid" value="<?=$id?>">
						<? if($deposit->unlocked()) { ?>
							<button class="mainSubmit hoverEffect withdrawBtn" type="submit"><?=($deposit->timeLeft()<0?'Withdraw':'Cancel')?></button>
						<? } ?>
					</div>
				</div>
			</form>
			<? } ?>
		<? } ?>

		<div class="row no-gutters spaceNotice">
			<div class="statCol-1 col-md-4 label">
				Deposited: <span class="total_amount"><?=$province->getDepositAmount(true)?></span>
				(<span class="totaldeposits"><?=$dep_num?></span> deposits)
			</div>
			<div class="statCol-2 col-md-4 label">
				Final: <span class="total_final"><?=$province->getDepositFinal(true)?></span>
			</div>
			<div class="statCol-3 col-md-4 label">
				Available (unlocked): <span class="total_available"><?=$province->getDepositAvailable(true)?></span>
			</div>
		</div>
	</div>
</div>
<?
get_footer();