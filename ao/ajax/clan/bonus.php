<?php

function ajax_bonus($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }
    $bonus = Bonus::make(intval(Request::post('id')));
    if($bonus->get('id')==0) {
        return array('status' => 'No such bonus.');
    }
    if($bonus->isUsed()) {
        return array('status' => 'Bonus already used.');
    }
    if($bonus->receive()) {
        return array('success' => true, 'status' => $bonus->money(true).' money and '.$bonus->turns(true).' turns received');
    }
    return array('status' => 'Undefined error.');
}