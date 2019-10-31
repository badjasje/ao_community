<?php

function ajax_startingbonus($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }

    if(!empty($province->getStartingBonus())) {
        return array('status' => 'You already have a startbonus.');
    }

    $bonustype = Request::post('bonustype');
    if(!$province->setStartingBonus($bonustype)) {
        return array('status' => 'No such startbonus.');
    }
    return array('success' => true, 'status' => 'Starting bonus picked');
}