<?php

class Province extends DbObject {
    //static $table = 'provinces';
    public static $cache = 'provinces';

    // @todo: add building, missile, sat, research, unit-fields from data objects instead of huge array
    public $fields = array(
        // Generic
        'id','display_name','avatar_user','status','starting_bonus','new_events','new_messages','new_global_events',
        'user_lock','morale_lock','telegram_key','last_online',

        // Resources
        'money','turns','networth','land','power','morale','morale_pool','sat_morale',
        'networth_cache','land_cache','cached_land','cached_nw',

        // Stats
        'sold_land_today','land_sold_today','explored_today','special_sold_today','turn_spread','treasures_today',
        'builtland','units_sold','nuke_protection_timestamp','user_country',
        'sat_nw','research_nw','building_nw','unit_nw','land_nw','missile_nw','morale_lost',
        'highest_networth','highest_land','buildings_built',
        'money_lost_thieving','attempts_received','units_built_turns',
        'attacks_received','money_lost_combat','land_lost_combat','nw_damage_lost','units_lost','buildings_lost','attacks_lost',
        'attacks_made','money_gained_combat','land_gained_combat','in_war_attacks','units_killed','nw_damage_attacks',
        'buildings_killed','succesful_attacks','attacks_made_current','last_attacked','kills_made','times_killed',
        'missiles_received','missiles_hit','missiles_hit_rec','missiles_launched','nw_damage_missiles','nw_damage_missiles_war','nw_damage_missiles_rec',
        'money_gained_thieving','succesful_attempts','thieving_attempts','succesful_attempts_rec','turns_lost', 'attacks_rec_current',

        // Market
        'units_ordered',

        // Buildings
        'silo','command_centre','shipyard','airfield','warfactory','baracks','powerplant','advancedpowerplant','torpedolauncher',
        'samsite','missileturret','machinegunturret','antimissile',

        // Missiles
        'nuke_owned','nuke_ordered','chemical_owned','chemical_ordered','bio_owned','bio_ordered','moab_owned','moab_ordered',
        'tomahawk_ordered','tomahawk_owned',

        // Sats
        'sat_in_progress','sat_owned','stealth_sat_status','level_satellite_construction','sat_endlife','stealth_sat_time',
        // Bank
        'total_deposits',

        // Research
        'research_in_progress','queued_research',
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
        'mod_position','mod_prev','mod_next','modes_position','modes_prev','modes_next',

        // Clan
        'clan_id_user','clan_join_stamp','new_clan_timestamp','total_aid_sent','number_of_aids','aid_received','aid_sent_today',
        'clan_message','clan_create_counter','spied_current_clan',
    );

    public function __construct($props=null) {
        if(is_numeric($props)) {
            if(static::$cache && isset(static::$list[static::$cache][$props])) {
                return parent::__construct(static::$list[static::$cache][$props]);
            }
            $props = User::make()->getUserDataFromWordpress($props);
        }
        if(is_array($props)) {
            foreach($props as $k => $v) {
                if(count($this->fields) && !in_array($k, $this->fields)) { unset($props[$k]); continue; }
            }
        }
        parent::__construct($props);
    }

    // As long as we are using WP, we re-use this function
    public function update($key, $value) {
        if(in_array($key,$this->fields)) update_user_meta($this->id, $key, $value); //@wp
        return parent::update($key, $value);
    }

    /**
     * Helper function
     */
    public function isCurrentUser() {
        $user = CurrentUser::make();
        if(!$user->isLoggedIn()) return false;
        $province = $user->getProvince();
        return ($province->get('id') == $this->id);
    }
    public function isBanned() {
        return User::make($this->id)->isBanned();
    }
    public function isBot() {
        return false;
    }

    /**
     * Status: dead
     */
    public function isDead() {
        return $this->get('status') == 'dead';
    }
    public function afterDeath() {
        after_death($this->id);
    }

    /**
     * Status: protected
     */
    public function isProtected() {
        return $this->get('status') == 'nukeprotection';
    }
    public function getProtectionTimeLeft($format=false) {
        $diff = intval($this->get('nuke_protection_timestamp')) - current_time('timestamp');
        return ($format ? Format::time_diff($diff) : $diff);
    }

    /**
     * Other
     */
    public function inRange($user_id=false) {
        // result should be cached
        if($this->isCurrentUser()) return false; // I am not in range of myself
        if($this->isDead()) return false;
        if($this->isProtected()) return false;
        if($this->isFellowClanMember($user_id)) return false;

        $user = (!$user_id ? CurrentUser::make() : User::make($user_id));
        $province = $user->getProvince();
        if($clan = $this->getClan()) {
            if($my_clan_id = $province->getClanId()) {
                if($clan->getWarType($my_clan_id) == 'mutual') return true;
            }
        }

        $networth = $this->getNetworth();
        $viewerNetworth = $province->getNetworth();
        $range = Settings::get('attack_range_mult');
        return ($networth > $viewerNetworth / $range && $networth < $viewerNetworth * $range);
    }

