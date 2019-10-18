<?php
class Trophies extends DataObject {

    public static function init() {
        // Ultimate Rick
        Hooks::on('set_province_research', 10, function($research_key, $province) {
            $total = 0;
            foreach(Researches::get() as $rs) $total += $rs['maxlevel'];
            $has = 0;
            foreach($province->getResearches() as $rs) $has += $rs['level'];
            if($total == $has) {
                // Trophy::create('rick', $province)
            }
        });

        // Golden Boy
        // Medal Man
        // Hooks::on('set_province_medal')

        // Helmsman
        // Hooks::on('set_province_award') or // Hooks::on('set_clan_award')?

        // Bob the Builder
        // Hooks::on('province_build')

        // Warlord
        // Killer
        // Hooks::on('province_attack') check also // Hooks::on('province_unit_attack')

        // Deatheater
        // Hooks::on('province_kill') vs // Hooks::on('province_die')

        // All kinds of Gains
        // Hooks::on('set_province_ponts')

        // Growing Pains
        // Hooks::on('set_province_nw')
    }

    static $data = array(
        'rick' 		        => array(
            'name'			=>	'Ultimate Rick',
            'stars'			=>	5,
            'description'	=> 'Complete every research during a single round.',
            'type'			=> 'research',
            'icon'			=>	''
        ),
        'golden' 		    => array(
            'name'			=>	'Golden Boy',
            'stars'			=>	5,
            'description'	=> 'Win the Medal of Honor & Medal of Courage during a single round.',
            'type'			=> 'medal',
            'icon'			=>	''
        ),
        'medalman' 		    => array(
            'name'			=>	'Medal Man',
            'stars'			=>	5,
            'description'	=> 'Win three different medals during a single round.',
            'type'			=> 'medal',
            'icon'			=>	''
        ),
        'leader' 		    => array(
            'name'			=>	'Helmsman',
            'stars'			=>	5,
            'description'	=> 'Win a golden award leading a clan',
            'type'			=> 'award',
            'icon'			=>	''
        ),

        'building1' 		=> array(
            'name'			=>	'Bob the Builder',
            'stars'			=>	3,
            'description'	=> 'Build 1000 buildings during a single round.',
            'type'			=> 'building',
            'icon'			=>	''
        ),
        'attacking1'        => array(
            'name'			=>	'Warlord',
            'stars'			=>	3,
            'description'	=> 'Make 100 attacks in clan wars during a single round.',
            'type'			=> 'attacking',
            'icon'			=>	''
        ),
        'killing1' 	        => array(
            'name'			=>	'Deatheater',
            'stars'			=>	3,
            'description'	=> 'Make 10 kills during a single round.',
            'type'			=> 'killing',
            'icon'			=>	''
        ),
        'points1' 		    => array(
            'name'			=>	'All kinds of Gains',
            'stars'			=>	3,
            'description'	=> 'Gain 1000 points during a single round.',
            'type'			=> 'points',
            'icon'			=>	''
        ),
        'networth1' 		=> array(
            'name'			=>	'Growing Pains',
            'stars'			=>	3,
            'description'	=> 'Reach 1 million networth during a single round.',
            'type'			=> 'networth',
            'icon'			=>	''
        ),
        'units1' 		    => array(
            'name'			=>	'Killer',
            'stars'			=>	3,
            'description'	=> 'Kill 2500 units during a round.',
            'type'			=> 'units',
            'icon'			=>	''
        ),

        'building2' 		=> array(
            'name'			=>	'Mr. Constructive',
            'stars'			=>	4,
            'description'	=> 'Build 5000 buildings during a single round.',
            'type'			=> 'building',
            'icon'			=>	''
        ),
        'attacking2' 	    => array(
            'name'			=>	'Veteran',
            'stars'			=>	4,
            'description'	=> 'Make 250 attacks during a single round.',
            'type'			=> 'attacking',
            'icon'			=>	''
        ),
        'killing2' 		    => array(
            'name'			=>	'Tag em bag em',
            'stars'			=>	4,
            'description'	=> 'Make 15 kills during a single round.',
            'type'			=> 'killing',
            'icon'			=>	''
        ),
        'points2' 		    => array(
            'name'			=>	'Relentless',
            'stars'			=>	4,
            'description'	=> 'Gain 2000 points during a single round.',
            'type'			=> 'points',
            'icon'			=>	''
        ),
        'networth2' 		=> array(
            'name'			=>	'Big Spender',
            'stars'			=>	4,
            'description'	=> 'Reach 2 million networth during a single round.',
            'type'			=> 'networth',
            'icon'			=>	''
        ),
        'units2' 		    => array(
            'name'			=>	'Destroyer',
            'stars'			=>	3,
            'description'	=> 'Kill 5000 units during a round.',
            'type'			=> 'units',
            'icon'			=>	''
        ),

        'building3' 		=> array(
            'name'			=>	'Bran the Builder',
            'stars'			=>	5,
            'description'	=> 'Build 8500 buildings during a single round.',
            'type'			=> 'building',
            'icon'			=>	''
        ),
        'attacking3' 	    => array(
            'name'			=>	'Lord Commander',
            'stars'			=>	5,
            'description'	=> 'Make 500 attacks during a single round.',
            'type'			=> 'attacking',
            'icon'			=>	''
        ),
        'killing3' 		    => array(
            'name'			=>	'Envoy of the End',
            'stars'			=>	5,
            'description'	=> 'Make 20 kills during a single round.',
            'type'			=> 'killing',
            'icon'			=>	''
        ),
        'points3' 		    => array(
            'name'			=>	'Iceman',
            'stars'			=>	5,
            'description'	=> 'Gain 3000 points during a single round.',
            'type'			=> 'points',
            'icon'			=>	''
        ),
        'networth3' 		=> array(
            'name'			=>	'MVP',
            'stars'			=>	5,
            'description'	=> 'Reach 3 million networth during a single round.',
            'type'			=> 'points',
            'icon'			=>	''
        ),
        'units3' 		    => array(
            'name'			=>	'Annihilator',
            'stars'			=>	3,
            'description'	=> 'Kill 7500 units during a round.',
            'type'			=> 'units',
            'icon'			=>	''
        ),
    );
}
