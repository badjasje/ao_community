<?php
    
    $satellites = array(
    'laser'     => array(
            'price'         =>  '1000000',
            'networth'      =>  14,
            'targets'       => 'Buildings',
            'name'          =>  'Laser Beam Satellite',
            'shortname'     =>  'LBS',
            'desc'          =>  'Gain the ability to fire an orbital laser cannon every 5 hours.',
            'attack'        =>  25000),/*
    'comsat' 		=> array(
			'price'			=>	'500000',
			'networth'		=>	14,
			'name'			=>	'Communication Satellite',
			'desc'			=>	'Additional 10% attack power for units attacking',
			'attack'		=>	0), */
    'stealths'      => array(
            'price'         =>  '600000',
            'networth'      =>  14,
            'name'          =>  'Stealth satellite',
            'shortname'     =>  'STE',
            'desc'          =>  'When activated, the Stealth Satellite will hide your base from enemy spies, thieves, saboteurs, snipers, laser beam satellites and EMP satellites.  It hides your base for 3.5 hours.'),/*
			'attack'		=>	0),
	'spysat' 		=> array(
			'price'			=>	'500000',
			'networth'		=>	14,
			'name'			=>	'Spy satellite',
			'desc'			=>	'A spy satellite allows you to spy buildings, units and power usage of a player.',
			'attack'		=>	0),*/
	'amssat' 		=> array(
			'price'			=>	'500000',
			'networth'		=>	14,
			'name'			=>	'Anti-missile satellite',
			'shortname'     =>  'AMS',
			'desc'			=>	'An anti-missile satellite grants 100% protection against missiles.',
			'attack'		=>	0),
    'empsat'        => array(
            'price'         =>  '500000',
            'networth'      =>  14,
            'targets'       => 'Power',
            'shortname'     =>  'EMP',
            'name'          =>  'EMP satellite',
            'desc'          =>  'Disables 20% of the target power production',
            'attack'        =>  'n.a'),

    );
