<?php

function ajax_land_explore($province, $return) {
    if(!Round::isLive()) return array('status' => 'Game is paused.');
    $postedTurns = abs(floor(Request::post('turns')));

    if ($postedTurns < 1 || !is_numeric(($postedTurns))) {
        return array('status' => 'Not a valid number.');
    }

    $perturnm2 = $province->getExplorationRate();
    if($perturnm2 < 0) {
        return array('status' => 'No more exploring possible');
    }

    $turns = $province->getTurns();
    if($turns < $postedTurns) {
        return array('status' => 'Not enough turns');
    }

    $maxLand = $province->getMaxExploreLand();
    $postedLand = ($postedTurns*$perturnm2);
    if ($maxLand < $postedLand) {
        return array('status' => 'You can only explore '. Format::land($maxLand).'</strong> more land.');
    }

    $ownedland = $province->getLand();
    $province->update('turns', round($turns-$postedTurns));
    $province->update('land', round($ownedland + $postedLand));
    $province->update('explored_today', round($province->get('explored_today') + $postedLand));
    $province->turn_spread('exploring', $postedTurns); //@wp
    $province->count_all_stats();
    $exploredToday = $province->get('explored_today');
    Log::add('land explore',array('id' => $province->get('id'),'Turns used' => $postedTurns, 'New land' => ($ownedland+$postedLand), 'Explored today' => $exploredToday));

    $perturnm2 = $province->getExplorationRate();
    $maxLand = $province->getMaxExploreLand();
    $maxAmount = floor($maxLand/$perturnm2);
    $maxSell = $province->getMaxSellLand();
    $return = array_merge($return, array(
        'success' => true,
        'status' => Format::land($postedLand).' explored',
        'newrate' => Format::land($perturnm2),
        'exploredtoday' => 'You have explored <strong>'.Format::land($exploredToday).' </strong> today.
            You can explore an additional <span class="maxexp" data-max="'. $maxAmount .'"><strong>'.Format::land($maxLand).'</strong>
            <i>('.$maxAmount.' turns)</i></span>',
        'maxturns' => $maxAmount,
        'maxsell' => $maxSell,
        'soldtoday' => Format::land(1).' has a value of '.Format::money(Settings::get('money_per_land')).'.
            You have '. $province->getFreeLand(true) .' of free land.
            You have sold <strong>'.Format::land($province->get('land_sold_today')).'</strong> today. You can sell an additional
            <strong class="maxsell" data-max="'. $maxSell .'">'. Format::land($maxSell) .'</strong>',
    ));
    return $return;
}