    public function isAttackable($user_id=false) {
        if(!$this->inRange($user_id)) return false;
        if($this->isBot()) return true; // bots in range are always attackable

        $clan = $this->getClan();
        $user = (!$user_id ? CurrentUser::make() : User::make($user_id));
        $province = $user->getProvince();
        $my_clan_id = $province->getClanId();
        if($clan == false && $my_clan_id == false) return true; // clanless vs clanless (in range)
        elseif($clan != false && $my_clan_id != false) { // both in clan
            if($clan->getWarType($my_clan_id) != 'none') return true; // some type of war
        }
        return false; // either out of war, or clan vs clanless
    }

    /**
     * Public province data (viewable for everyone)
     */
    public function isOnline() {
        return User::make($this->id)->isOnline();
    }

    public function getName($format=false) {
        if(!$format) return $this->get('display_name');
        if($this->isBanned()) return '<strike>'.$this->get('display_name').'</strike> <strong>banned</strong>';

        $icon = '';
        if($this->isDead()) $icon = ' <span class="hover-tip" data-toggle="tooltip" data-title="This user is dead" data-placement="bottom"><i class="fas fa-skull"></i></span>';
        if($this->isProtected()) $icon = ' <span class="hover-tip" data-toggle="tooltip" data-title="This user is under assault protection" data-placement="bottom"><i class="fas fa-umbrella"></i></span>';
        return '<span class="name">'.$this->getName(false).'</span> '.
            '<span class="nameId">(#'.$this->get('id').')</span>' .
            $icon .
            ($this->isOnline()?' <span class="online">*</span>':'');
    }

    public function getLink($format=false) {
        if(!$format) return Request::siteUrl().'/users/profile/?id='.$this->id;
        return '<a class="memberField" href="'.$this->getLink(false).'">'. $this->getName($format).'</a>';;
    }

    public function getAvatar($classes='') {
        return User::make($this->id)->getAvatar($classes);
    }

    public function getNetworth($format=false) {
        $n = intval($this->get('networth'));
        if($this->isDead()) $n = 0;
        if(!$format) return $n;
        $fn = Format::networth($n);
        if($this->isCurrentUser() || $n == 0) return '<span>'. $fn .'</span>';

        $showRange = true;
        $timestamp = current_time('timestamp');
        $viewer = CurrentUser::make();
        if($this->isFellowClanMember($viewer->get('id'))) return '<span>'. $fn .'</span>';
        if($clan = $this->getClan()) {
            if($my_clan_id = $viewer->getProvince()->getClanId()) {
                if($clan->getWarType($my_clan_id) == 'mutual') {
                    $join_timestamp = $viewer->get('clan_join_stamp');
                    if($timestamp >= $join_timestamp) $showRange = false;
                }
            }
        }

        $min_nw = Format::networth($n / Settings::get('attack_range_mult'));
        $max_nw = Format::networth($n * Settings::get('attack_range_mult'));
        $inRange = $this->inRange();
        $fn .= ' <span class="hover-tip" data-toggle="tooltip"  data-placement="bottom"
            data-title="'.($inRange?'In range':'Out of range');
        if($showRange) $fn .= ", min ".$min_nw.', max '.$max_nw;
        else $fn .= ', mutual';
        $fn .= '"><i class="far fa-'.($inRange?'check':'times').'-circle"></i></span>';
        if($inRange) return '<strong>'. $fn .' </strong>';
        return '<span>'. $fn .'</span>';
    }

    public function getLand($format=false) {
        $n = intval($this->get('land'));
        return ($format ? Format::land($n) : $n);
    }


    /**
     * Private province data (viewable within clan)
     */
    public function getMoney($format=false) {
        $n = round($this->get('money'));
        return ($format ? Format::money($n) : $n);
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
        $n = ($this->get('sat_owned') !== 0 ? intval($this->get('sat_morale')) : 0);
        return ($format ? Format::morale($n) : $n);
    }

    public function getFreeLand($format=false) {
        $n = round($this->get('land'));
        $b = round($this->get('builtland'));
        return ($format ? Format::land($n-$b) : $n-$b);
    }

    public function getExplorationRate($format=false) {
        $n = round($this->get('land'));
        $perturnm2 = round(200-ceil($n*0.002));
        if (($perturnm2 < 50) && ($perturnm2 > 25)) $perturnm2 = 50;
        elseif ($perturnm2 < 25) $perturnm2 = 25;
        return ($format ? Format::land($perturnm2) : $perturnm2);
    }

    public function getMaxExploreLand() {
        $turnMax = $this->getTurns() * $this->getExplorationRate();
        if(Round::isDev() || Round::isTest()) return $turnMax;
        $maxExplore = round(Settings::get('max_explore_land') - $this->get('explored_today'));
        return $turnMax < $maxExplore ? $turnMax : $maxExplore;
    }

    public function getMaxSellLand() {
        $freeLand = $this->getFreeLand();
        if(Round::isDev() || Round::isTest()) return $freeLand;
        $maxSellLand = (Settings::get('max_sell_land') - $this->get('land_sold_today'));
        return $freeLand < $maxSellLand ? $freeLand : $maxSellLand;
    }

    public function getPower($format=false) {
        $n = intval($this->get('power'));
        return ($format ? Format::power($n) : $n);
    }

