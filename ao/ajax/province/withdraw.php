<?php

function ajax_withdraw($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused');
    }

    $depositid = round(Request::post('depositid'));
    if(!is_numeric($depositid) || $depositid <= 0) {
        return array('status' => 'Not a valid deposit');
    }

    $deposit = Deposit::make($depositid);
    if($deposit->get('id') != $depositid) {
        return array('status' => 'Invalid deposit');
    }
    if($deposit->get('province_id') != $province->id) {
        return array('status' => 'Not a deposit');
    }
    if($deposit->used()) {
        return array('status' => 'Already withdrawn');
    }
    if($deposit->timeLeft() > 0 && !$deposit->unlocked()) {
        return array('status' => 'Please wait');
    }
    if(!$deposit->unlocked()) {
        return array('status' => 'Cannot withdraw this deposit');
    }
    $amount = $deposit->availableAmount(true);
    $deposit->end();
    $max_dep = $province->getMaxDeposit();
    return array(
        'success' => true,
        'status' => ($deposit->timeLeft() > 0 ? 'You canceled your deposit. ':''). $amount.' withdrawn.',
        'max_input' => floor(min($max_dep, $province->getMoney())),
        'dep_num' => $province->getDepositNum(),
        'total_amount' => $province->getDepositAmount(true),
        'total_final' => $province->getDepositFinal(true),
        'total_available' => $province->getDepositAvailable(true)
    );
}