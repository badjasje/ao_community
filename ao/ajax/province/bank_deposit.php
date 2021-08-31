<?php

function ajax_bank_deposit($province, $return) {
    if(!Round::isLive()) return array('status' => 'The round has ended.');

    $amount = round(Request::post('amount'));
    $length = round(Request::post('days'));

    if(!is_numeric($amount) || !is_numeric($length)) {
        return array('status' => 'Enter a valid number');
    }

    if($amount <= 0 || $length <= 0) {
        return array('status' => 'Enter a valid number');
    }

    if($amount < $province->getMinDeposit()) {
        return array('status' => 'Deposit at least '.$province->getMinDeposit(true));
    }

    if($province->getDepositNum() >= $province->getMaxDeposits()) {
        return array('status' => 'You already made '.$province->getMaxDeposits().' deposits');
    }

    $money = $province->getMoney();
    if($amount > $money) {
        return array('status' => 'Insufficient funds');
    }

    $max_dep = $province->getMaxDeposit();
    if ($amount > $max_dep) {
        return array('status' => 'Your research doesn\'t allow you to deposit this much');
    }

    $rate = $province->getBankInterestRate($length);
    if(empty($rate)) {
        return array('status' => 'Undefined length');
    }

    $deposit = Deposit::create(array('province_id' => $province->id, 'length' => $length, 'amount' => $amount));
	$attacker->updateXP('bank');
    return array(
        'success' => true,
        'status' => $deposit->deposited(true).' deposited for '. $deposit->get('days') .' days',
        'deposited' => $deposit->deposited(true),
        'finalamount' => $deposit->finalAmount(true),
        'timeleft' => $deposit->timeLeft(),
        'max_input' => floor(min($max_dep, $province->getMoney())),
        'dep_num' => $province->getDepositNum(),
        'total_amount' => $province->getDepositAmount(true),
        'total_final' => $province->getDepositFinal(true),
        'total_available' => $province->getDepositAvailable(true)
    );
}