    /**
     *  Medal positions
     */
    public function getMedals($key=null,$format=false) {
        $medals = Medals::get();
        foreach($medals as $id => $medal) {
            $medals[$id]['position'] = !empty($this->get($id.'_position')) ? intval($this->get($id.'_position')) : 0;
            if($id == 'modev') $medals[$id]['damage'] = !empty($this->get($id.'_damage')) ? intval($this->get($id.'_damage')) : 0;
            else {
                $medals[$id]['next'] = !empty($this->get($id.'_next')) ? intval($this->get($id.'_next')) : 0;
                $medals[$id]['prev'] = !empty($this->get($id.'_prev')) ? intval($this->get($id.'_prev')) : 0;
            }
            if($format == true && isset($medals[$id]['format'])) {
                if($id == 'modev') $medals[$id]['damage'] = call_user_func(array('Format', $medals[$id]['format']), $medals[$id]['damage']);
                else {
                    $medals[$id]['next'] = call_user_func(array('Format', $medals[$id]['format']), $medals[$id]['next']);
                    $medals[$id]['prev'] = call_user_func(array('Format', $medals[$id]['format']), $medals[$id]['prev']);
                }
            }
            Hooks::trigger('get_province_medal', null, $medals, $id, $this);
        }
        return  ($key != null && $medals[$key] ? $medals[$key] : $medals);
    }
    public function getPosition($key,$format=false) {
        if(!in_array($key, array_keys(Medals::get()))) return false;
        $n = intval($this->get($key.'_position'));
        return ($format ? Format::position($n) : $n);
    }
    public function getPositionNext($key,$format=false) {
        if(!in_array($key, array_keys(Medals::get()))) return false;
        $n = intval($this->get($key.'_next'));
        return ($format ? Format::position($n) : $n); // dynamic format
    }
    public function getPositionPrev($key,$format=false) {
        if(!in_array($key, array_keys(Medals::get()))) return false;
        $n = intval($this->get($key.'_prev'));
        return ($format ? Format::position($n) : $n); // dynamic format
    }



    /**
     * Used on dashboard
     */
    public function getIncome($format=false) {
        $income = Settings::get('income_money');
        Hooks::trigger('get_province_income', null, $income, $this);
        return ($format ? Format::money($income) : $income);
    }

    public function getTelegramKey() {
        $telegram_key = $this->get('telegram_key');
        if(empty($telegram_key)) {
	        $telegram_key = uniqid();
	        $this->update('telegram_key', $telegram_key);
        }
        return $telegram_key;
    }

    /**
     * Get province clanbonuses
     */
    public function getBonuses() {
        $bonuses = get_posts(array(
            'author' => $this->id, 'numberposts' => -1, 'orderby' => 'post_date', 'post_type' => 'event_local', 'order' =>  'ASC',
            'meta_query' => array('relation' => 'AND', array('key' => 'attacktype', 'value' => array('bonus'), 'compare' => 'IN')),
        ));
        $return = array();
        foreach($bonuses as $bonus) {
            $return[] = Bonus::make($bonus);
        }
        return $return;
    }

    /**
     * Startingbonus
     */
    public function getStartingBonus() {
        if(empty($this->get('starting_bonus'))) return false;
        $s = Startbonuses::get($this->get('starting_bonus'));
        return (!!$s ? $s : false);
    }
    public function hasStartingBonus($key) {
        return $this->get('starting_bonus') == $key;
    }
    public function setStartingBonus($bonustype) {
        if(!empty($this->getStartingBonus())) return false; // Province already has a bonus
        $bonus = Startbonuses::get($bonustype);
        if(empty($bonus)) return false; // No such bonus
        Hooks::trigger('set_province_startbonus', null, $bonustype, $this);
        $this->update('starting_bonus', $bonustype);
        return true;
    }

    /**
     * Province bank account
     */
    public function getBankInterestRates($all=false) {
        $rates = Bank::getRates($all); // Array changes according to days left in this round, we want all in case of Deposit calculation
        Hooks::trigger('get_province_interest_rates', null, $rates, $this);
        return $rates;
    }
    public function getBankInterestRate($length) {
        $rates = $this->getBankInterestRates(true);
        return (isset($rates[$length]) ? $rates[$length] : 0);
    }

    public function getDeposits() {
        $posts = get_posts(array('posts_per_page' => -1, 'author' => $this->id, 'post_type' => 'deposit'));
        $deposits = array();
        foreach($posts as $post) {
            $deposits[$post->ID] = Deposit::make($post);
        }
        $this->set('deposits', $deposits); // Set in static cache
        return $deposits;
    }
    public function getDepositNum() {
        if(!$this->get('deposits')) $this->getDeposits();
        return count($this->get('deposits'));
    }
    public function getDepositAmount($format=false) {
        if(!$this->get('deposits')) $this->getDeposits();
        $n = 0;
        foreach($this->get('deposits') as $deposit) $n += $deposit->deposited();
        return ($format ? Format::money($n) : $n);
    }
    public function getDepositAvailable($format=false) {
        if(!$this->get('deposits')) $this->getDeposits();
        $n = 0;
        foreach($this->get('deposits') as $deposit) $n += $deposit->availableAmount();
        return ($format ? Format::money($n) : $n);
    }
    public function getDepositFinal($format=false) {
        if(!$this->get('deposits')) $this->getDeposits();
        $n = 0;
        foreach($this->get('deposits') as $deposit) $n += $deposit->finalAmount();
        return ($format ? Format::money($n) : $n);
    }


