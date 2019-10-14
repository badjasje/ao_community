<?php

function ajax_removenp($province, $return) {
    // @todo: use new LocalEvent();
    if(!Round::isLive()) return array('status' => 'Game is paused.');

    $ev = Event::create(array(
        'title' => 'Nukeprotection removed for '.$province->get('id'),
        'type' => 'nukeprotection',
        'defender_id' => $province->get('id')
    ), $province->get('id'));

    $province->update('status', 'online');
    return array('success' => true, 'status' => 'Protection removed');
}