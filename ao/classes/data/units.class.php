<?php
class Units extends DataObject {

    static $data = array(
        /// AIR UNITS ///
        'spyplane' 		=> array(
            'price'			=>	'15000',
            'networth'		=>	6,
            'normalname'	=>	'SR-71 Spyplane',
            'description'	=>	'The SR-71 Spyplane is used to gather intelligence about your targets buildings.',
            'attacks'		=>  array(),
            'defends'		=>  array(),
            'attack'		=>	0,
            'type'			=>	'air',
            'sectype'		=>	'special',
            'attacktype'	=>	array('spy'),
            'life'			=>	200
        ),
        'f22_raptors' 		=> array(
            'price'			=>	'775',
            'networth'		=>	11,
            'normalname'	=>	'Black Eagle',
            'attacks'		=>  array('veh','bld'),
            'defends'		=>  array('veh'),
            'attack'		=>	55,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	47
        ),
        'jsf' 		=> array(
            'price'			=>	'775',
            'networth'		=>	11,
            'normalname'	=>	'MiG',
            'attacks'		=>  array('inf','bld'),
            'defends'		=>  array('inf'),
            'attack'		=>	55,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	47
        ),
        'typhoon' 			=> array(
            'price'			=>	'690',
            'networth'		=>	10,
            'normalname'	=>	'Harrier',
            'attacks'		=>  array('air'),
            'defends'		=>  array('air'),
            'attack'		=>	50,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	42
        ),
        'seahawk' 		=> array(
            'price'			=>	'840',
            'networth'		=>	10,
            'normalname'	=>	'Hind',
            'attacks'		=>  array('sea'),
            'defends'		=>  array('sea'),
            'attack'		=>	60,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	51
        ),
        'rah66_commanches' 	=> array(
            'price'			=>	'850',
            'networth'		=>	11,
            'normalname'	=>	'Siege Chopper',
            'attacks'		=>  array('air','veh','bld'),
            'defends'		=>  array('air','veh'),
            'attack'		=>	60,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	51
        ),
        'b2_bomber' 	=> array(
            'price'			=>	'1800',
            'networth'		=>	13,
            'normalname'	=>	'B-52 Stratofortress',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	125,
            'type'			=>	'air',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	89
        ),

        /// VEHICLES ///
        'sam' 		=> array(
            'price'			=>	'900',
            'networth'		=>	11,
            'normalname'	=>	'Tiger Tank',
            'attacks'		=>  array('inf','bld'),
            'defends'		=>  array('inf'),
            'attack'		=>	65,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	54
        ),
        'abraham' 		=> array(
            'price'			=>	'680',
            'networth'		=>	11,
            'normalname'	=>	'Mirage Tank',
            'attacks'		=>  array('sea','inf','bld'),
            'defends'		=>  array('sea','inf'),
            'attack'		=>	50,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	42
        ),
        'm270_rocket' 		=> array(
            'price'			=>	'610',
            'networth'		=>	11,
            'normalname'	=>	'Tesla Tank',
            'attacks'		=>  array('sea','bld'),
            'defends'		=>  array('sea'),
            'attack'		=>	45,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	38
        ),
        'humvee' 		=> array(
            'price'			=>	'680',
            'networth'		=>	10,
            'normalname'	=>	'I.F.V',
            'attacks'		=>  array('veh'),
            'defends'		=>  array('veh'),
            'attack'		=>	50,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	42
        ),
        'm70mlrs' 			=> array(
            'price'			=>	'755',
            'networth'		=>	10,
            'normalname'	=>	'Apocalypse Tank',
            'attacks'		=>  array('air'),
            'defends'		=>  array('air'),
            'attack'		=>	55,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	46
        ),
        'artillery' 		=> array(
            'price'			=>	'1650',
            'networth'		=>	13,
            'normalname'	=>	'Paladin',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(''),
            'attack'		=>	115,
            'type'			=>	'veh',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	82
        ),

        /// INFANTRY ///
        'thief' 		=> array(
            'price'			=>	'5000',
            'description'	=>	'Thiefs allow you to steal money from other users.',
            'networth'		=>	6,
            'normalname'	=>	'Thief',
            'attacks'		=>  array(),
            'defends'		=>  array(),
            'attack'		=>	0,
            'type'			=>	'inf',
            'sectype'		=>	'special',
            'attacktype'	=>	array('thief'), // this was spy??
            'life'			=>	60
        ),
        'saboteur' 		=> array(
            'price'			=>	'6000',
            'description'	=>	'Saboteurs allow you to sabotage up to two missile silos. You have a 50% chance to sabotage a missile silo.',
            'networth'		=>	6,
            'normalname'	=>	'Saboteur',
            'attacks'		=>  array(),
            'defends'		=>  array(),
            'attack'		=>	0,
            'type'			=>	'inf',
            'sectype'		=>	'special',
            'attacktype'	=>	array('saboteur'),
            'life'			=>	75
        ),
        'sniper' 		=> array(
            'price'			=>	'5000',
            'description'	=>	'Snipers protect your base from thiefs, snipers, spies and saboteurs.',
            'networth'		=>	6,
            'normalname'	=>	'Sniper',
            'attacks'		=>  array(),
            'defends'		=>  array(),
            'attack'		=>	140,
            'type'			=>	'inf',
            'sectype'		=>	'special',
            'attacktype'	=>	array('sniper'),
            'life'			=>	200
        ),
        'spy' 		=> array(
            'price'			=>	'4000',
            'description'	=>	'Spies are used to gather intelligence about your targets units.',
            'networth'		=>	6,
            'normalname'	=>	'Spy',
            'attacks'		=>  array(),
            'defends'		=>  array(),
            'attack'		=>	0,
            'type'			=>	'inf',
            'sectype'		=>	'special',
            'attacktype'	=>	array('spy'),
            'life'			=>	250
        ),
        'grenade' 		=> array(
            'price'			=>	'430',
            'networth'		=>	10,
            'normalname'	=>	'Conscript',
            'attacks'		=>  array('veh'),
            'defends'		=>  array('veh'),
            'attack'		=>	24,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	30
        ),
        'rifle' 		=> array(
            'price'			=>	'810',
            'networth'		=>	10,
            'normalname'	=>	'Rifle infantry',
            'attacks'		=>  array('inf'),
            'defends'		=>  array('inf'),
            'attack'		=>	55,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	44
        ),
        'rocket' 		=> array(
            'price'			=>	'430',
            'networth'		=>	11,
            'normalname'	=>	'Tesla Trooper',
            'attacks'		=>  array('sea','bld'),
            'defends'		=>  array('sea'),
            'attack'		=>	30,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	24
        ),
        'flamethrower' 	=> array(
            'price'			=>	'510',
            'networth'		=>	11,
            'normalname'	=>	'Rocketeer',
            'attacks'		=>  array('sea','air','bld'),
            'defends'		=>  array('sea','air','bld'),
            'attack'		=>	35,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	28
        ),
        'paratrooper' 		=> array(
            'price'			=>	'1250',
            'networth'		=>	13,
            'normalname'	=>	'G.I',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	85,
            'type'			=>	'inf',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	58
        ),
        'armoured' 		=> array(
            'price'			=>	'660',
            'networth'		=>	11,
            'normalname'	=>	'Guardian G.I',
            'attacks'		=>  array('air','bld'),
            'defends'		=>  array('air'),
            'attack'		=>	45,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	37
        ),

        /// SEA ///
        'submarine' 		=> array(
            'price'			=>	'950',
            'networth'		=>	10,
            'normalname'	=>	'Submarine',
            'attacks'		=>  array('sea'),
            'defends'		=>  array('sea'),
            'attack'		=>	75,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	64
        ),
        'cruiser' 			=> array(
            'price'			=>	'1400',
            'networth'		=>	11,
            'normalname'	=>	'Cruiser',
            'attacks'		=>  array('air','bld'),
            'defends'		=>  array('air'),
            'attack'		=>	105,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	89
        ),
        'frigate' 		=> array(
            'price'			=>	'875',
            'networth'		=>	10,
            'normalname'	=>	'Sea Scorpion',
            'attacks'		=>  array('inf'),
            'defends'		=>  array('inf'),
            'attack'		=>	70,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	59
        ),
        'destroyer' 		=> array(
            'price'			=>	'1235',
            'networth'		=>	11,
            'normalname'	=>	'Destroyer',
            'attacks'		=>  array('veh','inf','bld'),
            'defends'		=>  array('veh','inf'),
            'attack'		=>	95,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	80
        ),
        'sparrow'	 		=> array(
            'price'			=>	'1150',
            'networth'		=>	11,
            'normalname'	=>	'Corvette',
            'attacks'		=>  array('veh','bld'),
            'defends'		=>  array('veh'),
            'attack'		=>	90,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	75
        ),
        'battleship' 		=> array(
            'price'			=>	'2350',
            'networth'		=>	13,
            'normalname'	=>	'Battleship',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	170,
            'type'			=>	'sea',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	125
        ),
    );
}
/**
// Old Air units
'dragon' => array(
    'price'			=>	'1500',
    'networth'		=>	14,
    'normalname'	=>	'U-2 Dragon Lady',
    'description'	=>	'The U-2 Dragon Lady is a support unit. Each U-2 Dragon Lady sent into battle supports up to 40 vehicles, providing a 20% attack boost.',
    'attacks'		=>  array(),
    'defends'		=>  array(),
    'attack'		=>	0,
    'type'			=>	'air',
    'sectype'		=>	'special',
    'attacktype'	=>	array('air_sea','regular'),
    'life'			=>	105
),

// Old Sea units
'carrier' 		=> array(
    'price'			=>	'2475',
    'networth'		=>	14,
    'normalname'	=>	'Aircraft carrier',
    'description'	=>	'The Aircraft carrier is a support unit. Each Aircraft carrier sent into battle supports up to 40 air units, providing a 20% attack boost.',
    'attacks'		=>  array(),
    'defends'		=>  array(''),
    'attack'		=>	0,
    'type'			=>	'sea',
    'sectype'		=>	'normal',
    'attacktype'	=>	array('air_sea'),
    'life'			=>	235
),
'stealth' 			=> array(
    'price'			=>	'900',
    'networth'		=>	10,
    'normalname'	=>	'Zumwalt',
    'attacks'		=>  array('inf'),
    'defends'		=>  array('inf'),
    'attack'		=>	65,
    'type'			=>	'sea',
    'life'			=>	55
),

// Old Vehicles
'apc' => array(
    'price'			=>	'1500',
    'networth'		=>	14,
    'normalname'	=>	'Battle Fortress',
    'description'	=>	'The Battle Fortress is a support unit. Each Battle Fortress sent into battle supports up to 75 infantry, providing a 20% attack boost.',
    'attacks'		=>  array(),
    'defends'		=>  array(),
    'attack'		=>	0,
    'type'			=>	'veh',
    'sectype'		=>	'special',
    'attacktype'	=>	array('ground','regular'),
    'life'			=>	105
),
'sam' 		=> array( ==> became Tiger Tank
    'price'			=>	'950',
    'networth'		=>	11,
    'normalname'	=>	'Grizzly Battle Tank',
    'attacks'		=>  array('veh','bld'),
    'defends'		=>  array('veh'),
    'attack'		=>	65,
    'type'			=>	'veh',
    'sectype'		=>	'normal',
    'attacktype'	=>	array('ground','regular'),
    'life'			=>	50
),

// Old Infantry
'navy' 			=> array(
            'price'			=>	'815',
            'networth'		=>	11,
            'normalname'	=>	'Navy SEAL',
            'attacks'		=>  array('inf','bld'),
            'defends'		=>  array('inf'),
            'attack'		=>	45,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	40
        ),
*/