    public function getMaxDeposits() {
        $n = Settings::get('bank_max_deposits');
        // Hook
        return $n;
    }
    public function getMinDeposit($format=false) {
        $n = Settings::get('bank_min_deposit');
        // Hook
        return ($format ? Format::money($n) : $n);
    }
    public function getMaxDeposit($format=false) {
        $max_dep = Settings::get('bank_max_deposit');
        Hooks::trigger('get_province_max_deposit', null, $max_dep, $this);
        return ($format ? Format::money($max_dep) : $max_dep);
    }

    /**
     * Province market/sattelite/missile orders
     */
    public function getOrders() {
        $orders = get_posts(array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => 'market_order', 'author' => $this->id));
        $return = array();
        foreach($orders as $order) {
            $return[] = Order::make($order);
        }
        return $return;
    }
    public function getShippingTime($format=false) {
        $hours = 12;
        Hooks::trigger('get_province_shipping_time', null, $hours, $this);
        return $hours;
    }
    // We need the discount sepperatly because: 100 * 0.7 * 0.1 works differently then 100 * 0.6
    public function getShippingDiscount($format=false) {
        $discount = 0;
        Hooks::trigger('get_province_shipping_discount', null, $discount, $this);
        return $discount;
    }

    /**
     * Province Research
     */
    public function getResearches($key=null) {
        $researchInProgress = $this->getCurrentResearch();
        $researches = Researches::get();
        foreach($researches as $id => $research) {
            $level = !empty($this->get('level_'.$id)) ? intval($this->get('level_'.$id)) : 0;
            $value = (isset($research['level'.($level+1).'_value']) ? $research['level'.($level+1).'_value'] : 0); // next 'value'
            $description = (isset($research['level'.($level+1)]) ? $research['level'.($level+1)] : 'Unknown research level');

            $researches[$id]['level'] = $level;
            $researches[$id]['level_value'] = $value;
            $researches[$id]['inProgress'] = ($this->get('research_in_progress') == $id ? true : false);
            $researches[$id]['queued'] = ($this->get('queued_research') == $id ?  true : false);
            $researches[$id]['original_duration'] = $researches[$id]['duration']; // For nw calc
            $researches[$id]['nw'] = Format::networth($researches[$id]['original_duration'] * Settings::get('nw_research'));

            Hooks::trigger('get_province_research', null, $researches, $id, $this);

            // Show stuff after hooks fired
            $researches[$id]['level_description'] = str_replace('{value}', $researches[$id]['level_value'], $description);

            // Turns based on possible adjusted duration
            $researches[$id]['turns'] = round($researches[$id]['duration'] * Settings::get('turns_research'));
            if($researchInProgress != false) { // Queued research cost more turns
                $researches[$id]['turns'] = round($researches[$id]['duration'] * Settings::get('turns_queue_research'));
            }
        }
        return  ($key != null ? (!!$researches[$key] ? $researches[$key] : false) : $researches);
    }
    public function getCurrentResearch() {
        if(!empty($this->get('research_in_progress'))) {
            $researches = get_posts(array('posts_per_page' => 1, 'author' => $this->id, 'post_type' => 'research'));
            if(is_array($researches) && count($researches) && is_object($researches[0])) {
                return Research::make($researches[0]);
            }
        }
        return false;
    }
    public function getQueuedResearch() {
        $key = $this->get('queued_research');
        if(!empty($key)) return $this->getResearches($key);
        return false;
    }
    public function getResearchTimeLeft($format=false) {
        if($research = $this->getCurrentResearch()) return $research->timeLeft($format);
        return false;
    }
    public function hasResearchMinimalLevel($key,$level=0) { // 0 will always return true
        $r = !empty($this->get('level_'.$key)) ? intval($this->get('level_'.$key)) : 0;
        return $r >= $level;
    }

    /**
     * Get all information of one or all buildings of this province
     */
    public function getBuildings($key=null) {
        $totalMoney = $this->getMoney();
        $totalturns = $this->getTurns();
        $buildingsPerTurn = $this->getBuildingsPerTurn();
        $freeTurns = floor($totalturns * $buildingsPerTurn);
        $unitsPerTurn = $this->getUnitsPerTurn();
        $freeLand = $this->getFreeLand();
        $freeSpace = $this->getBuildSpace();
        $missiles = $this->getMissiles();

        //$units = $this->getUnits();
        $units = Units::get(); // we don't want to start an infinite loop
        $buildings = Buildings::get();
        foreach($buildings as $id => $building) {
            $buildings[$id]['num'] = (!!$this->get($id) ? intval($this->get($id)) : 0);
            $buildings[$id]['original_price'] = $building['price']; // For nw calc
            $buildings[$id]['buildprice'] = $building['price']; // Might become cheaper with research/startbonus
            $buildings[$id]['demoprice'] = $building['price'] * Settings::get('demolish_price_multi');
            $buildings[$id]['networthPerUnit'] = round($building['price'] * $building['networth']/100); // of original price!
            $buildings[$id]['maxbuild'] = min(floor($totalMoney / $buildings[$id]['buildprice']), $freeTurns, $freeSpace);
            $occupied = 0;
            if(isset($building['houses'])) {
                foreach($units as $unitKey => $unit) {
                    if($unit['type'] == $building['houses'] || $unit['sectype'] == $building['houses']) {
                        $occupied += (!!$this->get($unitKey.'_ordered') ? intval($this->get($unitKey.'_ordered')) : 0);
                        $occupied += (!!$this->get($unitKey.'_owned') ? intval($this->get($unitKey.'_owned')) : 0);
                    }
                }
                foreach($missiles as $missile_key => $missile) {
                    if($missile_key == 'tomahawk') continue; // are not housed in buildings
                    if($missile['type'] == $building['houses']) $occupied += ($missile['ordered'] + $missile['num']);
                }
            }
            $buildings[$id]['occupied'] = ($occupied > 0 ? $occupied : 0);
            $buildings[$id]['maxdemo'] = $buildings[$id]['num'];
            if($buildings[$id]['occupied']>0) {
                $buildings[$id]['maxdemo'] -= ceil($buildings[$id]['occupied'] / $building['housing']);
            }

            Hooks::trigger('get_province_building', null, $buildings, $id, $this);
        }

        if($shootdown_chance = $this->getShootdownChance()) {
            $buildings['antimissile']['shootdown_chance'] = $shootdown_chance;
            $buildings['antimissile']['description'] = 'Each Anti-Missile System protects 100m2 of your built land.
                Chance to shoot down missiles is currently '. $shootdown_chance .'%.
                Every Anti-Missile System has a 25% chance to shoot down tomahawk missiles.';
        }

        return ($key != null ? (!!$buildings[$key] ? $buildings[$key] : false) : $buildings);
    }

    public function getBuildingsPerTurn() {
        $bbt = 5;
        Hooks::trigger('get_province_buildings_per_turn', null, $bbt, $this);
        return $bbt;
    }

    public function getUnitTypeSpace() {
        $buildings = $this->getBuildings();
        $space = array();
        foreach($buildings as $id => $building) {
            if(!isset($building['houses'])) continue;
            if(!isset($space[$building['houses']])) $space[$building['houses']] = 0;
            $space[$building['houses']] += ($building['num'] * $building['housing']);
        }
        return $space;
    }

    public function getUnitTypeUsedSpace() {
        $buildings = $this->getBuildings();
        $usedSpace = array();
        foreach($buildings as $id => $building) {
            if(!isset($building['houses'])) continue;
            if(!isset($usedSpace[$building['houses']])) $usedSpace[$building['houses']] = 0;
            $usedSpace[$building['houses']] += $building['occupied'];
        }
        return $usedSpace;
    }

    public function getBuildSpace() {
        return floor($this->getFreeLand() / Settings::get('land_per_building'));
    }

    public function getMaxBuild() {
        return min($this->getBuildSpace(), floor($this->getBuildingsPerTurn() * $this->getTurns()) );
    }

    /**
     * Used for showing pp-buildings, and status on dashboard
     */
    public function getShootdownChance($format=false) {
        $shootdown_chance = 0;
        $AMS = intval($this->get('antimissile'));
        if($AMS > 0) {
            $def_land = intval($this->get('builtland'));
            $shootdown_chance = round( (($AMS*100)/$def_land)*100, 2 );
            if ($shootdown_chance >= 75) $shootdown_chance = 75;
        }
        return ($format ? $shootdown_chance.'%' : $shootdown_chance);
    }

    /**
     * Calculate total buildings number
     */
    public function getBuildingsNum() {
        $num = 0;
        foreach(array_keys(Buildings::get()) as $key) {
            $num += (!!$this->get($key) ? intval($this->get($key)) : 0);
        }
        //$this->update('buildings_built', $num); // overhead to always update this
        //@todo: we might want to update builtland too?
        return $num;
    }

    /**
     * Build units per turn, might get modified by a research some day
     */
    public function getUnitsPerTurn($key=null) {
        $unitsPerTurn = Settings::get('units_per_turn');
        Hooks::trigger('get_province_units_per_turn', null, $unitsPerTurn, $this);
        return ($key != null ? (!!$unitsPerTurn[$key] ? $unitsPerTurn[$key] : false) : $unitsPerTurn);
    }

    /**
     * Get all information of one or all of one type, or all units of this province
     */
    public function getUnits($key=null,$type=null) {

        $space = $this->getUnitTypeSpace();
        $usedSpace = $this->getUnitTypeUsedSpace();
        $totalMoney = $this->getMoney();
        $totalturns = $this->getTurns();
        $unitsPerTurn = $this->getUnitsPerTurn();
        $special_units = Settings::get('special_units');
        $max_special_sell = Settings::get('max_special_sell');
        $max_special_order = Settings::get('max_special_order');
        $max_special_space = $space['special'] - $usedSpace['special'];
        $units = Units::get();
        $discount = $this->getShippingDiscount();

        // You cannot sell subs when having tommy's
        $totalmissiles = ($this->get('tomahawk_owned') + $this->get('tomahawk_ordered'));
        $maxSellSubs = ($totalmissiles > 0 ? ceil($totalmissiles/2) : -1);

        foreach($units as $id => $unit) {
            $units[$id]['num'] = (!!$this->get($id.'_owned') ? intval($this->get($id.'_owned')) : 0);
            $units[$id]['ordered'] = (!!$this->get($id.'_ordered') ? intval($this->get($id.'_ordered')) : 0);
            $units[$id]['original_price'] = $unit['price']; // For nw calc
            $units[$id]['buildprice'] = $unit['price']; // Might become cheaper with research/startbonus
            $units[$id]['orderprice'] = round($unit['price'] * Settings::get('unit_order_multi') * (1-$discount));
            $units[$id]['sellprice'] = round($unit['price'] * Settings::get('unit_sell_multi'));
            $units[$id]['tradeprice'] = round($unit['price'] * Settings::get('unit_trade_multi'));
            $units[$id]['networthPerUnit'] = round($unit['price'] * $unit['networth']/100); // of original price!

            $maxBuy = floor($totalMoney / $units[$id]['buildprice']);
            $maxOrder = floor($totalMoney / $units[$id]['orderprice']);
            $maxSell = $units[$id]['num'];
            $maxTurns = floor($totalturns * $unitsPerTurn[$unit['type']]);
            $maxSpace = max($space[$unit['type']] - $usedSpace[$unit['type']], 0);
            $maxSpecialSpace = (in_array($id, $special_units) ? $max_special_space : $maxSpace);
            $maxSpecialBuy = (in_array($id, $special_units) ? $space['special'] - $usedSpace['special'] : $maxBuy);
            $maxSpecialSell = (in_array($id, $special_units) ? ($max_special_sell-$this->get('special_sold_today')) : $maxSell);
            $maxSpecialOrder = (in_array($id, $special_units) ? $max_special_order : $maxOrder);

            $units[$id]['space'] = $maxSpace;
            $units[$id]['specialspace'] = (in_array($id, $special_units) ? $space['special'] - $usedSpace['special'] : 0);
            $units[$id]['maxbuild'] = min($maxSpecialBuy, $maxBuy, $maxSpace, $maxTurns);
            $units[$id]['maxorder'] = min($maxOrder, $maxSpace, $maxSpecialSpace, $maxSpecialOrder);
            $units[$id]['maxsell'] = min($maxSell, $maxSpecialSell);
            if($id == 'submarine' && $maxSellSubs > -1) {
                $units[$id]['maxsell'] = min($units[$id]['maxsell'], ($units[$id]['num']-$maxSellSubs));
            }

            Hooks::trigger('get_province_unit', null, $units, $id, $this);
        }

        return ($key != null ? (!!$units[$key] ? $units[$key] : false) : $units);
    }
    public function getUnitAttackTypeNum($attacktype=null) {
        $num = 0;
        foreach(Units::get() as $k => $unit) {
            if(in_array($attacktype,$unit['attacktype'])) $num += (!!$this->get($k.'_owned') ? intval($this->get($k.'_owned')) : 0);
        }
        return $num;
    }
    public function getUnitTypeNum($type=null) {
        $num = 0;
        foreach(Units::get() as $k => $unit) {
            if($type==$unit['type']) $num += (!!$this->get($k.'_owned') ? intval($this->get($k.'_owned')) : 0);
        }
        return $num;
    }

    public function getMostUsedUnitType() {
        $nums = array(); $max = 0; $bestType = 'air';
        foreach(Units::get() as $k => $unit) {
            if(!isset($nums[$unit['type']])) $nums[$unit['type']] = 0;
            $nums[$unit['type']] += (!!$this->get($k.'_owned') ? intval($this->get($k.'_owned')) : 0);
        }
        foreach($nums as $type => $num) {
            if($num > $max) { $bestType = $type; $max = $num; }
        }
        return $bestType;
    }

    public function getUnitsNum($key=null) {
        $num = 0;
        foreach(Units::get() as $k => $unit) {
            if(is_null($key) || $k == $key) {
                $num += (!!$this->get($k.'_owned') ? intval($this->get($k.'_owned')) : 0);
            }
        }
        return $num;
    }
    function getSpyUnits() {
        $spiesOwned = array();
        foreach(Units::get() as $k => $unit) {
            if(in_array('spy', $unit['attacktype']) && !!$this->get($k.'_owned')) {
                $spiesOwned[$k] = $unit['normalname'];
            }
        }
        return $spiesOwned;
    }
    function get_spy_buttons($target_id) {
        if($this->get('id') == $target_id) return;
        if($this->isFellowClanMember($target_id)) return;
        $target = Province::make($target_id);
        if($target->isDead() || $target->isProtected() || $target->isBanned()) return;
        $spiesOwned = $this->getSpyUnits();
        if(!count($spiesOwned)) return;
        if(!isset($_SESSION['tokens'])) $_SESSION['tokens'] = array();
        $_SESSION['tokens'][$target_id] = uniqid();
        $btnClass = (count($spiesOwned)==2?'col-md-6':'col-md-12');
        $return = '<form action="'.Request::siteUrl().'/attack/?id='. $target_id .'&token='. $_SESSION['tokens'][$target_id] .'" method="post" class="row no-gutters fw-row profileButtonRow">';
        $return .= '<input type="hidden" name="id" value="'. $target_id .'">';
        $return .= '<input type="hidden" name="attacktype" value="spy">';
        $return .= '<input type="hidden" name="attackmode" value="normal">';
        $return .= '<input type="hidden" name="maintarget" value="none">';
        $return .= '<input type="hidden" name="token" value="'. $_SESSION['tokens'][$target_id] .'">';
        foreach($spiesOwned as $key => $name) {
            $return .= '<button type="submit" name="spytype" value="'. $key .'" class="'. $btnClass .' '.($key=='spy'?'secondButton ':'').'cancelButton profileButton">
                <i class="fas fa-binoculars"></i> &nbsp;Send '. $name .'
            </button>';
        }
        $return .= '</form>';
        return $return;
    }

    /**
     * Get all information of one or all Missiles of this province
     */
    public function getMissiles($key=null) {
        $missiles = Missiles::get();
        foreach($missiles as $id => $missile) {
            $missiles[$id]['num'] = (!!$this->get($id.'_owned') ? intval($this->get($id.'_owned')) : 0);
            $missiles[$id]['ordered'] = (!!$this->get($id.'_ordered') ? intval($this->get($id.'_ordered')) : 0);
            $missiles[$id]['original_price'] = $missile['price']; // For nw calc
            Hooks::trigger('get_province_missile', null, $missiles, $id, $this);
        }
        return ($key != null ? (!!$missiles[$key] ? $missiles[$key] : false) : $missiles);
    }
    public function getMissileNum() {
        $num = 0;
        foreach(array_keys(Missiles::get()) as $key) {
            $num += (!!$this->get($key.'_owned') ? intval($this->get($key.'_owned')) : 0);
        }
        return $num;
    }

    /**
     * Get all information of one or all Satellites of this province
     * WARNING: currently a province should be able to have only one sat
     */
    public function getSatellites($key=null) {
        $timestamp = current_time('timestamp');
        $satellites = Satellites::get();
        $orders = $this->getOrderedSatellites();
        $sat_owned = $this->get('sat_owned');
        $sat_endlife = intval(trim($this->get('sat_endlife')));
        foreach($satellites as $id => $satellite) {
            $satellites[$id]['timeleft'] = $satellites[$id]['stealthtime'] = 0;
            $satellites[$id]['in_progress'] = false;
            $satellites[$id]['num'] = ($sat_owned == $id ? 1 : 0);
            if($satellites[$id]['num']>0) $satellites[$id]['timeleft'] = floor($sat_endlife-$timestamp);
            $satellites[$id]['active'] = ($id=='stealths' && $satellites[$id]['num']>0 && $this->get('stealth_sat_status')=='active' ? true : false);
            if($satellites[$id]['active']) $satellites[$id]['stealthtime'] = intval($this->get('stealth_sat_time')) - current_time('timestamp');
            foreach($orders as $satorder) {
                if($satorder->title() == $satellite['name']) {
                    $satellites[$id]['in_progress'] = true;
                    $satellites[$id]['timeleft'] = $satorder->timeLeft();
                }
            }
            $satellites[$id]['original_price'] = $satellite['price']; // For nw calc
            $satellites[$id]['days'] = 0; // This will be set in research-class
            Hooks::trigger('get_province_satellite', null, $satellites, $id, $this);
        }
        return ($key != null ? (!!$satellites[$key] ? $satellites[$key] : false) : $satellites);
    }
    public function getOrderedSatellites($key=null) {
        $orders = array();
        foreach ($this->getOrders() as $order) {
            if($order->type() == 'satellite') $orders[$order->get('unit_type')] = $order;
        }
        return ($key != null ? (!!$orders[$key] ? $orders[$key] : false) : $orders);
    }
    public function getSatelliteNum() {
        if(!$this->hasResearchMinimalLevel('satellite_construction', 1)) return 0;
        $sat = $this->get('sat_owned');
        if(empty($sat)) return 0;
        return (!!Satellites::get($sat) ? 1 : 0);
    }

    public function crashSatellite($key,$cost=0) {

        $this->update('sat_owned', 0);
        $this->update('sat_endlife', 0);
        $this->update('stealth_sat_status', 0);
        $this->update('stealth_sat_time', 0);
        $this->update('money', $this->getMoney() - $demo_cost);
        Event::create(array(
            'title' => 'Sat crash: ' . $this->get('id'), 'type' => 'sat_crash', 'attacker_id' => 0, 'defender_id' => $this->get('id')
        ), $this->get('id'));
        return true;
    }


    /**
     * Get Clan
     */
    public function getClanId() {
        return (!empty($this->get('clan_id_user')) ? $this->get('clan_id_user') : false);
    }
    public function getClan() {
        return (!empty($this->get('clan_id_user')) ? Clan::make($this->get('clan_id_user')) : false);
    }
    public function isFellowClanMember($target_id) {
        if($clan = $this->getClan()) {
            return in_array($target_id, $clan->getMembers());
        }
        return false;
    }
    public function getClanPoints($format=false) {
        $n = $this->get('user_clan_points');
        return ($format ? Format::points($n) : $n);
    }
    public function getPPA($format=false) {
        $attacksMade = $this->get('in_war_attacks');
		$pts = $this->get('user_clan_points');
		return ($pts > 0 ? ($attacksMade > 0 ? round($pts / $attacksMade, 1) : 0) : 0);
    }

    /**
     * Messages
     */
    public function getInbox() {
        $this->update('new_messages', 0);
        $convos = array();
        $posts = get_posts(array(
            'numberposts' => -1, 'post_type' => 'user_message', 'meta_key' => 'last_update_stamp', 'orderby' => 'meta_value', 'order' =>  'DESC',
            'meta_query' => array('relation' => 'OR',
                array('key' => 'receiver_id', 'value' => $this->get('id')),
                array('key' => 'sender_id', 'value' => $this->get('id'))
            ),
        ));
        foreach($posts as $post) $convos[] = Conversation::make($post->ID);
        return $convos;
    }

    /**
     * Keep track of turn usage
     */
    public function turn_spread($turntype, $addedturns) {
        $turnSpread = maybe_unserialize(maybe_unserialize($this->get('turn_spread'))); // Do not make an object, keep it an array
        if(!is_array($turnSpread)) $turnSpread = array();
        if(!isset($turnSpread[$turntype])) $turnSpread[$turntype] = 0;
        $turnSpread[$turntype] += $addedturns;
        $this->update('turn_spread', maybe_serialize($turnSpread));
    }
    public function getTurnSpread() {
        $turn_spread = maybe_unserialize(maybe_unserialize($this->get('turn_spread')));
        return phpObject::make($turn_spread);
    }

    /**
     * Some stuff should not be calculated on the fly
     * So we put it in DB and update only when needed
     */
    public function calculateNetworth() {
        // calculate unit NW (using original price!)
        $unit_networth = 0;
        foreach($this->getUnits() as $key => $unit) {
            $unit_networth += $unit['num'] * $unit['original_price'] * ($unit['networth'] / 100);
        }

        // calculate missile NW
        $missile_networth = 0;
        foreach($this->getMissiles() as $key => $missile) {
            $missile_networth += $missile['num'] * $missile['original_price'] * ($missile['networth'] / 100);
        }

        // calculate building NW (using original price!)
        $building_networth = 0;
        foreach ($this->getBuildings() as $key => $building) {
            if($building['num'] == 0) continue;
            $building_networth += $building['num'] * $building['original_price'] * ($building['networth'] / 100);
        }

        // calculate research NW (using original duration!)
        $research_networth = 0;
        foreach ($this->getResearches() as $key => $research) {
            if($research['level']==0) continue;
            $research_networth += $research['original_duration'] * Settings::get('nw_research') * $research['level'];
        }

        // calculate satellite NW (using original price!)
        $sat_networth = 0;
        foreach ($this->getSatellites() as $key => $satellite) {
            if($satellite['num'] == 0) continue;
            $sat_networth += $satellite['num'] * $satellite['original_price'] * ($satellite['networth'] / 100);
        }

        // calculate land NW
        $land_networth = round($this->getLand() * Settings::get('nw_land'));

        $totalNW = round($unit_networth+$missile_networth+$building_networth+$research_networth+$sat_networth+$land_networth);
        $this->update('networth', $totalNW);
        $this->update('unit_nw', round($unit_networth));
        $this->update('missile_nw', round($missile_networth));
        $this->update('building_nw', round($building_networth));
        $this->update('research_nw', round($research_networth));
        $this->update('sat_nw', round($sat_networth));
        $this->update('land_nw', round($land_networth));
    }

    /**
     *
     */
    public function calculatePower() {

        $used_power = $power_production = 0;
        foreach ($this->getBuildings() as $key => $building) {
            if($building['num'] == 0) continue;
            $power_production += $building['powerprod'] * $building['num'];
            $used_power += $building['power'] * $building['num'];
        }

        // @wp
        $empReduction = 0;
        $emps = get_posts(array('numberposts' => -1, 'post_type' => 'emp', 'meta_key' => 'defender_emp', 'meta_value' => $this->id));
        $empReduction = 0;
        foreach ($emps as $emp) {
            $empReduction += get_post_meta($emp->ID, 'deduction_emp', true);
        }
        if($power_production == 0) $power_production=1;
        $this->update('power', $used_power / $power_production * 100 + $empReduction);
    }

    /**
     *
     */
    public function calculateFreeLand() {
        $totalbuildings = 0;
        foreach ($this->getBuildings() as $key => $building) {
            if($building['num'] == 0) continue;
            $totalbuildings += $building['num'];
        }
        $this->update('builtland', $totalbuildings * Settings::get('land_per_building'));
        return $totalbuildings;
    }

    /**
     * Heavy shit
     */
    public function count_all_stats() {

        $this->calculateNetworth();

        $this->calculateFreeLand();

        $totalNW = $this->get('networth');
        if($totalNW > $this->get('highest_networth')) $this->update('highest_networth', $totalNW);

        $land = $this->getLand();
        if($land > $this->get('highest_land')) $this->update('highest_land', $land);

        $this->calculatePower();
    }

    public function reset() {
        $this->update('status', 'dead');
        $this->update('reset_status', 1);
        $moneyThieved = $this->get('money_gained_thieving');
        if(($moneyThieved-20000000) <= 0) $newValue = 0;
        else $newValue = $moneyThieved-20000000;
        $this->update('money_gained_thieving', $newValue);
        return true;
    }

    public function notify($type, $attacker=0) {
        fcm_send_notification($this->get('id'), $type, $attacker);
    }

    /*
    invite(),
    kick(),
    getTrophies(),
    kill(),
    attack(),
    spy()
    */
}