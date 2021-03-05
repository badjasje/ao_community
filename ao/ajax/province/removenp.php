<?php

function ajax_removenp($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'The round has ended.');
    }

    if(!$province->isProtected()) {
        return array('status' => 'Wait, why?');
    }

    $timer_left = ($province->getProtectionTimeLeft() || Round::isTest() || Round::isDev());
    if($timer_left > Settings::get('nuke_protection_removal')) {
        return array('status' => 'No can do, sorry.');
    }

    $ev = Event::create(array(
        'title' => 'Assault protection removed for '.$province->get('id'),
        'type' => 'nukeprotection',
        'defender_id' => $province->get('id')
    ), $province->get('id'));

    $province->update('status', 'online');

    return array('success' => true, 'status' => 'Assault protection removed');
}