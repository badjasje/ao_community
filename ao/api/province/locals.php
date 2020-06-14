<?php

function api_locals($province, $return) {

    $user = User::make($province->get('id'));
    $user->update('new_events', 0);
    $events = $user->getEvents('incoming');

    /*$keys = array(
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
    );*/

    $result = array();
    foreach($events as $e) {
        $ed = array(
            'type' => $e->getHeader(),
            'title' => $e->getTitle(),
            'body' => $e->getBody(),
            'datetime' => date('Y-m-d H:i:s', $e->getCol1()),
        );
        /*foreach($keys as $key) {
            $v = $e->get($key);
            if(in_array($key,array('attacker_lost','defender_lost'))) $v=maybe_unserialize($v);
            if(!empty($v)) $ed[$key] = $v;
        }*/
        $result[] = $ed;
    }

    return array_merge($return, array('success' => true, 'data' => $result, 'status' => 'Locals from province #'.$province->get('id')));
}
