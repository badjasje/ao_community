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
            'attacks'       => array(),
            'defends'       => array(),
            'attack'        => 0,
            'type'          => 'air',
            'sectype'       => 'special',
            'attacktype'    => array('spy'),
            'life'          => 200
        ),

        'f22_raptors' => array(
            'price'         => '1120',
            'networth'      => 11,
            'normalname'    => 'Aegis Falcon',
            'description'   => 'The Aegis Falcon is a high-speed air superiority fighter built to control the skies before ground forces move in. It combines advanced radar, stealth shaping and precision weapons to hunt enemy aircraft while still supporting attacks against exposed infantry. Its balanced speed and survivability make it a reliable first choice for commanders who need air dominance.',
            'attacks'       => array('air', 'inf'),
            'defends'       => array('air', 'inf'),
            'attack'        => 76,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 58
        ),

        'jsf' => array(
            'price'         => '1260',
            'networth'      => 11,
            'normalname'    => 'Specter Warden',
            'description'   => 'The Specter Warden is a stealth multirole strike aircraft designed to slip through contested airspace and hit valuable targets. It performs especially well against armored vehicles and hardened structures, making it useful in both offensive pushes and precision raids. Its advanced sensors allow it to identify weak points before delivering decisive strikes.',
            'attacks'       => array('bld', 'veh'),
            'defends'       => array('bld', 'veh'),
            'attack'        => 90,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 62
        ),

        'typhoon' => array(
            'price'         => '1185',
            'networth'      => 11,
            'normalname'    => 'Stormlance Interceptor',
            'description'   => 'The Stormlance Interceptor is a fast-response fighter built for aggressive patrols and rapid counterattacks. It is effective against hostile aircraft and lighter vehicle formations, giving it strong value in mixed battles. Its agility and dependable weapons systems make it a flexible defensive and offensive air unit.',
            'attacks'       => array('air', 'veh'),
            'defends'       => array('air', 'veh'),
            'attack'        => 82,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 60
        ),

        'seahawk' => array(
            'price'         => '1100',
            'networth'      => 11,
            'normalname'    => 'Maritime Talon',
            'description'   => 'The Maritime Talon is a naval attack helicopter optimized for coastal warfare and fleet support. It excels at striking ships and exposed armored columns, especially when deployed alongside sea units. Its sensor package and low-altitude maneuverability allow it to remain effective in chaotic battlefield conditions.',
            'attacks'       => array('sea', 'veh'),
            'defends'       => array('sea', 'veh'),
            'attack'        => 78,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 56
        ),

        'rah66_commanches' => array(
            'price'         => '1150',
            'networth'      => 11,
            'normalname'    => 'Dragonfly Gunship',
            'description'   => 'The Dragonfly Gunship is a nimble attack helicopter designed for rapid battlefield response. It can harass infantry formations and challenge enemy aircraft that move too close to the front line. While not as durable as heavier aircraft, its speed and focused firepower make it dangerous in the right engagement.',
            'attacks'       => array('air', 'inf'),
            'defends'       => array('air', 'inf'),
            'attack'        => 80,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 52
        ),

        'b2_bomber' => array(
            'price'         => '2800',
            'networth'      => 11,
            'normalname'    => 'Nightfall Bomber',
            'description'   => 'The Nightfall Bomber is a heavy stealth bomber built for deep strategic strikes against enemy infrastructure. It carries a devastating payload and specializes in destroying fortified buildings that would slow down a conventional assault. Its high cost is balanced by its exceptional damage output and strong durability.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 220,
            'type'          => 'air',
            'sectype'       => 'bk',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 125
        ),

        'unit_1' => array(
            'price'         => '1340',
            'networth'      => 11,
            'normalname'    => 'Thunderbolt Strike Wing',
            'description'   => 'The Thunderbolt Strike Wing is a rugged close-air-support platform built to punish enemy armor and infantry. It lacks the stealth of advanced fighters, but compensates with heavy weapons, battlefield endurance and excellent ground-attack performance. It is a strong choice when commanders need reliable pressure against land forces.',
            'attacks'       => array('veh', 'inf'),
            'defends'       => array('veh', 'inf'),
            'attack'        => 95,
            'type'          => 'air',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 65
        ),


        /// VEHICLE UNITS ///

        'sam' => array(
            'price'         => '1200',
            'networth'      => 11,
            'normalname'    => 'Skyguard SAM',
            'description'   => 'The Skyguard SAM is a mobile surface-to-air missile platform designed to protect ground forces from hostile aircraft. It delivers strong anti-air damage while remaining durable enough to survive on the front line. Its focused role makes it one of the most important counters against air-heavy armies.',
            'attacks'       => array('air'),
            'defends'       => array('air'),
            'attack'        => 92,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 60
        ),

        'abraham' => array(
            'price'         => '1300',
            'networth'      => 11,
            'normalname'    => 'Ironback MBT',
            'description'   => 'The Ironback MBT is a modern main battle tank designed to lead armored advances through hostile territory. Its heavy cannon and reinforced armor make it effective against enemy vehicles and defensive structures. It is expensive, but offers excellent staying power in prolonged ground battles.',
            'attacks'       => array('veh', 'bld'),
            'defends'       => array('veh', 'bld'),
            'attack'        => 88,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 70
        ),

        'm270_rocket' => array(
            'price'         => '1180',
            'networth'      => 11,
            'normalname'    => 'Hailstorm MLRS',
            'description'   => 'The Hailstorm MLRS is a mobile rocket artillery system built to soften enemy positions before the main attack begins. It performs well against buildings and clustered infantry, delivering high area pressure from a safe distance. Its lighter armor means it should be protected by tougher front-line units.',
            'attacks'       => array('bld', 'inf'),
            'defends'       => array('bld', 'inf'),
            'attack'        => 96,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 48
        ),

        'humvee' => array(
            'price'         => '680',
            'networth'      => 11,
            'normalname'    => 'Ranger JLTV',
            'description'   => 'The Ranger JLTV is a fast tactical vehicle used for scouting, patrols and quick strikes. It is affordable and useful against infantry and low-flying threats, but it should avoid extended fights with heavy armor. Its low cost makes it valuable for building flexible armies early in the game.',
            'attacks'       => array('inf', 'air'),
            'defends'       => array('inf', 'air'),
            'attack'        => 50,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 44
        ),

        'm70mlrs' => array(
            'price'         => '1050',
            'networth'      => 11,
            'normalname'    => 'Sentinel Air Defense',
            'description'   => 'The Sentinel Air Defense system combines radar tracking with rapid missile response to defend against both aircraft and incoming naval strikes. It is not the strongest attacker, but its flexible targeting makes it useful in mixed defensive armies. Commanders often deploy it to protect valuable vehicles and artillery.',
            'attacks'       => array('air', 'sea'),
            'defends'       => array('air', 'sea'),
            'attack'        => 74,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 54
        ),

        'artillery' => array(
            'price'         => '2400',
            'networth'      => 11,
            'normalname'    => 'Siegebreaker Howitzer',
            'description'   => 'The Siegebreaker Howitzer is a heavy self-propelled artillery unit designed to crush fortified positions. It delivers punishing damage against buildings, making it ideal for breaking defensive players who rely on infrastructure. Its high price and narrow target focus are offset by excellent destructive power.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 190,
            'type'          => 'veh',
            'sectype'       => 'bk',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 105
        ),

        'unit_3' => array(
            'price'         => '980',
            'networth'      => 11,
            'normalname'    => 'Vanguard IFV',
            'description'   => 'The Vanguard IFV is an infantry fighting vehicle built to escort troops through contested ground. Its autocannon and missile systems allow it to pressure both infantry and lighter armored targets. It offers a balanced mix of price, durability and damage for commanders who want dependable ground support.',
            'attacks'       => array('inf', 'veh'),
            'defends'       => array('inf', 'veh'),
            'attack'        => 76,
            'type'          => 'veh',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 58
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
            'price'         => '720',
            'networth'      => 11,
            'normalname'    => 'Breach Team',
            'description'   => 'The Breach Team is a disciplined assault squad trained to open gaps in enemy defenses. It performs well against infantry and light structures, making it useful during early attacks and urban fighting. Its moderate price gives commanders a dependable offensive infantry option without overcommitting resources.',
            'attacks'       => array('bld', 'inf'),
            'defends'       => array('bld', 'inf'),
            'attack'        => 58,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 36
        ),

        'rifle' => array(
            'price'         => '790',
            'networth'      => 11,
            'normalname'    => 'Line Infantry',
            'description'   => 'Line Infantry are the backbone of any ground army, trained to hold territory and push steadily through enemy positions. They are effective against infantry and buildings, especially when deployed in large numbers. Their simple role, fair cost and reliable performance make them a core unit throughout the game.',
            'attacks'       => array('inf', 'bld'),
            'defends'       => array('inf', 'bld'),
            'attack'        => 64,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 40
        ),

        'rocket' => array(
            'price'         => '760',
            'networth'      => 11,
            'normalname'    => 'Titan AT Team',
            'description'   => 'The Titan AT Team carries portable anti-armor and anti-air weapons for flexible front-line support. It is dangerous against vehicles and aircraft, but remains vulnerable when caught by dedicated infantry counters. Its value comes from answering expensive enemy units at a manageable price.',
            'attacks'       => array('veh', 'air'),
            'defends'       => array('veh', 'air'),
            'attack'        => 62,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 34
        ),

        'flamethrower' => array(
            'price'         => '870',
            'networth'      => 11,
            'normalname'    => 'Urban Fire Team',
            'description'   => 'The Urban Fire Team specializes in clearing fortified streets, bunkers and dense defensive positions. It is strong against buildings and infantry, but it does not offer much value against armor or naval forces. Used correctly, it can turn a slow siege into a rapid breakthrough.',
            'attacks'       => array('bld', 'inf'),
            'defends'       => array('bld', 'inf'),
            'attack'        => 70,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 38
        ),

        'paratrooper' => array(
            'price'         => '1120',
            'networth'      => 11,
            'normalname'    => 'Drop Vanguard',
            'description'   => 'The Drop Vanguard is an elite airborne force trained to strike key locations before the enemy can fully react. It is especially useful against buildings and isolated sea assets, giving it a unique role in surprise attacks. Its higher cost reflects better training, mobility and battlefield discipline.',
            'attacks'       => array('bld', 'sea'),
            'defends'       => array('bld', 'sea'),
            'attack'        => 83,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 48
        ),

        'armoured' => array(
            'price'         => '990',
            'networth'      => 11,
            'normalname'    => 'Bulwark Guard',
            'description'   => 'The Bulwark Guard is a heavily protected infantry unit built to absorb punishment while advancing under fire. It performs well against infantry and vehicles, making it a sturdy anchor for ground formations. Its strength lies in survivability and consistent pressure rather than raw speed.',
            'attacks'       => array('inf', 'veh'),
            'defends'       => array('inf', 'veh'),
            'attack'        => 78,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 52
        ),

        'unit_5' => array(
            'price'         => '830',
            'networth'      => 11,
            'normalname'    => 'Drone Hunter Cell',
            'description'   => 'The Drone Hunter Cell is a compact specialist squad equipped with portable sensors and smart launchers. It is designed to threaten aircraft and armored vehicles without the cost of heavy machinery. Although fragile, it gives infantry armies a valuable answer to advanced technology.',
            'attacks'       => array('air', 'veh'),
            'defends'       => array('air', 'veh'),
            'attack'        => 68,
            'type'          => 'inf',
            'sectype'       => 'normal',
            'attacktype'    => array('ground', 'regular'),
            'life'          => 33
        ),


        /// SEA UNITS ///

        'submarine' => array(
            'price'         => '2250',
            'networth'      => 11,
            'normalname'    => 'Wraith Submarine',
            'description'   => 'The Wraith Submarine is a silent attack vessel built to strike from below the surface before the enemy can respond. It is highly effective against ships and vehicle supply routes near the coast. Its stealth and strong damage make it dangerous, but its price requires careful use.',
            'attacks'       => array('sea', 'veh'),
            'defends'       => array('sea', 'veh'),
            'attack'        => 155,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 100
        ),

        'cruiser' => array(
            'price'         => '2100',
            'networth'      => 11,
            'normalname'    => 'Aegis Cruiser',
            'description'   => 'The Aegis Cruiser is a fleet defense warship designed to protect naval groups from aircraft and enemy ships. Its advanced radar and missile systems give it strong control over sea lanes. It is costly, but it brings excellent defensive value to any maritime strategy.',
            'attacks'       => array('air', 'sea'),
            'defends'       => array('air', 'sea'),
            'attack'        => 145,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 92
        ),

        'frigate' => array(
            'price'         => '1350',
            'networth'      => 11,
            'normalname'    => 'Guardian Frigate',
            'description'   => 'The Guardian Frigate is a versatile escort ship built for patrols, convoy protection and coastal support. It is effective against aircraft and infantry, making it useful when supporting landings or defending against mixed attacks. Its moderate cost gives naval players an accessible all-round option.',
            'attacks'       => array('air', 'inf'),
            'defends'       => array('air', 'inf'),
            'attack'        => 98,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 70
        ),

        'destroyer' => array(
            'price'         => '2300',
            'networth'      => 11,
            'normalname'    => 'Tempest Destroyer',
            'description'   => 'The Tempest Destroyer is an aggressive surface combatant made for ship-to-ship battles and coastal bombardment. It can punish enemy fleets while also damaging important structures near the shoreline. Its strong attack and solid durability make it one of the most reliable high-tier naval units.',
            'attacks'       => array('sea', 'bld'),
            'defends'       => array('sea', 'bld'),
            'attack'        => 170,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 105
        ),

        'sparrow' => array(
            'price'         => '1500',
            'networth'      => 11,
            'normalname'    => 'Swift Corvette',
            'description'   => 'The Swift Corvette is a fast and agile warship used for raiding, scouting and disrupting lighter enemies. It performs well against ships and infantry, especially in quick coastal operations. While it lacks the staying power of heavier vessels, its speed and cost make it tactically useful.',
            'attacks'       => array('sea', 'inf'),
            'defends'       => array('sea', 'inf'),
            'attack'        => 105,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 76
        ),

        'battleship' => array(
            'price'         => '3100',
            'networth'      => 11,
            'normalname'    => 'Leviathan Battleship',
            'description'   => 'The Leviathan Battleship is a massive bombardment platform built to erase enemy structures from extreme range. It is slow and expensive, but its firepower against buildings is unmatched among naval forces. When protected by escorts, it can decide long wars through raw destructive pressure.',
            'attacks'       => array('bld'),
            'defends'       => array('bld'),
            'attack'        => 250,
            'type'          => 'sea',
            'sectype'       => 'bk',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 145
        ),

        'unit_7' => array(
            'price'         => '1850',
            'networth'      => 11,
            'normalname'    => 'Riptide Missile Craft',
            'description'   => 'The Riptide Missile Craft is a compact strike vessel designed for fast missile attacks against ships and coastal vehicle formations. It is cheaper than major warships but still carries enough firepower to threaten valuable targets. Its best use is in mobile fleets that rely on speed, timing and concentrated damage.',
            'attacks'       => array('sea', 'veh'),
            'defends'       => array('sea', 'veh'),
            'attack'        => 135,
            'type'          => 'sea',
            'sectype'       => 'normal',
            'attacktype'    => array('air_sea', 'regular'),
            'life'          => 82
        )
    );
}