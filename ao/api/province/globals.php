<?php

function api_globals($province, $return) {

    $user = User::make($province->get('id'));
    $user->update('new_global_events', 0);
    $events = $user->getEvents('global');

    $keys = array(
        'attacker_id','defender_id','winner_id',
        'attacker_clan_id', 'defender_clan_id',
        'defender_points','clan_points','status_defender',
        'money_lost','land_lost',
        'shotdown','thiefs_lost','event_spy_type',
        'outcome','missile_type',
        'dec_message',
        'bonus_money','bonus_turns',
        'attackmode','maintarget','moralecost',
        'att_total_units_lost','attacker_lost',
        'nw_damage_defender','nw_damage_attacker',
        'def_total_units_lost','total_buildings_lost','defender_lost',
    );

    $data = array();
    foreach($events as $e) {
        $ed = array(
            'type' => $e->get('attacktype'),
            'datetime' => $e->getCol1(),
        );
        foreach($keys as $key) {
            $v = $e->get($key);
            if(in_array($key,array('attacker_lost','defender_lost'))) $v=maybe_unserialize($v);
            if(!empty($v)) $ed[$key] = $v;
        }
        $data[] = $ed;
    }

    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Globals from province #'.$province->get('id')));
}
