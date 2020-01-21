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
        'max_request_errors' => 10,

        // online
        'online_status_time' => 7200,

        // When you die or start of new round
        'start_money' => 450000,
        'start_powerplant' => 50,
        'start_turns' => 200,
        'start_land' => 2000,
        'start_morale' => 100,
        'start_morale_pool' => 100,
        'nuke_protection_length' => (48 * 3600),
        'nuke_protection_removal' => (24 * 3600),

        // Default income
        'income_turns' => 1,
        'income_money' => 15000,
        'income_morale' => 5,

        // devfunds
        'devfunds_money' => 250000,
        'devfunds_turns' => 50,

        // Nw costs multipliers for types without nw
        'nw_land' => 0.85,
        'nw_research' => 950,
        // Satellite has their personal settings, just like buildings and units

        // Bank
        'bank_max_deposits' => 10,
        'bank_max_deposit' => 250000,
        'bank_min_deposit' => 5000,

        // Explore & sell
        'max_explore_land' => 20000,
        'max_sell_land' => 20000,
        'money_per_land' => 75,

        // Building
        'land_per_building' => 20,
        'defensive_buildings' => array('torpedolauncher', 'samsite', 'missileturret', 'machinegunturret'),
        //'all_types' => array('sea', 'air', 'veh', 'inf', 'bld'), // unused?
        'unit_types' => array(
            'air' => 'Air units',
            'sea' => 'Sea units',
            'veh' => 'Vehicles',
            'inf' => 'Infantry'
        ),
        'special_units' => array('spyplane', 'thief', 'spy','sniper','saboteur'),
        'demolish_price_multi' => 0.15,

        // Units
        'units_per_turn' => array('air' => 10, 'sea' => 5, 'veh' => 10, 'inf' => 20),

        // Aid
        'max_aid' => 250000,
        'max_aid_times' => 3,

        // Attack constants
        'attack_maintargets' => array(
            'power' => 'Power plants',
		    'silo' => 'Missile silos',
		    'command' => 'Command centres',
		    'shipyard' => 'Shipyards',
		    'airfield' => 'Airfields',
		    'barracks' => 'Barracks',
		    'warfactory' => 'Warfactories',
		    'defense' => 'Defense buildings',
		    'ams' => 'Anti-Missile System',
        ),
        'points_cap' => 25, // POINTS_CAP
        'points_kill_outgoing' => 25,
        'points_kill_incoming' => 25,
        'points_kill_mutual' => 50,
        'maintarget_target_multi' => 0.8,
        'maintarget_notarget_multi' => 1.2,
        'saboteur_morale_cost' => 30,
        'sniper_morale_cost' => 10,
        'thief_morale_cost' => 5,
        'spy_morale_cost' => 0,
            // Morale costs
            'morale_missile_tgt_below' => 40,
            'morale_missile_tgt_above' => 35,
            'morale_attack_tgt_below' => 25,
            'morale_attack_tgt_above' => 20,
            'morale_thief' => 5,
            'morale_saboteur' => 30,
            'morale_spy' => 0,
        'missile_morale_tgt_below' => 40,
        'missile_morale_tgt_above' => 35,
        'attack_morale_tgt_below' => 25,
        'attack_morale_tgt_above' => 20,
        'damage_reduction_unit' => 25, // DAMAGE_REDUCTION_FACTOR_UNIT
        'damage_reduction_building' => 20, // DAMAGE_REDUCTION_FACTOR_BLD
        'spy_effectiveness' => array( // per spy enhance level
            0 => array(20,30,36,72),
            1 => array(10,20,12,36),
            2 => array(6,12,6,12),
            3 => array(1,3,1,3),
        ),

        // Turn costs
        'turns_missile' => 3,
        'turns_attack' => 3,
        'turns_thief' => 2,
        'turns_spy' => 1,
        'turns_research' => 2, // duration times 2
        'turns_queue_research' => 2.5, // duration times 3

        // Market, Satellite & Missile
        'sat_turn_cost' => 25,
        'sat_demo_price' => .2,
        'sat_delivery_time' => (12 * 3600),
        'stealthsat_turn_cost' => 3,
        'stealthsat_time' => (3600 * 3.5),
        'stealthsat_morale_cost' => 100,
        'order_cancel_cashback' => 0.75,
        'missile_sell_multi' => 0.75,
        'unit_order_multi' => 2.2,
        'unit_sell_multi' => 0.8, // of unit buy price
        'unit_trade_multi' => 1, // of unit buy price
        'max_special_sell' => 50,
        'max_special_order' => 500,
        'max_market_delay' => 360,

        // Attack range multiplier
        'attack_range_mult' => 1.4,
        'average_declare_nw_allowed' => 1.6,

        // Clan stuff
        'clan_trustee_num' => 5,
        'clan_member_num' => 7,
        'clan_kick_penalty' => 0.25,
        'clan_join_mutual_delay' =>  (24 * 3600),

        // War
        'resume_after_hours' => 12,
        'peace_after_time' => (24 * 3600),
        'war_type_multi' => array(
            'mutual' => 1,
            'outgoing' => 1,
            'incoming' => .5,
            'none' => 0,
        ),
    );

}
