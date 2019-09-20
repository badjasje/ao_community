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

        // Default income
        'income_turns' => 1,
        'income_money' => 15000,
        'income_morale' => 5,

        // devfunds
        'devfunds_money' => 250000,
        'devfunds_turns' => 50,

        // Nw costs multipliers for types without nw
        'nw_land' => 0.85,
        'nw_sat' => 0.04,
        'nw_research' => 950,

        // start bonus things
        'startbonus_defensive_building_life_multi' => 1.25,
        'startbonus_defensive_unit_life_multi' => 1.2,
        'startbonus_finance_income_multi' => 1.1,
        'startbonus_finance_deposit_multi' => 1.5,
        'startbonus_money_research_multi' => 1.1,
        'startbonus_shipping_research_multi' => 10,
        'startbonus_defensive_research_time' => 0.9,

        // Bank
        'bank_max_deposits' => 10,
        'bank_max_deposit' => 250000,
        'bank_min_deposit' => 5000,

        // Research
        'powerplant_efficiency_life_multi' => 1.5,
        'powerplant_efficiency_power_multi' => 1.5,
        'satellite_construction_1_endlife' => 11,
        'satellite_construction_2_endlife' => 16,
        'satellite_construction_3_price_multi' => .8,
        'bank_management_1_interest' => .5,
        'bank_management_2_interest' => .5,
        'bank_management_3_interest' => .75,
        'bank_management_1_deposit' => 350000,
        'bank_management_2_deposit' => 450000,
        'bank_management_3_deposit' => 500000,
        'bank_management_2_time' => (60*60*24),
        'bank_management_2_withdraw' => 0.5,
        'bank_management_3_withdraw' => 0.75,

        // Explore & sell
        'max_explore_land' => 20000,
        'max_sell_land' => 20000,
        'money_per_land' => 75,

        // Building
        'land_per_building' => 20,
        'defensive_buildings' => array('torpedolauncher', 'samsite', 'missileturret', 'machinegunturret'),
        'all_types' => array('sea', 'air', 'veh', 'inf', 'bld'),
        'unit_types' => array('sea', 'air', 'veh', 'inf'),
        'special_units' => array('spyplane', 'thief', 'spy','sniper','saboteur'),
        'demolish_price_multi' => 0.15,

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
        'points_kill_outgoing' => 25,
        'points_kill_incoming' => 25,
        'points_kill_mutual' => 50,
        'maintarget_target_multi' => 1.5,
        'maintarget_power_multi' => 1.2,
        'maintarget_notarget_multi' => 0.5,
        'saboteur_morale_cost' => 30,
        'sniper_morale_cost' => 10,
        'thief_morale_cost' => 5,
        'spy_morale_cost' => 0,
        'missile_morale_tgt_below' => 40,
        'missile_morale_tgt_above' => 35,
        'attack_morale_tgt_below' => 25,
        'attack_morale_tgt_above' => 20,

        // Turn costs
        'turns_missile' => 3,
        'turns_attack' => 3,
        'turns_thief' => 2,
        'turns_spy' => 1,
        'turns_research' => 25,
        'turns_queue_research' => 30,

        // Morale costs
        'morale_missile_tgt_below' => 40,
        'morale_missile_tgt_above' => 35,
        'morale_attack_tgt_below' => 25,
        'morale_attack_tgt_above' => 20,
        'morale_thief' => 5,
        'morale_saboteur' => 30,
        'morale_spy' => 0,

        // Attack range multiplier
        'attack_range_mult' => 1.4,
        'average_declare_nw_allowed' => 1.6,

        // Clan stuff
        'clan_trustee_num' => 4,
    );

}
