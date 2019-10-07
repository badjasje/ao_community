<?php

function ajax_removenp($province, $return) {
    // @todo: use new LocalEvent();
    if(!Round::isLive()) return array('status' => 'Game is paused.');
    $new_event_id = wp_insert_post(array(
        'post_title' => 'Nukeprotection removed for '.$province->id,
        'post_status' => 'publish', 'post_type' => 'event_local', 'post_author' => $province->id
    ));
    update_field('attacktype', 'nukeprotection', $new_event_id);
    update_field('defender_id', $province->id, $new_event_id);
    update_field('attacker_id', $province->id, $new_event_id);
    update_field('time_attacked', current_time('timestamp'), $new_event_id);
    $province->update('new_events', intval($province->get('new_events')) + 1 );

    $province->update('status', 'online');
    return array('success' => true, 'status' => 'Protection removed');
}