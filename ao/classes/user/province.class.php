<?php

class Province extends User {
    //static $table = 'provinces';
    public static $cache = 'provinces';

    // @todo: add building, missile, sat, research, unit-fields from data objects
    public $fields = array(
        // Generic
        'id','status','starting_bonus','new_events','new_messages','new_global_events','user_lock','morale_lock',
        // Resources
        'money','turns','networth','land','power','morale','morale_pool','sat_morale',
        'networth_cache','land_cache','cached_land','cached_nw',

        // Stats
        'sold_land_today','land_sold_today','explored_today','special_sold_today',
        'builtland','units_sold','nuke_protection_timestamp','user_country',
        'sat_nw','research_nw','building_nw','unit_nw','land_nw','missile_nw','morale_lost',
        'highest_networth','highest_land','buildings_built',
        'money_lost_thieving','attempts_received','units_built_turns',
        'attacks_received','money_lost_combat','land_lost_combat','nw_damage_lost','units_lost','buildings_lost','attacks_lost',
        'attacks_made','money_gained_combat','land_gained_combat','in_war_attacks','units_killed','nw_damage_attacks',
        'buildings_killed','succesful_attacks','attacks_made_current','last_attacked','kills_made','times_killed',
        'missiles_received','missiles_hit_rec','nw_damage_missiles_rec','money_gained_thieving','thieving_attempts',

        // Market
        'units_ordered',

        // Buildings
        'silo','command_centre','shipyard','airfield','warfactory','baracks','powerplant','advancedpowerplant','torpedolauncher',
        'samsite','missileturret','machinegunturret','antimissile',

        // Missiles
        'nuke_owned','nuke_ordered','chemical_owned','chemical_ordered','bio_owned','bio_ordered','moab_owned','moab_ordered',
        'tomahawk_ordered','tomahawk_owned',

        // Sats
        'sat_in_progress','sat_owned','stealth_sat_status','level_satellite_construction','sat_endlife',
        // Bank
        'total_deposits',

        // Research
        'research_in_progress','queued_research','level_raid_protection',
        'level_money_production','level_missile_accuracy','level_sattelite_construction','level_shipping_time','level_market_discount',
        'level_thieving_effectiveness','level_engineering_effectiveness','level_bank_management','level_powerplant_efficiency',

        // Units: air
        'spyplane_owned','spyplane_ordered','dragon_owned','dragon_ordered','f22_raptors_owned','f22_raptors_ordered',
        'rah66_commanches_owned','rah66_commanches_ordered','b2_bomber_owned','b2_bomber_ordered','jsf_owned','jsf_ordered',
        'typhoon_owned','typhoon_ordered','seahawk_owned','seahawk_ordered',

        // Units: veh
        'apc_owned','apc_ordered','humvee_owned','humvee_ordered','sam_owned','sam_ordered','abraham_owned','abraham_ordered',
        'artillery_owned','artillery_ordered','m70mlrs_owned','m70mlrs_ordered','m270_rocket_owned','m270_rocket_ordered',

        // Units: inf
        'thief_owned','thief_ordered','saboteur_owned','saboteur_ordered','sniper_owned','sniper_ordered','spy_owned','spy_ordered',
        'paratrooper_owned','paratrooper_ordered','grenade_owned','grenade_ordered', 'navy_owned','navy_ordered','rifle_owned',
        'rifle_ordered','rocket_owned','rocket_ordered','armoured_owned','armoured_ordered','flamethrower_owned','flamethrower_ordered',

        // Units: sea
        'battleship_owned','battleship_ordered','frigate_owned','frigate_ordered','carrier_owned','carrier_ordered',
        'submarine_owned','submarine_ordered','cruiser_owned','cruiser_ordered','destroyer_owned','destroyer_ordered',
        'sparrow_owned','sparrow_ordered',

        // Awards
        'points_position','networth_position','user_clan_points','current_clan_points',
        'moe_position','moe_prev','moe_next','mog_position','mog_prev','mog_next','moh_position','moh_prev','moh_next',
        'moc_position','moc_prev','moc_next','modev_position','modev_damage','mot_position','mot_prev','mot_next',
        'mod_position','mod_prev','mod_next',

        // Clan
        'clan_id_user','clan_join_stamp','new_clan_timestamp','total_aid_sent','number_of_aids','aid_received','aid_sent_today',
        'clan_message','clan_create_counter','spied_current_clan',
    );

