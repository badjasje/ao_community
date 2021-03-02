<?php

function ajax_land_explore($province, $return) {
    if(!Round::isLive()) return array('status' => 'The round has ended.');
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

    // Let do something with easter... maybe an egghunt... an Easter Egg Hunt!
    if(Format::isEaster()) {
        $treasures_today = $province->get('treasures_today');
        if(!$treasures_today) $treasures_today = 0;
        if($treasures_today < 5) {
            $gains = array(
                'You found a treasure chest with <strong>[random money]</strong>',
                'While exploring, you found a <strong>[random building]</strong>!',
                'A rogue <strong>[random inf]</strong> appears! It will now work for you',
                'What\'s prowling over there? It seems a <strong>[random air]</strong>..',
                'Score! This <strong>[random veh]</strong> is now yours.',
                'Your archaeologist dug a <strong>[random sea]</strong> up for you.',
                'Secret tunnel revealed to <strong>[random land]</strong> extra land!',
                'A magic hourglass gives <strong>[random turns]</strong> extra turns!',
                'A famous banner gave your army <strong>[random morale]</strong> extra morale',
            );
        } else $gains = array();
        $nogains = array(// WARNING: DO NOT USE [ or ] HERE!
            'Oh dear, nothing here',
            'No plain, no gain',
            'Oh well, nothing to sell',
            'Nothing found but empty ground',
            'What a bore, an empty explore',
            'In your hand... more empty land',
            'No treasure, no pleasure',
            'Oh heck, no egg',
            'The ground boils, but no spoils',
            'Ahw, failed quest with an empty chest',
            'No aid with this empty raid',
            'This bunny gives no money',
        );
        $return['expResult'] = array_rand_item(array_merge($gains, $nogains));
        if(strpos($return['expResult'],'[') !== false && strpos($return['expResult'],']') !== false) {
            $province->update('treasures_today', ++$treasures_today);
        }
        if(strpos($return['expResult'], '[random money]') !== false) {
            $money = rand(1,10) * 500;
            $province->update('money', $province->get('money') + $money);
            $return['expResult'] = str_replace('[random money]', Format::money($money), $return['expResult']);
        }
        if(strpos($return['expResult'], '[random land]') !== false) {
            $land = rand(1,5) * 500;
            $province->update('land', $province->get('land') + $land);
            $return['expResult'] = str_replace('[random land]', Format::land($land), $return['expResult']);
        }
        if(strpos($return['expResult'], '[random turns]') !== false) {
            $turns = rand(5,10);
            $province->update('turns', $province->get('turns') + $turns);
            $return['expResult'] = str_replace('[random turns]', Format::turns($turns), $return['expResult']);
        }
        if(strpos($return['expResult'], '[random morale]') !== false) {
            $morale = rand(1,3) * 10;
            $province->update('morale', $province->get('morale') + $morale);
            $return['expResult'] = str_replace('[random morale]', Format::morale($morale), $return['expResult']);
        }
        if(strpos($return['expResult'], '[random building]') !== false) {
            $buildings = Buildings::get();
            $key = array_rand_item(array_keys($buildings));
            $province->update($key, $province->get($key) + 1);
            $return['expResult'] = str_replace('[random building]', $buildings[$key]['normalname'], $return['expResult']);
        }
        if(strpos($return['expResult'], '[random inf]') !== false) {
            $units = Units::getByType('inf');
            $key = array_rand_item(array_keys($units));
            $province->update($key.'_owned', $province->get($key.'_owned') + 1);
            $return['expResult'] = str_replace('[random inf]', $units[$key]['normalname'], $return['expResult']);
        }
        if(strpos($return['expResult'], '[random air]') !== false) {
            $units = Units::getByType('air');
            $key = array_rand_item(array_keys($units));
            $province->update($key.'_owned', $province->get($key.'_owned') + 1);
            $return['expResult'] = str_replace('[random air]', $units[$key]['normalname'], $return['expResult']);
        }
        if(strpos($return['expResult'], '[random veh]') !== false) {
            $units = Units::getByType('veh');
            $key = array_rand_item(array_keys($units));
            $province->update($key.'_owned', $province->get($key.'_owned') + 1);
            $return['expResult'] = str_replace('[random veh]', $units[$key]['normalname'], $return['expResult']);
        }
        if(strpos($return['expResult'], '[random sea]') !== false) {
            $units = Units::getByType('sea');
            $key = array_rand_item(array_keys($units));
            $province->update($key.'_owned', $province->get($key.'_owned') + 1);
            $return['expResult'] = str_replace('[random sea]', $units[$key]['normalname'], $return['expResult']);
        }
        $return['expResult'] = 'Result: '.$return['expResult'];
    }

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