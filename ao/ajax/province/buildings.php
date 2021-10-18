<?php

function ajax_buildings($province, $return) {
    if(!Round::isLive()) return array('status' => 'The round has ended.');
    $buildings = $province->getBuildings();

    if(!is_array($_POST['demo']) || !is_array($_POST['build'])) {
        return array('status' => 'Not a valid request.');
    }

    $status = array('Done');

    // Demo first, opens up land
    $buildingsNum = $province->getBuildingsNum();
    $money = $province->getMoney();
    $demo = array();
    $demo_num = $demo_price = 0;
    foreach($_POST['demo'] as $key => $num) {
        if(empty($num) || !is_numeric($num) || $num < 0 || !isset($buildings[$key])) continue;
        $demo[$key] = min($num, $buildings[$key]['maxdemo']);
        $demo_num += $demo[$key];
        $demo_price += $demo[$key] * $buildings[$key]['demoprice'];
    }

    if($demo_num == $buildingsNum) {
        return array('status' => 'Cannot demolish all your buildings');
    }

    if($demo_price > $money) {
        return array('status' => 'Insufficient funds');
    }

    foreach ($demo as $key => $count) {
        $province->update($key, $province->get($key) - $count);
    }
    $province->update('buildings_built', $province->get('buildings_built') - $demo_num);
    $province->update('money', $money - $demo_price);
    if($demo_num > 0) $status[] = $demo_num.' buildings demolished';

    // Recalculate maxbuild and freeland for building
    $province->calculateFreeLand();
    $buildings = $province->getBuildings();
    $freeland = $province->getFreeLand();
    $money = $province->getMoney();
    $turns = $province->getTurns();
    $build = array();
    $build_num = $build_price = 0;
    foreach($_POST['build'] as $key => $num) {
        if(empty($num) || !is_numeric($num) || $num < 0 || !isset($buildings[$key])) continue;
        // add to status if num > maxbuild
        $build[$key] = min($num, $buildings[$key]['maxbuild']);
        $build_num += $build[$key];
        $build_price += $build[$key] * $buildings[$key]['buildprice'];
    }

    $turns_needed = ceil($build_num/$province->getBuildingsPerTurn());
    if($build_price > $money) {
        $status[] = 'insufficient funds to build';
    }
    else if($turns_needed > $turns || $turns == 0) {
        $status[] = 'not enough turns to build';
    }
    else if($build_num*Settings::get('land_per_building') > $freeland) {
        $status[] = 'not enough free land';
    }
    else {
        if($build_num > 0) $status[] = $build_num.' buildings built';
        foreach ($build as $key => $count) {
            $province->update($key, $province->get($key) + $count);
            Log::add('turn build', array('id' => $province->get('id'), 'type' => $key, 'num' => $count, 'new amount' => $province->get($key) ));
        }
        $province->update('buildings_built', $province->get('buildings_built') + $build_num);
        $province->update('money', $money - $build_price);
        $province->update('turns', $turns - $turns_needed);
        $province->turn_spread('buildings', $turns_needed);
        
        if($build_num>0) $province->updateXP('building',0,$turns_needed);       
    }
	
    // Recalculate maxes
    $province->count_all_stats();
    $buildings = $province->getBuildings();
    $maxbuild = $maxdemo = $owned = array();
    foreach($buildings as $key => $building) {
        $maxbuild[$key] = $building['maxbuild'];
        $maxdemo[$key] = $building['maxdemo'];
        $owned[$key] = $building['num'];
    }

    return array_merge($return, array(
        'success' => true, 'status' => implode(', ', $status), 'maxbuild' => $province->getMaxBuild(), 'buildspace' => $province->getBuildSpace(),
        'buildmax' => $maxbuild, 'demomax' => $maxdemo, 'owned' => $owned
	));   
}