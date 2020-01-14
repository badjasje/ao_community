<?php

function ajax_market($province, $return) {
    if(!Round::isLive()) return array('status' => 'Game is paused.');

    if((isset($_POST['demo']) && !is_array($_POST['demo'])) || (isset($_POST['build']) && !is_array($_POST['build']))) {
        return array('status' => 'Not a valid request.');
    }
    $delay = 0;
    if(!empty($_POST['delay'])) {
        if(!is_numeric($_POST['delay']) || $_POST['delay']<0 || $_POST['delay']>Settings::get('max_market_delay') || ceil($_POST['delay'])!=$_POST['delay']) {
            return array('status' => 'Delay: enter a valid number');
        }
        $delay = ceil($_POST['delay']);
    }


    $status = array('Done');
    $timestamp = current_time('timestamp');
    $units = $province->getUnits();

    // Check for numbers
    $sell = array();
    if(isset($_POST['demo'])) {
        foreach($_POST['demo'] as $key => $num) {
            if(!empty($num) && (!is_numeric($num) || $num < 0 || ceil($num)!=$num || !isset($units[$key]))) return array('status' => $key.': enter a valid number');
            if(!empty($num)) $sell[$key] = ceil($num);
        }
}
    $order = array();
    if(isset($_POST['build'])) {
        foreach($_POST['build'] as $key => $num) {
            if(!empty($num) && (!is_numeric($num) || $num < 0 || ceil($num)!=$num || !isset($units[$key]))) return array('status' => $key.': enter a valid number');
            if(!empty($num)) $order[$key] = ceil($num);
        }
    }

    if(!Market::isOpen() && count($order)) return array('status' => 'The market is closed');

    // Special units
    $specialSell = $specialOrder = $totalSell = $totalOrder = 0;
    foreach($sell as $key => $num) {
        if($units[$key]['sectype'] == 'special') $specialSell += $num;
        $totalSell += $num;
    }
    if($specialSell > Settings::get('max_special_sell')) {
        return array('status' => 'You cannot sell more than '.Settings::get('max_special_sell').' special units');
    }
    foreach($order as $key => $num) {
        if($units[$key]['sectype'] == 'special') $specialOrder += $num;
        $totalOrder += $num;
    }
    if($specialOrder > Settings::get('max_special_order')) {
        return array('status' => 'You cannot order more than '.Settings::get('max_special_order').' special units');
    }

    // You cannot sell subs when having tommy's
    if(!empty($sell['submarine'])) {
        $totalmissiles = ($province->get('tomahawk_owned') + $province->get('tomahawk_ordered'));
        $maxSubs = ($totalmissiles > 0 ? ceil($totalmissiles/2) : -1);
        if($maxSubs > -1 && $sell['submarine'] > ($units['submarine']['num'] - $maxSubs)) {
            return array('status' => 'You must sell the tomahawks occupying the submarines before you can sell them');
        }
    }

    // You cannot sell more than you have
    foreach($sell as $key => $num) {
        if($num > $units[$key]['maxsell']) return array('status' => 'You cannot sell more than you have');
    }

    // Check if you have enough space to buy, minus sell
    if($totalOrder > 0) {
        $space = $province->getUnitTypeSpace();
        $usedSpace = $province->getUnitTypeUsedSpace();
        foreach($units as $key => $unit) {
            $order_n = (!empty($order[$key]) ? intval($order[$key]) : 0);
            $sell_n = (!empty($sell[$key]) ? intval($sell[$key]) : 0);
            if(!empty($order_n)) $usedSpace[$unit['type']] += $order_n;
            if(!empty($sell_n)) $usedSpace[$unit['type']] -= $sell_n;
            if($unit['sectype']=='special') {
                if(!empty($order_n)) $usedSpace['special'] += $order_n;
                if(!empty($sell_n)) $usedSpace['special'] -= $sell_n;
            }
        }
        foreach($space as $type => $num) {
            if(isset($usedSpace[$type]) && $usedSpace[$type] > $num) {
                return array('status' => ''.($usedSpace[$type]-$num).' '.$type.' units have no housing, fix that first.');
            }
        }
    }

    // Check tomahawk space
    if(empty($order['submarine'])) {
        $totalmissiles = ($province->get('tomahawk_owned') + $province->get('tomahawk_ordered'));
        $minSubs = ($totalmissiles > 0 ? ceil($totalmissiles/2) : -1);
        if($minSubs > -1 && $units['submarine']['num'] < $minSubs) return array('status' => 'Too many tomahawks, sell them or buy submarines');
    }

    // Total order-price, without redacting trades
    $order_total = $sell_total = 0;
    foreach($order as $key => $num) {
        $order_total += ($num * $units[$key]['orderprice']);
    }

    // Selling is Trading, we substract it from order-total
    foreach($sell as $key => $num) {
        $unit = $units[$key];
        if($order_total > 0) {
            $tradenum = min($num, ceil($order_total/$unit['tradeprice']) );
            $order_total -= ($tradenum * $unit['tradeprice']);
            $sell_total += (($num-$tradenum) > 0 ? ($num-$tradenum) * $unit['sellprice'] : 0);
        } else {
            $sell_total += ($num * $unit['sellprice']);
        }
    }
    if($order_total < 0) {
        $sell_total += -$order_total;
        $order_total = 0;
    }
    $cost_total = $sell_total - $order_total; // Positive means you get some money back!

    // Check if you have enough money
    if($cost_total < 0 && $province->getMoney() < ($cost_total*-1)) {
        return array('status' => 'Insufficient funds');
    }

    // Actually sell
    foreach($sell as $key => $num) {
        $province->update($key.'_owned', $province->get($key.'_owned') - $num);
        Log::add('market sell', array('id' => $province->get('id'), 'Units sold' => $num, 'Type' => $key));
    }
    $province->update('units_sold', $province->get('units_sold') + $totalSell);
    if($specialSell > 0) $province->update('special_sold_today', $province->get('special_sold_today') + $specialSell);
    if($totalSell > 0) $status[] = Format::plural($totalSell, 'unit').' sold';

    // Actually order
    $hours = $province->getShippingTime(); // hours
    foreach($order as $key => $num) {
        $unit = $units[$key];
        // Instant order ;-) $province->update($key.'_owned', $province->get($key.'_owned') + $num);
        $province->update($key . '_ordered', $province->get($key . '_ordered') + $num);
        Order::create(array(
            'title' => $unit['normalname'], 'province_id' => $province->get('id'),
            'order_type' => 'units', 'order_value' => ($num * $unit['orderprice']),
            'unit_type' => $key, 'user_placed_id' => $province->get('id'), 'time_placed' => $timestamp,
            'delivery_time' => $timestamp + ($hours * 3600) + ($delay * 60), 'amount_ordered' => $num,
        ));
        Log::add('market order', array('id' => $province->get('id'), 'Units ordered' => $num, 'Type' => $key));
    }
    $province->update('units_ordered', $province->get('units_ordered') + $totalOrder);
    if($totalOrder > 0) $status[] = Format::plural($totalOrder, 'unit').' ordered';

    // Update
    $province->update('money', $province->getMoney() + $cost_total);
    $province->count_all_stats();

    // Get new max
    $units = $province->getUnits();
    $typeSpace = $province->getUnitTypeSpace();
    $usedTypeSpace = $province->getUnitTypeUsedSpace();
    $maxbuild = $maxdemo = $owned = $ordered = $space = $specialspace = array();
    foreach($units as $key => $unit) {
        $maxbuild[$key] = $unit['maxorder'];
        $maxdemo[$key] = $unit['maxsell'];
        $owned[$key] = $unit['num'];
        $ordered[$key] = $unit['ordered'];
        $space[$key] = $unit['space'];
        if($unit['sectype']=='special') $specialspace[$key] = $unit['specialspace'];
    }
    return array_merge($return, array(
        'success' => true, 'status' => implode(', ', $status), 'specialsold' => $province->get('special_sold_today'), 'typespace' => $typeSpace, 'usedtypespace' => $usedTypeSpace,
        'buildmax' => $maxbuild, 'demomax' => $maxdemo, 'owned' => $owned, 'ordered' => $ordered, 'space' => $space, 'specialspace' => $specialspace
    ));
}
