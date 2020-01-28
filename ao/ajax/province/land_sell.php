<?php

function ajax_land_sell($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }

    $postedLand = abs(floor(Request::post('land')));
    if($postedLand < 0 || !is_numeric($postedLand)) {
        return array('status' => 'Not a valid number.');
    }

    $freeland = $province->getFreeLand();
    if($freeland < 0) {
        return array('status' => 'Cannot sell! Not enough free land');
    }

    if($postedLand > $freeland) {
        return array('status' => 'Not enough free land');
    }

    $maxSellLand = $province->getMaxSellLand();
    if ($maxSellLand < $postedLand) {
        return array('status' => 'Cannot sell any more land');
    }

    $province->update('land', round($province->getLand() - $postedLand));
    $province->update('land_sold_today', round($province->get('land_sold_today') + $postedLand));
    $province->update('money', $province->getMoney() + round($postedLand * Settings::get('money_per_land')));
    $province->count_all_stats();
    Log::add('land sell',array('id'=>$province->get('id'),'Sold land'=>$postedLand, 'Land sold today' => $province->get('land_sold_today')));

    $maxSell = $province->getMaxSellLand();
    $return = array_merge($return, array(
        'success' => true,
        'status' => 'You sold '.Format::land($postedLand).' for a total sum of '.Format::money($postedLand * Settings::get('money_per_land')),
        'maxsell' => $maxSell,
        'soldtoday' => Format::land(1).' has a value of '.Format::money(Settings::get('money_per_land')).'.
            You have '. $province->getFreeLand(true) .' of free land.
            You have sold <strong>'.Format::land($province->get('land_sold_today')).'</strong> today. You can sell an additional
            <strong class="maxsell" data-max="'. $maxSell .'">'. Format::land($maxSell) .'</strong>'
    ));
    return $return;
}