<?php

function api_info($province_id, $return) {

    $user = User::make($province_id);
    $province = $user->getProvince();
    if($province->get('id') == false) {
        return array('status' => 'Not a user');
    }

    $startbonus = $province->getStartingBonus();

    $researches = array();
    foreach($province->getResearches() as $r) {
        if($r['level']>0) $researches[$r['name']] = $r['level'];
    }

    $buildings = array();
    foreach($province->getBuildings() as $b) {
        if($b['num']>0) $buildings[$b['normalname']] = $b['num'];
    }

    $units = array();
    foreach($province->getUnits() as $u) {
        if($u['num']>0) $units[$u['normalname']] = $u['num'];
    }

    $missiles = array();
    foreach($province->getMissiles() as $m) {
        if($m['num']>0) $missiles[$m['normalname']] = $m['num'];
    }

    $satellites = array();
    foreach($province->getSatellites() as $s) {
        if($s['num']>0) $satellites[$s['name']] = $s['num'];
    }

    $orders = array();
    foreach($province->getOrders() as $order) {
        $orders[] = array(
            'type'=>$order->type(),
            'subtype'=>$order->get('unit_type'),
            'title'=>$order->title(),
            'amount'=>$order->amount(),
            'time_left'=>$order->timeLeft()
        );
    }

    $data = array(
        'name' => $province->getName(),
        'status' => $province->get('status'),
        'money' => $province->getMoney(),
        'networth' => $province->getNetworth(),
        'turns' => $province->getTurns(),
        'morale' => $province->getMorale(),
        'land' => $province->getLand(),
        'power' => $province->getPower(),
        'new_globals' => $user->getGlobalNum(),
        'new_locals' => $user->getLocalNum(),
        'startbonus' => (!empty($startbonus) ? $startbonus['name'] : ''),
        'deposits_num' => $province->getDepositNum(),
        'deposits_available' => $province->getDepositAvailable(),
        'deposits_final' => $province->getDepositFinal(),
    );
    if(count($researches)) $data['researches'] = $researches;
    if(count($buildings)) $data['buildings'] = $buildings;
    if(count($units)) $data['units'] = $units;
    if(count($missiles)) $data['missiles'] = $missiles;
    if(count($satellites)) $data['satellites'] = $satellites;
    if(count($orders)) $data['orders'] = $orders;

    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Data from province #'.$province->get('id')));
}