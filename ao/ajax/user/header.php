<?php

// Returns clean & formatted data
function ajax_header($province, $return) {
    $user = CurrentUser::make();
    return array(
        'success' => true,
        'globals' => $user->getGlobalNum(),
        'locals' => $user->getLocalNum(),
        'messages' => $user->getMessageNum(),
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
}