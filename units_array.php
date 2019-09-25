<?php

$units = array(

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
	/*'dragon' 		=> array(
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
	),*/
    'f22_raptors' 		=> array(
		'price'			=>	'830',
		'networth'		=>	11,
		'normalname'	=>	'Black Eagle',
		'attacks'		=>  array('veh','bld'),
		'defends'		=>  array('veh'),
		'attack'		=>	55,
		'type'			=>	'air',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	45
	),
    'rah66_commanches' 	=> array(
		'price'			=>	'875',
		'networth'		=>	11,
		'normalname'	=>	'Siege Chopper',
		'attacks'		=>  array('inf','veh','bld'),
		'defends'		=>  array('inf','veh'),
		'attack'		=>	65,
		'type'			=>	'air',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	45
	),
	'b2_bomber' 	=> array(
		'price'			=>	'2200',
		'networth'		=>	13,
		'normalname'	=>	'B-52 Stratofortress',
		'attacks'		=>  array('bld'),
		'defends'		=>  array(),
		'attack'		=>	120,
		'type'			=>	'air',
		'sectype'		=>	'bk',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	95
	),
	'jsf' 		=> array(
		'price'			=>	'750',
		'networth'		=>	11,
		'normalname'	=>	'MiG',
		'attacks'		=>  array('inf','bld'),
		'defends'		=>  array('inf'),
		'attack'		=>	55,
		'type'			=>	'air',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	45
	),
	'typhoon' 			=> array(
		'price'			=>	'690',
		'networth'		=>	11,
		'normalname'	=>	'Harrier',
		'attacks'		=>  array('air','bld'),
		'defends'		=>  array('air'),
		'attack'		=>	45,
		'type'			=>	'air',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	50
	),
	'seahawk' 		=> array(
		'price'			=>	'830',
		'networth'		=>	10,
		'normalname'	=>	'Hind',
		'attacks'		=>  array('sea'),
		'defends'		=>  array('sea'),
		'attack'		=>	65,
		'type'			=>	'air',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea','regular'),
		'life'			=>	50
	),

	/// VEHICLES ///
	/*'apc' 		=> array(
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
	),*/
	'humvee' 		=> array(
		'price'			=>	'500',
		'networth'		=>	10,
		'normalname'	=>	'I.F.V',
		'attacks'		=>  array('air'),
		'defends'		=>  array('air'),
		'attack'		=>	45,
		'type'			=>	'veh',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	25
	),
	'sam' 		=> array(
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
	'abraham' 		=> array(
		'price'			=>	'730',
		'networth'		=>	11,
		'normalname'	=>	'Mirage Tank',
		'attacks'		=>  array('inf','bld'),
		'defends'		=>  array('inf'),
		'attack'		=>	50,
		'type'			=>	'veh',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	35
	),
	'artillery' 		=> array(
		'price'			=>	'1275',
		'networth'		=>	13,
		'normalname'	=>	'Paladin',
		'attacks'		=>  array('bld'),
		'defends'		=>  array(''),
		'attack'		=>	75,
		'type'			=>	'veh',
		'sectype'		=>	'bk',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	55
	),
	'm70mlrs' 			=> array(
		'price'			=>	'835',
		'networth'		=>	10,
		'normalname'	=>	'Apocalypse Tank',
		'attacks'		=>  array('air','inf'),
		'defends'		=>  array('air','inf'),
		'attack'		=>	65,
		'type'			=>	'veh',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	55
	),
	'm270_rocket' 		=> array(
		'price'			=>	'540',
		'networth'		=>	10,
		'normalname'	=>	'Tesla Tank',
		'attacks'		=>  array('sea','bld'),
		'defends'		=>  array('sea'),
		'attack'		=>	40,
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
		'attacktype'	=>	array('spy'),
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
	'paratrooper' 		=> array(
		'price'			=>	'1100',
		'networth'		=>	13,
		'normalname'	=>	'G.I',
		'attacks'		=>  array('bld'),
		'defends'		=>  array(),
		'attack'		=>	60,
		'type'			=>	'inf',
		'sectype'		=>	'bk',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	40
	),
	'grenade' 		=> array(
		'price'			=>	'445',
		'networth'		=>	11,
		'normalname'	=>	'Conscript',
		'attacks'		=>  array('veh','bld'),
		'defends'		=>  array('veh'),
		'attack'		=>	30,
		'type'			=>	'inf',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	20
	),
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
	'rifle' 		=> array(
		'price'			=>	'765',
		'networth'		=>	10,
		'normalname'	=>	'Rifle infantry',
		'attacks'		=>  array('inf'),
		'defends'		=>  array('inf'),
		'attack'		=>	50,
		'type'			=>	'inf',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	40
	),
	'rocket' 		=> array(
		'price'			=>	'850',
		'networth'		=>	10,
		'normalname'	=>	'Tesla Trooper',
		'attacks'		=>  array('sea'),
		'defends'		=>  array('sea'),
		'attack'		=>	50,
		'type'			=>	'inf',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	65
	),
	'armoured' 		=> array(
		'price'			=>	'540',
		'networth'		=>	10,
		'normalname'	=>	'Guardian G.I',
		'attacks'		=>  array('air','veh'),
		'defends'		=>  array('air','veh'),
		'attack'		=>	35,
		'type'			=>	'inf',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	25
	),
	'flamethrower' 	=> array(
		'price'			=>	'500',
		'networth'		=>	11,
		'normalname'	=>	'Rocketeer',
		'attacks'		=>  array('sea','air','bld'),
		'defends'		=>  array('sea','air','bld'),
		'attack'		=>	40,
		'type'			=>	'inf',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('ground','regular'),
		'life'			=>	30
	),


	/// SEA ///
	'battleship' 		=> array(
		'price'			=>	'2350',
		'networth'		=>	13,
		'normalname'	=>	'Battleship',
		'attacks'		=>  array('bld'),
		'defends'		=>  array(),
		'attack'		=>	135,
		'type'			=>	'sea',
		'sectype'		=>	'bk',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	120
	),
	'frigate' 		=> array(
		'price'			=>	'965',
		'networth'		=>	10,
		'normalname'	=>	'Sea Scorpion',
		'attacks'		=>  array('inf'),
		'defends'		=>  array('inf'),
		'attack'		=>	70,
		'type'			=>	'sea',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	55
	),
	/*'carrier' 		=> array(
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
	),*/
	/*'stealth' 			=> array(
        'price'			=>	'900',
        'networth'		=>	10,
        'normalname'	=>	'Zumwalt',
        'attacks'		=>  array('inf'),
        'defends'		=>  array('inf'),
        'attack'		=>	65,
        'type'			=>	'sea',
        'life'			=>	55
    ),*/
	'submarine' 		=> array(
		'price'			=>	'1090',
		'networth'		=>	11,
		'normalname'	=>	'Submarine',
		'attacks'		=>  array('sea','bld'),
		'defends'		=>  array('sea'),
		'attack'		=>	65,
		'type'			=>	'sea',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	70
	),
	'cruiser' 			=> array(
		'price'			=>	'1400',
		'networth'		=>	11,
		'normalname'	=>	'Cruiser',
		'attacks'		=>  array('air','bld'),
		'defends'		=>  array('air'),
		'attack'		=>	100,
		'type'			=>	'sea',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	90
	),
	'destroyer' 		=> array(
		'price'			=>	'1235',
		'networth'		=>	11,
		'normalname'	=>	'Destroyer',
		'attacks'		=>  array('veh','inf','bld'),
		'defends'		=>  array('veh','inf'),
		'attack'		=>	90,
		'type'			=>	'sea',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	70
	),
	'sparrow'	 		=> array(
		'price'			=>	'1150',
		'networth'		=>	10,
		'normalname'	=>	'Corvette',
		'attacks'		=>  array('sea','veh'),
		'defends'		=>  array('sea','veh'),
		'attack'		=>	85,
		'type'			=>	'sea',
		'sectype'		=>	'normal',
		'attacktype'	=>	array('air_sea'),
		'life'			=>	75
	),
);