    public function getAvatar() {
        return small_avatar($this->id,'menuAvatar');
    }

    public function getMoney($format=false) {
        $n = intval($this->get('money'));
        return ($format ? Format::money($n) : $n);
    }

    public function getNetworth($format=false) {
        $n = intval($this->get('networth'));
        return ($format ? Format::networth($n) : $n);
    }

    public function getTurns($format=false) {
        $n = intval($this->get('turns'));
        return ($format ? Format::turns($n) : $n);
    }

    public function getMorale($format=false) {
        $n = intval($this->get('morale'));
        return ($format ? Format::morale($n) : $n);
    }

    public function getMoralePool($format=false) {
        $n = intval($this->get('morale_pool'));
        return ($format ? Format::morale($n) : $n);
    }

    public function getSatMorale($format=false) {
        $n = intval($this->get('sat_morale'));
        return ($format ? Format::morale($n) : $n);
    }

    public function getLand($format=false) {
        $n = intval($this->get('land'));
        return ($format ? Format::land($n) : $n);
    }

    public function getFreeLand($format=false) {
        $n = intval($this->get('land'));
        $b = intval($this->get('land'));
        return ($format ? Format::land($n-$b) : $n-$b);
    }

    public function getPower($format=false) {
        $n = intval($this->get('power'));
        return ($format ? Format::power($n) : $n);
    }

    /**
     *
     */
    public function getCurrentResearch() {
        if(!empty($this->get('research_in_progress'))) {
            $args = array('posts_per_page' => 1, 'author' => $this->id, 'post_type' => 'research');
            $researches = get_posts($args);
            if(is_array($researches) && count($researches) && is_object($researches[0])) {
                $props = array_merge(
                    Researches::get($researches[0]->post_content),
                    array('end_time' => $researches[0]->post_title)
                );
                return PhpObject::make($props); // @todo: should be a ResearchObject
            }
        }
        return false;
    }
    public function getResearchTimeLeft() {
        if($r = $this->getCurrentResearch()) return Format::time_diff($r->get('end_time'));
        return false;
    }

    /**
     * Get all information of one or all buildings of this province
     */
    public function getBuildings($key=null) {
        $buildings = Buildings::get();
        foreach($buildings as $key => $building) {
            $buildings[$key]['num'] = (!!$this->get($key) ? intval($this->get($key)) : 0);
        }

        $PPE_level = intval($this->get('level_powerplant_efficiency'));
        if ($PPE_level >= 1) {
            $buildings['powerplant']['description'] = 'Produces ' . (3000*1.5) .' power';
            $buildings['advancedpowerplant']['description'] = 'Produces ' . (15000*1.5) .' power';
        }

        $AMS = intval($this->get('antimissile'));
        if($AMS > 0) {
            $def_land = intval($province->get('builtland'));
            $shootdown_chance = (($AMS*100)/$def_land)*100;
            if ($shootdown_chance >= 75) $shootdown_chance = 75;
            $buildings['antimissile']['shootdown_chance'] = $shootdown_chance;
            $buildings['antimissile']['description'] = 'Each Anti-Missile System protects 100m2 of your built land.
                Chance to shoot down missiles is currently '. $shootdown_chance .'%.
                Every Anti-Missile System has a 25% chance to shoot down tomahawk missiles.';
        }

        return ($key != null && $buildings[$key] ? $buildings[$key] : $buildings);
    }
    // Calculate total buildings number
    public function getBuildingsNum() {
        $num = 0;
        foreach(array_keys(Buildings::get()) as $key) {
            $num += (!!$this->get($key) ? intval($this->get($key)) : 0);
        }
        //$this->update('buildings_built', $num); // overhead to always update this
        return 0;
    }

    /**
     * Get all information of one or all of one type, or all units of this province
     */
    public function getUnits($key=null,$type=null) {

    }
    public function getUnitsNum($type=null) {

    }

    public function getSatellites() {

    }

    public function getMissiles() {

    }

    public function getResearches() {

    }

    /*
    getClan(),
    invite(),
    kick(),
    isFellowClanMember(),

    getMedals(),
    getTrophies(),

    calculateNw(),
    calculatePower(),
    calculateFreeLand()

    kill(),
    reset(),
    isDead(),
    isProtected(),
    inRange()

    attack(),
    spy()
    */
}