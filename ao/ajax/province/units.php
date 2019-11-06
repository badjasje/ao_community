<?php

function ajax_units($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }
    if(!is_array($_POST['build'])) {
        return array('status' => 'Not a valid request.');
    }

    $status = array('Done');
    $units = $province->getUnits();
    $money = $province->getMoney();
    $turns = $province->getTurns();
    $unitsPerTurn = $province->getUnitsPerTurn();

    $build = array();
    $build_num = $build_price = $turns_needed = 0;
    foreach($_POST['build'] as $key => $num) {
        if(empty($num) || !is_numeric($num) || $num < 0 || !isset($units[$key])) continue;
        $build[$key] = min($num, $units[$key]['maxbuild']);
        $build_num += $build[$key];
        $build_price += $build[$key] * $units[$key]['buildprice'];
        $turns_needed += $build[$key]/$unitsPerTurn[$units[$key]['type']];
    }

    // Check space per unit type
    $space = $province->getUnitTypeSpace();
    $usedSpace = $province->getUnitTypeUsedSpace();
    $type_num = array('special' => 0);
    foreach($units as $key => $unit) {
        if(!isset($type_num[$unit['type']])) $type_num[$unit['type']] = 0;
        $type_num[$unit['type']] = $usedSpace[$unit['type']] + (isset($build[$key]) ? $build[$key] : 0);
        if($unit['sectype']=='special') $type_num['special'] = $usedSpace['special'] + (isset($build[$key]) ? $build[$key] : 0);
    }
    foreach($space as $type => $num) {
        if(isset($type_num[$type]) && $type_num[$type] > $num) {
            return array('status' => ''.($type_num[$type]-$num).' '.$type.' units have no housing, fix that first.');
        }
    }

    // Check other stuff
    $turns_needed = ceil($turns_needed);
    if($build_price > $money) {
        $status[] = 'insufficient funds';
    }
    else if($turns_needed > $turns) {
        $status[] = 'not enough turns';
    }
    else {
        if($build_num > 0) $status[] = $build_num.' units built';
        foreach ($build as $key => $count) {
            $province->update($key.'_owned', $province->get($key.'_owned') + $count);
        }
        $province->update('units_built_turns', $province->get('units_built_turns') + $build_num);
        $province->update('money', $money - $build_price);
        $province->update('turns', $turns - $turns_needed);
        $province->turn_spread('unit_turn_build', $turns_needed);
    }

    // Recalculate maxes
    $province->count_all_stats();
    $units = $province->getUnits();
    $maxbuild = $owned = $space = $specialspace = $typespace = $typespecialspace = array();
    foreach($units as $key => $unit) {
        $maxbuild[$key] = $unit['maxbuild'];
        $owned[$key] = $unit['num'];
        $space[$key] = $unit['space'];
        if($unit['sectype']=='special') $specialspace[$key] = $unit['specialspace'];
        if(!isset($typespace[$unit['type']])) $typespace[$unit['type']] = $unit['space'];
        if(!isset($typespecialspace[$unit['type']])) $typespecialspace[$unit['type']] = $unit['specialspace'];
    }
    return array_merge($return, array(
        'success' => true, 'status' => implode(', ', $status), 'typespace' => $space, 'typespecialspace' => $typespecialspace,
        'buildmax' => $maxbuild, 'owned' => $owned, 'space' => $space, 'specialspace' => $specialspace
    ));
}