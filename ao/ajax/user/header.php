<?php

// Returns clean & formatted data
function ajax_header($province, $return) {
    $user = CurrentUser::make();

    $return = array(
        'success' => true,
        'globals' => $user->getGlobalNum(),
        'locals' => $user->getLocalNum(),
        'messages' => $user->getMessageNum(),
        'wars' => getIncomingWars($province->getClanId()),
        'formatted' => array(
            'turns'	=> $province->getTurns(true), 'networth'=> $province->getNetworth(true), 'money' => $province->getMoney(true),
            'morale' => $province->getMorale(true), 'land' => $province->getLand(true), 'freeland'=> $province->getFreeLand(true),
            'power'	=> $province->getPower(true),
        ),
        'clean' => array(
            'turns'	=> $province->getTurns(), 'networth'=> $province->getNetworth(), 'money' => $province->getMoney(),
            'morale' => $province->getMorale(), 'land' => $province->getLand(), 'freeland'=> $province->getFreeLand(),
            'power'	=> $province->getPower(),
        )
    );
    if(rand(1,30) == 1 && in_array(date('d-m'), array('30-10','31-10'))) {
        $a = array();
        if($user->getGlobalNum() == 0) $a[] = 'globals';
        if($user->getLocalNum() == 0) $a[] = 'locals';
        if($user->getMessageNum() == 0) $a[] = 'messages';
        if(count($a)>0) $return['ghost'] = $a[mt_rand(0, count($a) - 1)];
    }
    return $return;
}