<?php

function ajax_sendaid($province, $return) {
    if(!Round::isLive()) return array('status' => 'Game is paused.');
    $receiver = Province::make(Request::post('receiver'));
    $aid = abs(floor(Request::post('amount')));
    if(!$receiver->get('id')) return array('status' => 'Member not found');
    if($receiver->get('id') == $province->get('id')) return array('status' => 'Yourself? Really!?');
    $clan = $province->getClan();
    if(!in_array($receiver->get('id'), $clan->getMembers())) return array('status' => 'Not a clan member');
    if ($receiver->getNetworth() > $province->getNetworth())  return array('status' => 'You cannot aid a member larger in networth');
    $aid_sent = $province->get('aid_sent_today');
    if($aid_sent >= Settings::get('max_aid_times')) return array('status' => 'You already sent aid 3 times today');
    if(!is_numeric($aid) || $aid < 0) return array('status' => 'That\'s a weird number');
    if ($aid > $province->getMoney()) return array('status' => 'Insufficient funds');
    if ($aid > Settings::get('max_aid')) $aid = Settings::get('max_aid');

    $province->update('money', $province->getMoney() - $aid);
    $receiver->update('money', $receiver->getMoney() + $aid);

    $province->update('aid_sent_today', $province->get('aid_sent_today') + 1);
    $receiver->update('new_events', $receiver->get('new_events') + 1);

    // @todo: use new LocalEvent();
    $timestamp = current_time('timestamp');
    $args = array(
        'post_title'    => 'Aid sent by '.$province->get('id').' Receiver: '.$receiver->get('id'),
        'post_status'   => 'publish',
        'post_type'     => 'event_local',
        'post_author'   => $province->get('id')
    );
    $new_event_id = wp_insert_post($args);
    update_post_meta($new_event_id, 'event_ip_address', get_user_ip_address());
    update_field('defender_id', $receiver->get('id'), $new_event_id);
    update_field('attacker_id', $province->get('id'), $new_event_id);
    update_field('attacktype', 'aid', $new_event_id);
    update_field('time_attacked', $timestamp, $new_event_id);
    update_field('money_lost', $aid, $new_event_id);
    update_field('attacker_clan_id', $clan->get('id'), $new_event_id);

    foreach($clan->getMembers() as $member_id) {
        $member = Province::make($member_id);
        $member->update('new_global_events', $member->get('new_global_events') + 1);
    }

    $province->update('total_aid_sent', $province->get('total_aid_sent') + $aid);
    $province->update('number_of_aids', $province->get('number_of_aids') + 1);
    $receiver->update('aid_received', $receiver->get('aid_received') + $aid);

    return array(
        'success' => true, 'noaids' => $province->get('number_of_aids'), 'max' => round(min(Settings::get('max_aid'), $province->getMoney())),
        'status' =>  Format::money($aid).' aid sent to '. $receiver->getName() .' (#'. $receiver->get('id') .')'
    );
}