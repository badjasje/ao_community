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
        'spyplane' => array(
            'price'         => '10000',
            'networth'      => 6,
            'normalname'    => 'RQ-170 Sentinel',
            'description'   => 'The RQ-170 Sentinel is an advanced reconnaissance UAV developed in the 2000s. It operates at extreme altitudes to gather critical intelligence behind enemy lines while evading modern air defenses. Its stealthy design and long-endurance capabilities make it a vital asset in strategic surveillance missions.',
            'attacks'       => array(),  // Excluded
            'defends'       => array(),
            'attack'        => 0,
            'type'          => 'air',
            'sectype'       => 'special',
            'attacktype'    => array('spy'),
            'life'          => 200
        ),
        'f22_raptors' => array(
            'price'         => '1045',
            'networth'      => 11,
            'normalname'    => 'F-22 Raptor',
            'description'   => 'The F-22 Raptor is a fifth-generation stealth fighter known for its exceptional agility and advanced avionics. It is designed to execute precision strikes against enemy fortifications and infantry with unmatched stealth. Its integrated sensor systems and superior maneuverability have set the standard for modern aerial combat.',
            'attacks'       => array('bld', 'inf'),
            'defends'       => array('bld', 'inf'),
            'attack'        => 70,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 55
        ),
        'jsf' => array(
            'price'         => '1193',
            'networth'      => 11,
            'normalname'    => 'F-35 Lightning II',
            'description'   => 'The F-35 Lightning II is a state-of-the-art multirole stealth fighter that integrates advanced sensor fusion with precision strike capabilities. It is built to engage enemy structures and armored vehicles under the most contested conditions. Its stealth design, combined with unmatched situational awareness, makes it a cornerstone of modern air combat.',
            'attacks'       => array('bld', 'veh'),
            'defends'       => array('bld', 'veh'),
            'attack'        => 88,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 60
        ),
        'typhoon' => array(
            'price'         => '1035',
            'networth'      => 11,
            'normalname'    => 'Eurofighter Typhoon',
            'description'   => 'The Eurofighter Typhoon is a highly agile, multirole combat aircraft engineered for rapid response. It delivers precise strikes against enemy vehicles and infantry, making it indispensable in dynamic aerial engagements. Its advanced flight controls and sensor systems ensure it remains at the forefront of modern air superiority.',
            'attacks'       => array('veh', 'inf'),
            'defends'       => array('veh', 'inf'),
            'attack'        => 75,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 59
        ),
        'seahawk' => array(
            'price'         => '1260',
            'networth'      => 11,
            'normalname'    => 'MH-60R Seahawk',
            'description'   => 'The MH-60R Seahawk is a modern naval helicopter that excels in multi-role missions at sea. It is equipped with advanced sensors and weaponry to engage enemy ships and armored assets with precision. Its robust design and versatility ensure it can operate effectively in complex maritime environments.',
            'attacks'       => array('sea', 'veh'),
            'defends'       => array('sea', 'veh'),
            'attack'        => 85,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 60
        ),
        'rah66_commanches' => array(
            'price'         => '1260',
            'networth'      => 11,
            'normalname'    => 'Eurocopter Tiger',
            'description'   => 'The Eurocopter Tiger is a cutting-edge attack helicopter renowned for its lethal air-to-air and air-to-ground capabilities. It employs advanced missile systems and precision avionics to engage enemy aircraft effectively. Its agility and sophisticated targeting systems make it a key player in modern rotary-wing operations.',
            'attacks'       => array('air'),
            'defends'       => array('air'),
            'attack'        => 110,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 60
        ),
        'b2_bomber' => array(
            'price'         => '2700',
            'networth'      => 11,
            'normalname'    => 'B-21 Raider',
            'description'   => 'The B-21 Raider is a next-generation stealth bomber designed to penetrate sophisticated enemy air defenses. It carries precision-guided munitions capable of demolishing fortified enemy structures with overwhelming firepower. Its advanced stealth technology and long-range capabilities ensure it remains undetected in high-threat environments.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 205,
            'type'          => 'air',
            'sectype'       => 'bk',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 130
        ),
        'unit_1' => array(
            'price'         => '1125',
            'networth'      => 11,
            'normalname'    => 'F/A-18E Super Hornet',
            'description'   => 'The F/A-18E Super Hornet is a versatile carrier-based fighter that dominates maritime operations with its superior agility and firepower. It is built to perform both air superiority and strike missions with exceptional precision. Its state-of-the-art avionics and rugged design ensure that it remains effective even in the most hostile environments.',
            'attacks'       => array('sea'),
            'defends'       => array('sea'),
            'attack'        => 100,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 56
        ),

        /// VEHICLE UNITS ///
        'sam' => array(
            'price'         => '1253',
            'networth'      => 11,
            'normalname'    => 'M1A2 Abrams',
            'description'   => 'The M1A2 Abrams is a state-of-the-art main battle tank with advanced composite armor and precision firepower. It is engineered to withstand and deliver sustained fire in high-intensity conflicts. Its integrated targeting and communication systems make it one of the most formidable armored vehicles in modern warfare.',
            'attacks'       => array('bld', 'inf'),
            'defends'       => array('bld', 'inf'),
            'attack'        => 85,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 60
        ),
        'abraham' => array(
            'price'         => '953',
            'networth'      => 11,
            'normalname'    => 'LAV-25',
            'description'   => 'The LAV-25 is an amphibious light armored vehicle used for rapid deployment and versatile strike operations. It is designed to operate in a variety of terrains, including urban and maritime environments. Its speed, maneuverability, and modular weapon systems make it an essential asset in modern battlefield operations.',
            'attacks'       => array('bld', 'sea'),
            'defends'       => array('bld', 'sea'),
            'attack'        => 69,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 45
        ),
        'm270_rocket' => array(
            'price'         => '800',
            'networth'      => 11,
            'normalname'    => 'M270 MLRS',
            'description'   => 'The M270 MLRS is a modern multiple launch rocket system capable of delivering rapid, long-range barrages. It is engineered to achieve high accuracy in striking enemy armored vehicles. Its ability to saturate target areas with precision rockets makes it indispensable for artillery support.',
            'attacks'       => array('veh'),
            'defends'       => array('veh'),
            'attack'        => 70,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 35
        ),
        'humvee' => array(
            'price'         => '945',
            'networth'      => 11,
            'normalname'    => 'JLTV',
            'description'   => 'The Joint Light Tactical Vehicle (JLTV) is a modern replacement for the Humvee, offering superior mobility, protection, and off-road capability. It is designed to rapidly counter enemy armored threats in a wide range of combat scenarios. Its robust construction and advanced systems ensure optimal performance under fire.',
            'attacks'       => array('inf', 'air'),
            'defends'       => array('inf', 'air'),
            'attack'        => 65,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 53
        ),
        'm70mlrs' => array(
            'price'         => '1073',
            'networth'      => 11,
            'normalname'    => 'NASAMS',
            'description'   => 'NASAMS is an advanced surface-to-air missile system engineered for rapid interception of hostile aircraft. It integrates cutting-edge sensor technology with precision-guided missiles to provide robust air defense. This system is critical for protecting high-value assets against emerging aerial threats.',
            'attacks'       => array('air', 'sea'),
            'defends'       => array('air', 'sea'),
            'attack'        => 80,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 49
        ),
        'artillery' => array(
            'price'         => '2325',
            'networth'      => 11,
            'normalname'    => 'M109A6 Paladin',
            'description'   => 'The M109A6 Paladin is a self-propelled howitzer delivering sustained, high-caliber firepower. It is designed to breach enemy fortifications and provide decisive artillery support. Its advanced targeting and fire control systems ensure precise, reliable performance in all combat conditions.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 180,
            'type'          => 'veh',
            'sectype'       => 'bk',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 110
        ),
        'unit_3' => array(
            'price'         => '885',
            'networth'      => 11,
            'normalname'    => 'CV90',
            'description'   => 'The CV90 is a modern infantry fighting vehicle celebrated for its speed and lethal firepower. It is designed for rapid deployment and effective engagement of enemy infantry. Its advanced armor and integrated weapon systems make it a versatile platform in modern mechanized warfare.',
            'attacks'       => array('inf'),
            'defends'       => array('inf'),
            'attack'        => 80,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 43
        ),

        /// INFANTRY UNITS ///
        'thief' => array(
            'price'         => '5000',
            'networth'      => 6,
            'normalname'    => 'Thief',
            'description'   => 'The Thief specializes in covert operations, expertly navigating enemy lines to extract valuable intelligence. It employs state-of-the-art stealth technology to remain undetected during critical missions. Its operations are pivotal in disrupting enemy supply chains and strategic planning.',
            'attacks'       => array(),
            'defends'       => array(),
            'attack'        => 0,
            'type'          => 'inf',
            'sectype'       => 'special',
            'attacktype'    => array('thief'),
            'life'          => 60
        ),
        'saboteur' => array(
            'price'         => '6000',
            'networth'      => 6,
            'normalname'    => 'Saboteur',
            'description'   => 'The Saboteur is a covert operative trained in demolition and disruption. It utilizes specialized explosives and stealth tactics to undermine enemy infrastructure. Its operations create chaos behind enemy lines, significantly impairing enemy capabilities.',
            'attacks'       => array(),
            'defends'       => array(),
            'attack'        => 0,
            'type'          => 'inf',
            'sectype'       => 'special',
            'attacktype'    => array('saboteur'),
            'life'          => 75
        ),
        'sniper' => array(
            'price'         => '5000',
            'networth'      => 6,
            'normalname'    => 'Sniper',
            'description'   => 'The Sniper is a long-range precision specialist equipped with a high-powered rifle and advanced optics. It is capable of eliminating key enemy targets with unparalleled accuracy. Trained in stealth and marksmanship, the Sniper provides critical support by neutralizing high-value threats from a distance.',
            'attacks'       => array(),
            'defends'       => array(),
            'attack'        => 140,
            'type'          => 'inf',
            'sectype'       => 'special',
            'attacktype'    => array('sniper'),
            'life'          => 150
        ),
        'spy' => array(
            'price'         => '4000',
            'networth'      => 6,
            'normalname'    => 'Spy',
            'description'   => 'The Spy infiltrates enemy lines to gather critical intelligence on troop movements and strategic assets. It uses advanced surveillance tools and disguises to remain unseen. Its gathered data is essential for planning successful military operations.',
            'attacks'       => array(),
            'defends'       => array(),
            'attack'        => 0,
            'type'          => 'inf',
            'sectype'       => 'special',
            'attacktype'    => array('spy'),
            'life'          => 250
        ),
        'grenade' => array(
            'price'         => '705',
            'networth'      => 11,
            'normalname'    => 'Marine Rifle Squad',
            'description'   => 'The Marine Rifle Squad is a disciplined infantry unit trained for rapid assault. They specialize in breaching enemy defenses and seizing key positions with coordinated firepower. Their rigorous training and teamwork enable them to adapt swiftly to dynamic combat scenarios.',
            'attacks'       => array('bld', 'air'),
            'defends'       => array('bld', 'air'),
            'attack'        => 50,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 31
        ),
        'rifle' => array(
            'price'         => '1100',
            'networth'      => 11,
            'normalname'    => 'Infantry Vanguard',
            'description'   => 'The Infantry Vanguard leads the charge on the frontline with exceptional coordination and resolve. Equipped with advanced weaponry and communication systems, they breach enemy fortifications with precision. Their relentless assault and tactical acumen form the backbone of modern ground offensives.',
            'attacks'       => array('bld', 'sea'),
            'defends'       => array('bld', 'sea'),
            'attack'        => 80,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 35
        ),
        'rocket' => array(
            'price'         => '653',
            'networth'      => 11,
            'normalname'    => 'Anti-Vehicle Assault Team',
            'description'   => 'The Anti-Vehicle Assault Team is a rapid-response unit armed with shoulder-fired anti-tank missiles. They are trained to disable enemy armored vehicles and disrupt mechanized formations. Their quick reaction and specialized equipment provide a critical countermeasure against enemy armor in modern warfare.',
            'attacks'       => array('veh', 'sea'),
            'defends'       => array('veh', 'sea'),
            'attack'        => 45,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 35
        ),
        'flamethrower' => array(
            'price'         => '825',
            'networth'      => 11,
            'normalname'    => 'Urban Assault Team',
            'description'   => 'The Urban Assault Team is specialized for close-quarter combat in built-up environments. They use advanced incendiary weapons to clear enemy fortifications and secure urban terrain. Their aggressive tactics and rapid maneuvering make them highly effective in densely populated combat zones.',
            'attacks'       => array('air'),
            'defends'       => array('air'),
            'attack'        => 73,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 40
        ),
        'paratrooper' => array(
            'price'         => '1320',
            'networth'      => 11,
            'normalname'    => 'Airborne Infantry',
            'description'   => 'Airborne Infantry are elite paratroopers trained for rapid deployment behind enemy lines. They are capable of seizing and holding critical positions with precision and speed. Their rigorous training in airborne operations and close combat makes them indispensable in modern rapid reaction forces.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 100,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 60
        ),
        'armoured' => array(
            'price'         => '923',
            'networth'      => 11,
            'normalname'    => 'Guardian Elite',
            'description'   => 'Guardian Elite is a specialized infantry unit equipped with advanced protective gear for close-quarters combat. They are tasked with defending vital positions and repelling enemy assaults. Their exceptional resilience and tactical training ensure the security of strategic assets on the battlefield.',
            'attacks'       => array('inf'),
            'defends'       => array('inf'),
            'attack'        => 80,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 41
        ),
        'unit_5' => array(
            'price'         => '660',
            'networth'      => 11,
            'normalname'    => 'Cyber Commando',
            'description'   => 'Cyber Commando units combine state-of-the-art cybernetic enhancements with traditional infantry tactics. They specialize in disrupting enemy communications and neutralizing both aerial and armored threats. Their blend of technology and combat expertise makes them uniquely effective in modern network-centric warfare.',
            'attacks'       => array('veh', 'air'),
            'defends'       => array('veh', 'air'),
            'attack'        => 47,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 31
        ),

        /// SEA UNITS ///
        'submarine' => array(
            'price'         => '2295',
            'networth'      => 11,
            'normalname'    => 'Virginia-class Submarine',
            'description'   => 'The Virginia-class Submarine is a modern, nuclear-powered fast-attack vessel designed for stealth and precision. It is equipped with advanced sonar and torpedo systems to execute covert operations in contested coastal waters. Its silent operation and cutting-edge technology make it a linchpin of modern naval warfare.',
            'attacks'       => array('bld', 'veh'),
            'defends'       => array('bld', 'veh'),
            'attack'        => 160,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 100
        ),
        'cruiser' => array(
            'price'         => '2123',
            'networth'      => 11,
            'normalname'    => 'Ticonderoga-class Cruiser',
            'description'   => 'The Ticonderoga-class Cruiser is a versatile warship outfitted with advanced missile systems and radar arrays. It is engineered to counter both aerial and surface threats with its formidable firepower. Its multi-role capability and robust design have made it a mainstay in modern naval fleets.',
            'attacks'       => array('bld', 'air'),
            'defends'       => array('bld', 'air'),
            'attack'        => 155,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 90
        ),
        'frigate' => array(
            'price'         => '1313',
            'networth'      => 11,
            'normalname'    => 'Type 26 Frigate',
            'description'   => 'The Type 26 Frigate is a next-generation stealth warship with advanced sensors and a low radar profile. It is designed to intercept enemy aircraft and protect naval assets with rapid response capabilities. Its state-of-the-art design and efficient systems ensure unparalleled performance in modern maritime security.',
            'attacks'       => array('air', 'inf'),
            'defends'       => array('air', 'inf'),
            'attack'        => 95,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 61
        ),
        'destroyer' => array(
            'price'         => '2150',
            'networth'      => 11,
            'normalname'    => 'Arleigh Burke Destroyer',
            'description'   => 'The Arleigh Burke Destroyer is a modern naval combatant armed with an array of guided missiles and advanced radar systems. It is built for aggressive close-quarters engagements and excels at dominating hostile waters. Its robust firepower and rapid maneuverability make it a critical asset in fleet defense and offensive operations.',
            'attacks'       => array('sea'),
            'defends'       => array('sea'),
            'attack'        => 200,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 100
        ),
        'sparrow' => array(
            'price'         => '1635',
            'networth'      => 11,
            'normalname'    => 'Visby-class Corvette',
            'description'   => 'The Visby-class Corvette is a stealthy and agile warship built with advanced composite materials and sensor technology. It is designed for rapid strike operations against enemy armored vehicles and infantry. Its low radar signature and high maneuverability give it a significant edge in modern naval engagements.',
            'attacks'       => array('veh', 'inf'),
            'defends'       => array('veh', 'inf'),
            'attack'        => 110,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 94
        ),
        'battleship' => array(
            'price'         => '3000',
            'networth'      => 11,
            'normalname'    => 'Oceanic Titan',
            'description'   => 'Oceanic Titan is a colossal heavy warship engineered to deliver overwhelming firepower. It is equipped with a massive missile arsenal designed to demolish enemy fortifications. Its sheer size and advanced weapon systems ensure that it remains a dominant force in any maritime conflict.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 240,
            'type'          => 'sea',
            'sectype'       => 'bk',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 140
        ),
        'unit_7' => array(
            'price'         => '1973',
            'networth'      => 11,
            'normalname'    => 'Stealth Wave',
            'description'   => 'Stealth Wave is a fast attack craft that employs advanced stealth technology and high-speed maneuverability to execute precision strikes on enemy armored vehicles. It is built for rapid hit-and-run operations along hostile coastlines. Its agility and sophisticated systems allow it to disrupt enemy supply lines effectively.',
            'attacks'       => array('veh'),
            'defends'       => array('veh'),
            'attack'        => 160,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 94
        )
    );
}
