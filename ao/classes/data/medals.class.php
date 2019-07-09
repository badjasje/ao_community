<?php
class Medals extends DataObject {

    // @todo: add icons
    static $data = array(
        'moe' => array(
            'code' => 'moe',
            'icon' => '',
            'name' => 'Medal of Earth',
            'description' => 'Highest land area at the end of round',
            'format' => 'land',
        ),
        'moh' => array(
            'code' => 'moh',
            'icon' => '',
            'name' => 'Medal of Honor',
            'description' => 'Most clan points gained by a province',
            'format' => 'points',
        ),
        'mog' => array(
            'code' => 'mog',
            'icon' => '',
            'name' => 'Medal of Growth',
            'description' => 'Highest networth at the end of round',
            'format' => 'networth',
        ),
        'moc' => array(
            'code' => 'moc',
            'icon' => '',
            'name' => 'Medal of Courage',
            'description' => 'Most attacks made by a province during clan war',
        ),
        'mod' => array(
            'code' => 'mod',
            'icon' => '',
            'name' => 'Medal of Death',
            'description' => 'Killed most provinces during clan wars',
        ),
        'mot' => array(
            'code' => 'mot',
            'icon' => '',
            'name' => 'Medal of Thievery',
            'description' => 'Most money stolen at the end of round',
            'format' => 'money',
        ),
        'modes' => array(
            'code' => 'modes',
            'icon' => '',
            'name' => 'Medal of Destruction',
            'description' => 'Most networth damage made using missiles',
            'format' => 'networth',
        ),
        'modev' => array(
            'code' => 'modev',
            'icon' => '',
            'name' => 'Medal of Devastation',
            'description' => 'Most networth damage done in a single attack. Attack must be done in clan war',
            'format' => 'networth',
        ),
        'mor' => array(
            'code' => 'mor',
            'icon' => '',
            'name' => 'Medal of Recruitment',
            'description' => 'Succesfully invited most new players',
        ),
    );
}
