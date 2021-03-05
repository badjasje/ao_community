<?php

function ajax_startingbonus($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'The round has ended.');
    }

    if(!empty($province->getStartingBonus())) {
        return array('status' => 'You already have a starting bonus.');
    }

    $bonustype = Request::post('bonustype');
    if(!$province->setStartingBonus($bonustype)) {
        return array('status' => 'No such startbonus.');
    }
    return array('success' => true, 'status' => 'Starting bonus picked');
}