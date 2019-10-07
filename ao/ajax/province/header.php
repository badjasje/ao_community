<?php
// Returns clean & formatted data
// Unneeded: $province = CurrentUser::make()->getProvince();

function ajax_header($province, $return) {
    return array(
        'success' => true,
        'globals' => $province->getGlobalNum(),
        'locals' => $province->getLocalNum(),
        'messages' => $province->getMessageNum(),
        'formatted' => array(
            'turns'	=> $province->getTurns(true), 'networth'=> $province->getNetworth(true), 'money'	=> $province->getMoney(true),
            'morale' => $province->getMorale(true), 'land' => $province->getLand(true), 'freeland'=> $province->getFreeLand(true),
            'power'	=> $province->getPower(true),
        ),
        'clean' => array(
            'turns'	=> $province->getTurns(), 'networth'=> $province->getNetworth(), 'money'	=> $province->getMoney(),
            'morale' => $province->getMorale(), 'land' => $province->getLand(), 'freeland'=> $province->getFreeLand(),
            'power'	=> $province->getPower(),
        )
    );
}