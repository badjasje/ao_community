<?php

function ajax_declare($province, $return) {
    $timestamp = current_time('timestamp');

    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }

    $myClan = $province->getClan();
    if(empty($myClan->get('id'))) return array('status' => 'You must be in a clan');
    $userCanDeclare = $myClan->isCLT();
    if(!$userCanDeclare) return array('status' => 'Only CL\'s and CT\'s can declare');
    $myCooldownlist = $myClan->getCooldownList();

    $clan_id = Request::post('clan_id');
    $dec_msg = Request::post('dec_msg');
    if(empty($clan_id)) return array('status' => 'No such clan');
    $clan = Clan::make($clan_id);
    if(empty($clan->get('id'))) return array('status' => 'Clan not found');

    $warType = $clan->getWarType($myClan->get('id'));
    $inCooldown = array_key_exists($clan_id, $myCooldownlist);
    $inWar = in_array($warType, array('incoming', 'mutual'));

    // You can only declare war on a clan with a total networth within 40% of your clan’s networth.
    // If someone has declared war on you, you can declare a mutual war
    // Declaring a mutual war has no networth limitations and in a mutual war there are no networth limitations for attacks either.
    // This means that declaring a war always presents a risk where the other clan can grow and declare a mutual war.
    $inRange = $clan->inRange() || $warType == 'outgoing';

    // Peace can be declared 24 hours after war was declared.
    $canPeace = $clan->canPeace($myClan->get('id'));

    // There is a limit on amount incoming wars possible at the same time for one clan.
    // The limit is 3 incoming wars, after this limit is reached it is not possible for anyone to declare war on this clan, included mutualing.
    if(!$canPeace && !$inWar && !$inCooldown && $inRange) {
        if(count($clan->getIncomingWars()) >= Settings::get('max_incoming_wars')) return array('status' => 'Clan cannot be warred');
    }

    // A war can last for a maximum of 72h, it will then auto-peace.

    // After peace there is a 72 hour cooldown before you can declare war on that clan again,
    // unless that clan has a war declared on you, then there will be a 12h cooldown before you can resume war.
    $canResume = $clan->canResume($myClan->get('id'));

    if(!$canPeace && !$inWar && $inCooldown && $canResume) {
        if(count($clan->getIncomingWars()) >= Settings::get('max_incoming_wars')) return array('status' => 'War cannot be resumed');
    }



    if($canPeace) {
        $clan->peaceWar($dec_msg);
        $_SESSION['showError'] = 'Peace declared';
        return array('success' => true, 'refresh' => true, 'status' => 'Peace declared');
    }

    if($inWar) {
        return array('success' => false, 'status' => 'You are already at war with this clan');
    }

    if($inCooldown) {
        if($canResume) {
            $clan->resumeWar();
            $_SESSION['showError'] = 'War resumed';
            return array('success' => true, 'refresh' => true, 'status' => 'War resumed');
        }

        return array('success' => false, 'status' => 'In cooldown: ' . Format::time_diff($myCooldownlist[$clan_id]));
    }

    if($inRange) {
        $clan->declareWar($dec_msg);
        $_SESSION['showError'] = ($warType=='outgoing'?'Mutual':'War').' declared';
        return array('success' => true, 'refresh' => true, 'status' => ($warType=='outgoing'?'Mutual':'War').' declared');
    }

    return array('success' => false, 'status' => 'Currently not in range');
}
