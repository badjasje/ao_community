<?php
class Units extends DataObject {

    static public function get($key=null) {
        return (date('d-m')=='01-04' ? shuffle_assoc(parent::get($key)) : parent::get($key));
    }

    static public function getByType($type='air') {
        $return = array();
        foreach(static::$data as $key => $unit) {
            if($unit['type'] == $type) $return[$key] = $unit;
        }
        return $return;
    }

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
            'price'			=>	'730',
            'networth'		=>	11,
            'normalname'	=>	'Black Eagle',
            'attacks'		=>  array('veh','bld'),
            'defends'		=>  array('veh'),
            'attack'		=>	55,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	53
        ),
        'jsf' 		=> array(
            'price'			=>	'795',
            'networth'		=>	11,
            'normalname'	=>	'MiG',
            'attacks'		=>  array('inf','bld'),
            'defends'		=>  array('inf'),
            'attack'		=>	60,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	42
        ),
        'typhoon' 			=> array(
            'price'			=>	'690',
            'networth'		=>	11,
            'normalname'	=>	'Harrier',
            'attacks'		=>  array('air'),
            'defends'		=>  array('air'),
            'attack'		=>	55,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	47
        ),
        'seahawk' 		=> array(
            'price'			=>	'840',
            'networth'		=>	11,
            'normalname'	=>	'Hind',
            'attacks'		=>  array('sea'),
            'defends'		=>  array('sea'),
            'attack'		=>	70,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	41
        ),
        'rah66_commanches' 	=> array(
            'price'			=>	'840',
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
            'networth'		=>	11,
            'normalname'	=>	'B-52 Stratofortress',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	135,
            'type'			=>	'air',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	93
        ),
        
        //NEW
        'unit_1' 	=> array(
            'price'			=>	'500',
            'networth'		=>	11,
            'normalname'	=>	'F-22 Raptor',
            'attacks'		=>  array('sea','air'),
            'defends'		=>  array('sea','air'),
            'attack'		=>	36,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	30
        ),
        'unit_2' 	=> array(
            'price'			=>	'860',
            'networth'		=>	11,
            'normalname'	=>	'F-18 Fighter Hornet',
            'attacks'		=>  array('bld','air'),
            'defends'		=>  array('air'),
            'attack'		=>	60,
            'type'			=>	'air',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea','regular'),
            'life'			=>	45
        ),

        /// VEHICLES ///
        'sam' 		=> array(
            'price'			=>	'885',
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
            'price'			=>	'685',
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
            'price'			=>	'565',
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
            'networth'		=>	11,
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
            'networth'		=>	11,
            'normalname'	=>	'SAM Tank',
            'attacks'		=>  array('air'),
            'defends'		=>  array('air'),
            'attack'		=>	65,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	39
        ),
        'artillery' 		=> array(
            'price'			=>	'1650',
            'networth'		=>	11,
            'normalname'	=>	'Paladin',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(''),
            'attack'		=>	115,
            'type'			=>	'veh',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	85
        ),
        //NEW
        
        'unit_3' 		=> array(
            'price'			=>	'440',
            'networth'		=>	11,
            'normalname'	=>	'K2 Black Panthers',
            'attacks'		=>  array('veh','inf'),
            'defends'		=>  array('veh','inf'),
            'attack'		=>	32,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	27
        ),
        
         'unit_4' 		=> array(
            'price'			=>	'695',
            'networth'		=>	11,
            'normalname'	=>	'M1 Abram Tank',
            'attacks'		=>  array('veh','bld'),
            'defends'		=>  array('veh'),
            'attack'		=>	50,
            'type'			=>	'veh',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	40
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
            'life'			=>	150
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
            'price'			=>	'470',
            'networth'		=>	11,
            'normalname'	=>	'Conscript',
            'attacks'		=>  array('veh'),
            'defends'		=>  array('veh'),
            'attack'		=>	35,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	19
        ),
        'rifle' 		=> array(
            'price'			=>	'810',
            'networth'		=>	11,
            'normalname'	=>	'Rifle infantry',
            'attacks'		=>  array('inf'),
            'defends'		=>  array('inf'),
            'attack'		=>	55,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	42
        ),
        'rocket' 		=> array(
            'price'			=>	'435',
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
            'price'			=>	'550',
            'networth'		=>	11,
            'normalname'	=>	'Rocketeer',
            'attacks'		=>  array('sea','air','bld'),
            'defends'		=>  array('sea','air'),
            'attack'		=>	37,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	33
        ),
        'paratrooper' 		=> array(
            'price'			=>	'880',
            'networth'		=>	11,
            'normalname'	=>	'G.I',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	70,
            'type'			=>	'inf',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	35
        ),
        'armoured' 		=> array(
            'price'			=>	'615',
            'networth'		=>	11,
            'normalname'	=>	'Guardian G.I',
            'attacks'		=>  array('air','bld'),
            'defends'		=>  array('air'),
            'attack'		=>	45,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	33
        ),
        
        //NEW
        
        'unit_5' 		=> array(
            'price'			=>	'325',
            'networth'		=>	11,
            'normalname'	=>	'Machine Gunners',
            'attacks'		=>  array('air','inf'),
            'defends'		=>  array('air','inf'),
            'attack'		=>	22,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	16
        ),
        
        'unit_6' 		=> array(
            'price'			=>	'440',
            'networth'		=>	11,
            'normalname'	=>	'Cyborg Commando',
            'attacks'		=>  array('inf','bld'),
            'defends'		=>  array('inf'),
            'attack'		=>	30,
            'type'			=>	'inf',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('ground','regular'),
            'life'			=>	25
        ),
	
        /// SEA ///
        'submarine' 		=> array(
            'price'			=>	'950',
            'networth'		=>	11,
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
            'price'			=>	'1415',
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
            'networth'		=>	11,
            'normalname'	=>	'Sea Scorpion',
            'attacks'		=>  array('inf'),
            'defends'		=>  array('inf'),
            'attack'		=>	80,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	49
        ),
        'destroyer' 		=> array(
            'price'			=>	'1365',
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
            'price'			=>	'1090',
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
            'networth'		=>	11,
            'normalname'	=>	'Battleship',
            'attacks'		=>  array('bld'),
            'defends'		=>  array(),
            'attack'		=>	155,
            'type'			=>	'sea',
            'sectype'		=>	'bk',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	145
        ),
        
        //NEW
        'unit_7'	 		=> array(
            'price'			=>	'975',
            'networth'		=>	11,
            'normalname'	=>	'Stealth Boat',
            'attacks'		=>  array('veh','sea'),
            'defends'		=>  array('veh','sea'),
            'attack'		=>	70,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	55
        ),
        'unit_8'	 		=> array(
            'price'			=>	'1450',
            'networth'		=>	11,
            'normalname'	=>	'USS Zumwalt',
            'attacks'		=>  array('sea','bld'),
            'defends'		=>  array('sea'),
            'attack'		=>	110,
            'type'			=>	'sea',
            'sectype'		=>	'normal',
            'attacktype'	=>	array('air_sea'),
            'life'			=>	90
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