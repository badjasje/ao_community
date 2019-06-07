<?php
class Researches extends DataObject {

    static $data = array(
        'money_production'          => array(
            'name'                  => 'Money production',
            'description'           => 'Increases hourly income',
            'level1'                => 'Income increased to {value} per hour',
            'level1_value'          => 25000,
            'level2'                => 'Income increased to {value} per hour',
            'level2_value'          => 35000,
            'maxlevel'              => 2,
            'duration'              =>  '15'
        ),
        /*'raid_protection'     => array(
            'name'                  => 'Raid protection',
            'level1'                => 'Automatically builds 15 antimissile systems, 35 powerplants and puts your account in Assault Protection for 30 minutes when building count drops to 0. Raid protection has a cooldown period of 24 hours.',
            'maxlevel'              => 1,
            'description'           => 'Power Plants life and power produced increased by 50%',
            'duration'              =>  '20'
        ),*/
        'missile_accuracy'          => array(
            'name'                  => 'Missile accuracy',
            'level1'                => '45% chance to hit target',
            'level2'                => '90% chance to hit target',
            'level3'                => 'Only 1 missile silo can be sabotaged by a saboteur. Decreases the chance of sabotaging your missile silo by 10%.',
            'maxlevel'              => 3,
            'description'           => 'Increases missile accuracy',
            'duration'              =>  '10'
        ),
        'satellite_construction'    => array(
            'name'                  => 'Satellite technology',
            'level1'                => 'Allows you to build satellites',
            'level2'                => 'Extends the maximum orbit time of satellites by 5 days',
            'level3'                => 'Construction cost of satellites reduced by 20%',
            'maxlevel'              => 3,
            'description'           => 'Enables you to build sattelites',
            'duration'              =>  '10'
        ),
        'shipping_time'             => array(
            'name'                  => 'Market shipping time',
            'level1'                => 'Shipping time reduced to 9 hours',
            'level2'                => 'Shipping time reduced to 6 hours',
            'maxlevel'              => 2,
            'description'           => 'Decreases shipping time from the market',
            'duration'              =>  '5'
        ),
        'powerplant_efficiency'     => array(
            'name'                  => 'Powerplant efficiency',
            'level1'                => 'Power Plants life and power produced increased by 50%',
            'maxlevel'              => 1,
            'description'           => 'Power Plants life and power produced increased by 50%',
            'duration'              =>  '5'
        ),
        'market_discount'           => array(
            'name'                  => 'Market discount',
            'level1'                => '{value}% discount on all units purchased from the market',
            'level1_value'          => '15',
            'level2'                => '{value}% discount on all units purchased from the market',
            'level2_value'          => '30',
            'maxlevel'              => 2,
            'description'           => 'Decreases market prices',
            'duration'              =>  '5'
        ),
        'thieving_effectiveness'    => array(
            'name'                  => 'Thief education',
            'level1'                => 'Thieves steal two times the amount of money',
            'level2'                => 'Thieves steal three times the amount of money',
            'level3'                => 'Thieves steal four times the amount of money',
            'maxlevel'              => 3,
            'description'           => 'Enables your thieves to steal more money',
            'duration'              =>  '5'
        ),
        'engineering_effectiveness' => array(
            'name'                  => 'Engineering effectiveness',
            'level1'                => '10 buildings built per turn.',
            'level2'                => '15 buildings built per turn.',
            'maxlevel'              => 2,
            'description'           => 'Increases the amount of buildings built per turn',
            'duration'              =>  '10'
        ),
        'bank_management'           => array(
            'name'                  => 'Bank management',
            'level1'                => 'Bank stores up to $ 3 500 000 and bank interest increased by 0,5%',
            'level2'                => 'Bank stores up to $ 4 500 000 and bank interest increased by 0,75% + option to withdraw money after 12 hours with no interest but with a 50% fee',
            'level3'                => 'Bank stores up to $ 5 000 000 and bank interest increased by 1,0% + the fee for early withdrawals is now 25%',
            'maxlevel'              => 3,
            'description'           => 'Increases the amount of money you can bank',
            'duration'              =>  '10'
        ),
    );

}