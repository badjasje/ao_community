<?php


function ajax_devfunds($province, $return) {
    if(!Round::isLive()) return array('status' => 'Game is paused.');
    if(!Round::isDev() && !Round::isTest() && !Round::isSandbox()) {
        return array('status' => 'Unavailable');
    }

    $province->update('money', $province->getMoney() + Settings::get('devfunds_money'));
    $province->update('turns', $province->getTurns() + Settings::get('devfunds_turns'));
    $province->update('morale', 100);
    $province->update('morale_pool', 100);
    $province->update('sat_morale', 100);
    if($province->isDead() || $province->isProtected()) $province->update('status', 'online');

    if($research = $province->getCurrentResearch()) $research->end(); // could start queued research too
    if($research = $province->getCurrentResearch()) $research->end(); // end the queued research too

    foreach($province->getOrders() as $order) $order->end();

    return array('success' => true, 'status' => 'All set: '.
        Format::money(Settings::get('devfunds_money')).', full morale, orders, research and '.
        Format::turns(Settings::get('devfunds_turns')).' turns received');
}