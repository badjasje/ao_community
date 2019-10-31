<?php

function ajax_satellite($province, $return) {
    if(!Round::isLive()) return array('status' => 'Game is paused.');

    $action = Request::post('action');
    switch($action) {
        case 'cancel': $return = ajax_satellite_cancel($province); break;
        case 'demolish': $return = ajax_satellite_demolish($province); break;
        case 'order': $return = ajax_satellite_order($province); break;
        case 'activate': $return = ajax_satellite_activate($province); break;
        default: $return = array('status' => 'Ehm.. what?');
    }

    $_SESSION['showError'] = $return['status'];
    return array('success' => true, 'status' => '', 'redirect' => Request::siteUrl().'/satellites/');
}

function ajax_satellite_cancel($province) {
    $sat = Request::post('sat');
    $order = $province->getOrderedSatellites($sat);
    if(!$order) {
        return array('status' => 'Order not found');
    }

    $result = $order->cancel();
    if($result!= true) return array('status' => $result);
    return array('success' => true, 'status' => 'Satellite order cancelled, you received '. $order->cashback(true));
}

function ajax_satellite_demolish($province) {
    if($province->getSatelliteNum() == 0) {
        return array('status' => 'No satellites found');
    }

    $sat = Request::post('sat');
    $satellite = $province->getSatellites($sat);
    if(!$satellite) {
        return array('status' => 'No such sat');
    }

    if($satellite['num'] == 0) {
        return array('status' => 'Sat not owned');
    }

    $demo_cost = round($satellite['price'] * Settings::get('sat_demo_price'));
    if($demo_cost > $province->getMoney()) {
        return array('status' => 'Insufficient funds');
    }

    $province->update('sat_owned', 0);
    $province->update('sat_endlife', 0);
    $province->update('stealth_sat_status', 0);
    $province->update('stealth_sat_time', 0);
    $province->update('money', $province->getMoney() - $demo_cost);
    Event::create(array(
        'title' => 'Sat crash: ' . $province->get('id'), 'type' => 'sat_crash', 'attacker_id' => 0, 'defender_id' => $province->get('id')
    ));
    $province->update('new_events', $province->get('new_events') + 1);
    return array('success' => true, 'status' => $satellite['name'] . ' demolished');
}

function ajax_satellite_order($province) {
    $sc = $province->getResearches('satellite_construction');
    if(!$sc || $sc['level']==0) {
        return array('status' => 'Research satellite construction you tool');
    }

    if($province->getSatelliteNum() > 0) {
        return array('status' => 'No more then one sat per province');
    }

    if(count($province->getOrderedSatellites()) > 0) {
        return array('status' => 'No more then one sat per province');
    }

    if($province->getTurns() < Settings::get('sat_turn_cost')) {
        return array('status' => 'Not enough turns');
    }

    $sat = Request::post('sat');
    $satellite = $province->getSatellites($sat);
    if(!$satellite) {
        return array('status' => 'No such sat');
    }

    if($satellite['price'] > $province->getMoney()) {
        return array('status' => 'Insufficient funds');
    }

    $timestamp = current_time('timestamp');
    $order = Order::create(array(
        'title' => $satellite['name'], 'province_id' => $province->get('id'), 'user_placed_id' => $province->get('id'),
        'unit_type' => $sat, 'time_placed' => $timestamp, 'delivery_time' => $timestamp + Settings::get('sat_delivery_time'),
        'amount_ordered' => 1, 'order_value' => $satellite['price'], 'order_type' => 'satellite',
    ));
    $province->turn_spread('build_satellite', Settings::get('sat_turn_cost'));
    $province->update('sat_in_progress', $sat);
    $province->update('money', $province->getMoney() - $satellite['price']);
    $province->update('turns', $province->getTurns() - Settings::get('sat_turn_cost'));

    return array('success' => true, 'status' => $satellite['name'].' ordered');
}

function ajax_satellite_activate($province) {
    if($province->getSatelliteNum() == 0) return array('status' => 'No satellites found');
    $sat = 'stealths'; //Request::post('sat'); // Only stealth can be activated
    $satellite = $province->getSatellites($sat);
    if(!$satellite) {
        return array('status' => 'No such sat');
    }

    if($satellite['num'] == 0) {
        return array('status' => 'Sat not owned');
    }

    if($province->getTurns() < Settings::get('stealthsat_turn_cost')) {
        return array('status' => 'Not enough turns');
    }

    if($province->get('sat_morale') < Settings::get('stealthsat_morale_cost')) {
        return array('status' => 'Not enough satellite power');
    }

    $timestamp = current_time('timestamp');
    $province->update('stealth_sat_status', 'active');
    $province->update('stealth_sat_time', $timestamp + Settings::get('stealthsat_time'));
    $province->update('sat_morale', $province->get('sat_morale') - Settings::get('stealthsat_morale_cost'));
    $province->update('turns', $province->getTurns() - Settings::get('stealthsat_turn_cost'));
    $province->turn_spread('activate_sat', Settings::get('stealthsat_turn_cost'));

    return array('success' => true, 'status' => $satellite['name'] . ' activated');
}
