<?php 
$player_ID = get_current_user_id();

$PPE_level = get_user_meta($player_ID, 'level_powerplant_efficiency',true);
$PPE_multi = 1;
$pp = "Produces 3000 power.";
$app = "Produces 3000 power.";
	
if($PPE_level == 1){
	$PPE_multi = 1.5;
	$pp = 'Produces ' . 3000*$PPE_multi.' power.';
	$app = 'Produces ' . 15000*$PPE_multi.' power.';
}
$AMS = get_user_meta($player_ID, 'antimissile', true);
$def_land 	= 	get_user_meta($player_ID, 'builtland', true);
$shootdown_chance = 0;
if($AMS > 0){

$shootdown_chance = (($AMS*100)/$def_land)*100;


if($shootdown_chance >= 75){
	$shootdown_chance = 75;
}
}

$buildings = array(
    'silo' 		=> array(
			'price'			=>	'22000',
			'networth'		=>	6,
			'normalname'	=>	'Missile Silo',
			'targetname'	=> 	'silo',
			'description'	=>	"Missile silos are used to launch and store missiles. Each missile silo can store up to 1 missile.",
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	10000,
			'life'			=>	600),
    
    'command_centre' 	=> array(
			'price'			=>	'2200',
			'networth'		=>	6,
			'normalname'	=>	'Command Centre',
			'targetname'	=> 	'command',
			'description'	=>	'Command centres function as an intelligence centre for special units. Each command centre can support up to 5 special units',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	1800,
			'life'			=>	145),
	
	'shipyard' 		=> array(
			'price'			=>	'1100',
			'networth'		=>	6,
			'normalname'	=>	'Shipyard',
			'targetname'	=> 	'shipyard',
			'description'	=>	'Each shipyard houses 5 sea units',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	700,
			'life'			=>	110),
	
	'airfield' 		=> array(
			'price'			=>	'1000',
			'networth'		=>	6,
			'normalname'	=>	'Airfield',
			'targetname'	=> 	'airfield',
			'description'	=>	'Each airfield houses 10 air units',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	600,
			'life'			=>	100),
	
	'warfactory' 	=> array(
			'price'			=>	'1200',
			'networth'		=>	6,
			'normalname'	=>	'Warfactory',
			'targetname'	=> 	'warfactory',
			'description'	=>	'Each warfactory houses 10 vehicles',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	550,
			'life'			=>	105),
	
	'baracks'	 	=> array(
			'price'			=>	'700',
			'networth'		=>	6,
			'normalname'	=>	'Barracks',
			'targetname'	=> 	'barracks',
			'description'	=>	'Each barack houses 20 infantry',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	500,
			'life'			=>	80),
	
	'powerplant'	 => array(
			'price'			=>	'600',
			'description'	=>	$pp,
			'networth'		=>	6,
			'normalname'	=>	'Powerplant',
			'targetname'	=> 	'power',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	3000,
			'power'			=> 	0,
			'life'			=>	130),
	
	'advancedpowerplant' => array(
			'price'			=>	'1300',
			'networth'		=>	6,
			'description'	=>	$app,
			'normalname'	=>	'Advanced Powerplant',
			'targetname'	=> 	'power',
			'attacks'		=>  array(),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	15000,
			'power'			=> 	0,
			'life'			=>	60),
	
	'torpedolauncher'	=> array(
			'price'			=>	'1580',
			'networth'		=>	11,
			'normalname'	=>	'Torpedo Launcher',
			'targetname'	=> 	'defense',
			'description'	=>	'Torpedo launchers defend against sea units',
			'attacks'		=>  array('sea'),
			'attack'		=>	250,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	750,
			'life'			=>	360),
	
	'samsite'			=> array(
			'price'			=>	'1495',
			'networth'		=>	11,
			'normalname'	=>	'SAM Site',
			'targetname'	=> 	'defense',
			'description'	=>	'Sam sites defend against air units. Each Sam site has a 12.5% chance to shoot down tomahawk missiles.',
			'attacks'		=>  array('air'),
			'attack'		=>	225,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	600,
			'life'			=>	330),
	
	'missileturret'		=> array(
			'price'			=>	'1500',
			'networth'		=>	11,
			'normalname'	=>	'Missile Turret',
			'description'	=>	'Missile turrents defend against vehicles',
			'targetname'	=> 	'defense',
			'attacks'		=>  array('veh'),
			'attack'		=>	200,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	550,
			'life'			=>	340),
	
	'machinegunturret'	=> array(
			'price'			=>	'1465',
			'networth'		=>	11,
			'normalname'	=>	'Machinegun Turret',
			'description'	=>	'Machinegun turrets defend against infantry',
			'targetname'	=> 	'defense',
			'attacks'		=>  array('inf'),
			'attack'		=>	240,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	700,
			'life'			=>	300),
	
	'antimissile'		=> array(
			'price'			=>	'5000',
			'networth'		=>	14,
			'normalname'	=>	'Anti-Missile System',
			'targetname'	=> 	'ams',
			'description'	=>	'Each Anti-Missile System protects 100m2 of your built land. Chance to shoot down missiles is currently '.$shootdown_chance. '%. Every Anti-Missile System has a 25% chance to shoot down tomahawk missiles.',
			'attacks'		=>  array('mis'),
			'attack'		=>	0,
			'type'			=>	'bds',
			'powerprod'		=>	0,
			'power'			=>	2400,
			'life'			=>	390),
);