<?php
class Settings extends DataObject {

    /**
     * Let's store game constants here.
     * Balancing is very important
     * So we can easily change the "nw range", or the morale costs, etc.
     */
    static $data = array(

        // Not game related, haha
        'admin_ids' => array(1,2768),
        'admin_ips' => array('87.209.229.255','213.125.228.34','217.121.5.245','213.125.228.34','83.80.24.164'),

        // When you die or start of new round
        'start_money' => 450000,
        'start_powerplant' => 50,
        'start_turns' => 200,
        'start_land' => 2000,
        'start_morale' => 100,
        'start_morale_pool' => 100,
        'nuke_protection_length' => (48 * 3600),

        // devfunds
        'devfunds_money' => 250000,
        'devfunds_turns' => 50,

        // Nw costs multipliers for types without nw
        'nw_land' => 0.85,
        'nw_sat' => 0.04,
        'nw_research' => 950,

        // Building
        'land_per_building' => 20,
        'defensive_buildings' => array('torpedolauncher', 'samsite', 'missileturret', 'machinegunturret'),
        'all_types' => array('sea', 'air', 'veh', 'inf', 'bld'),
        'unit_types' => array('sea', 'air', 'veh', 'inf'),
        'special_units' => array('spyplane', 'thief', 'spy','sniper','saboteur'),

        // research constants
        'powerplant_efficiency_life_multi' => 1.5,
        'satellite_construction_1_endlife' => 11,
        'satellite_construction_2_endlife' => 16,

        // start bonus things
        'startboni' => array('offensive','defensive','finance','shipping'),
        'startbonus_defensive_building_life_multi' => 1.25,
        'startbonus_defensive_unit_life_multi' => 1.2,

        // attack constants
        'points_kill_outgoing' => 25,
        'points_kill_incoming' => 25,
        'points_kill_mutual' => 50,

        // turn costs
        'turns_missile' => 3,
        'turns_attack' => 3,
        'turns_thief' => 2,
        'turns_spy' => 1,

        // morale costs
        'morale_missile_tgt_below' => 40,
        'morale_missile_tgt_above' => 35,
        'morale_attack_tgt_below' => 25,
        'morale_attack_tgt_above' => 20,
        'morale_thief' => 5,
        'morale_saboteur' => 30,
        'morale_spy' => 0,

        // default income
        'income_turns' => 1,
        'income_money' => 15000,
        'income_morale' => 5,

        // attack range multiplier
        'attack_range_mult' => 1.4,
        'average_declare_nw_allowed' => 1.6,
    );

}
