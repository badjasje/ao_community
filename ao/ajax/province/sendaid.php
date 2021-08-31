<?php

function ajax_sendaid($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'The round has ended.');
    }

    if(current_time('timestamp') < (Round::startDate()+Settings::get('start_round_no_aid'))) {
        return array('status' => 'You cannot send aid in the first '.(Settings::get('start_round_no_aid')/60/60).' hours of the round.');
    }

    $receiver = Province::make(Request::post('receiver'));
    $aid = abs(floor(Request::post('amount')));
    if(!$receiver->get('id')) {
        return array('status' => 'Member not found');
    }

    if($receiver->get('id') == $province->get('id')) {
        return array('status' => 'Yourself? Really!?');
    }

    if($province->isProtected()) {
        return array('status' => 'You cannot send aid while in Assault Protection');
    }

    $clan = $province->getClan();
    $clan_members = $clan->getMembers();
    if(!in_array($receiver->get('id'), $clan_members)) {
        return array('status' => 'Not a clan member');
    }

    if ($receiver->getNetworth() > $province->getNetworth()) {
        return array('status' => 'You cannot aid a member larger in networth');
    }

    $aid_sent = $province->get('aid_sent_today');
    if($aid_sent >= Settings::get('max_aid_times')) {
        return array('status' => 'You already sent aid 3 times today');
    }

    if(!is_numeric($aid) || $aid < 1) {
        return array('status' => 'That\'s a weird number');
    }

    if ($aid > $province->getMoney()) {
        return array('status' => 'Insufficient funds');
    }

    if ($aid > Settings::get('max_aid')) $aid = Settings::get('max_aid');

    $province->update('money', $province->getMoney() - $aid);
    $receiver->update('money', $receiver->getMoney() + $aid);

    $province->update('aid_sent_today', $province->get('aid_sent_today') + 1);
    $receiver->update('new_events', $receiver->get('new_events') + 1);
	$province->updateXP('aid');
    $ev = Event::create(array(
        'title' => 'Aid sent by '.$province->get('id').' Receiver: '.$receiver->get('id'),
        'type' => 'aid',
        'send' => 'global',
        'money_lost' => $aid,
        'defender_id' => $receiver->get('id'),
        'attacker_clan_id' => $clan->get('id')
    ), $clan_members);

    $province->update('total_aid_sent', $province->get('total_aid_sent') + $aid);
    $province->update('number_of_aids', $province->get('number_of_aids') + 1);
    $receiver->update('aid_received', $receiver->get('aid_received') + $aid);

    return array(
        'success' => true, 'noaids' => $province->get('aid_sent_today'), 'max' => round(min(Settings::get('max_aid'), $province->getMoney())),
        'status' =>  Format::money($aid).' aid sent to '. $receiver->getName() .' (#'. $receiver->get('id') .')'
    );
}