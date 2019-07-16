<?php

class Province extends DbObject {
    //static $table = 'provinces';
    public static $cache = 'provinces';
    public static $bankAccount = false;
    public static $researches = false;

    // @todo: add building, missile, sat, research, unit-fields from data objects instead of huge array
    public $fields = array(
        // Generic
        'id','display_name','avatar_user','status','starting_bonus','new_events','new_messages','new_global_events',
        'user_lock','morale_lock','telegram_key',

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

    // Returns formatted data
    public function ajaxHeader($return) {
        $return['success']  = true;
        $return['globals']  = $this->getGlobalNum();
        $return['locals']   = $this->getLocalNum();
        $return['messages'] = $this->getMessageNum();
        $return['turns'] 	= $this->getTurns(true);
        $return['networth'] = $this->getNetworth(true);
        $return['money'] 	= $this->getMoney(true);
        $return['morale'] 	= $this->getMorale(true);
        $return['land'] 	= $this->getLand(true);
        $return['freeland'] = $this->getFreeLand(true);
        $return['power'] 	= $this->getPower(true);
        return $return;
    }

    public function ajaxDevfunds($return) {
        if(!Round::isDev() && !Round::isTest() && !Round::isSandbox()) return array('status' => 'Unavailable');

        $this->update('money', $this->getMoney() + Settings::get('devfunds_money'));
        $this->update('turns', $this->getTurns() + Settings::get('devfunds_turns'));
        $this->update('morale', 100);
        $this->update('morale_pool', 100);
        $this->update('sat_morale', 100);
        if($this->isDead() || $this->isProtected()) $this->update('status', 'online');

        if($research = $this->getCurrentResearch()) $research->end(); // could start queued research too
        if($research = $this->getCurrentResearch()) $research->end(); // end the queued research too

        foreach($this->getOrders() as $order) $order->end();

        return array('success' => true, 'status' => 'All set: '.
            Format::money(Settings::get('devfunds_money')).', full morale, orders, research and '.
            Format::turns(Settings::get('devfunds_turns')).' turns received');
    }

    public function ajaxStartingbonus($return) {
        if(!empty($this->getStartingBonus())) return array('status' => 'You already have a startbonus.');
        $bonustype = Request::post('bonustype');
        if(!$this->setStartingBonus($bonustype)) return array('status' => 'No such startbonus.');
        return array('success' => true, 'status' => 'Starting bonus picked');
    }

    public function ajaxClanBonus($return) {
        $bonus = Bonus::make(intval(Request::post('id')));
        if($bonus->get('id')==0) return array('status' => 'No such bonus.');
        if($bonus->isUsed()) return array('status' => 'Bonus already used.');
        if($bonus->receive()) return array('success' => true, 'status' => $bonus->money(true).' money and '.$bonus->turns(true).' turns received');
        return array('status' => 'Undefined error.');
    }

    public function ajaxRemoveNp($return) {
        // @todo: use new LocalEvent();

        $new_event_id = wp_insert_post(array(
            'post_title' => 'Nukeprotection removed for '.$this->id,
            'post_status' => 'publish', 'post_type' => 'event_local', 'post_author' => $this->id
        ));
        update_field('attacktype', 'nukeprotection', $new_event_id);
        update_field('defender_id', $this->id, $new_event_id);
        update_field('attacker_id', $this->id, $new_event_id);
        update_field('time_attacked', current_time('timestamp'), $new_event_id);
        $this->update('new_events', intval($this->get('new_events')) + 1 );

        $this->update('status', 'online');
        return array('success' => true, 'status' => 'Protection removed');
    }

    public function ajaxSetResearch($return) {
        $researchInProgress = $this->getCurrentResearch();
        $researchQueued = $this->getQueuedResearch();
        if($researchInProgress !== false && $researchQueued !== false) return array('status' => 'There is already a research in progress, and you already queued a research.');

        $new_key = Request::post('research');
        if(!Researches::get($new_key)) return array('status' => 'No such research');
        $new_research = $this->getResearches($new_key);
        if($new_research['level']>=$new_research['maxlevel']) return array('status' => 'Max reached');
        if($new_research['queued']) return array('status' => 'Already queued');
        if($new_research['inProgress'] && ($new_research['level']+1)>=$new_research['maxlevel']) return array('status' => 'Already in progress');

        $queueResearch = ($researchInProgress !== false);

        $totalturns = $this->getTurns();
        $turn_cost = ($queueResearch ? Settings::get('turns_queue_research') : Settings::get('turns_research'));
        if($totalturns < $turn_cost) return array('status' => 'No enough turns');

        $this->update('turns', $totalturns - $turn_cost);
        $this->turn_spread( ($queueResearch ? 'research_queue' : 'research'), $turn_cost); //@wp

        $return = array('success' => true,'started' => $new_key, 'status' => '', 'endtime' => 'queued');
        if($queueResearch === true) {
            $this->update('queued_research', $new_key);
            return array_merge($return, array('status' => $new_research['name'].' research queued'));
        }
        else {
            // set up arguments for creating research post
            // @todo use new Research-object
            $endTime = current_time('timestamp') + ($new_research['duration']*60*60);
            $args = array('post_title' => $endTime, 'post_status' => 'publish', 'post_content' => $new_key, 'post_type' => 'research', 'post_author' => $this->id);
            $new_research_id = wp_insert_post($args);
            $this->update('research_in_progress', $new_key);
            return array_merge($return, array(
                'status' => $new_research['name'].' research started',
                'hidebutton' => ($new_research['level']+1) >= $new_research['maxlevel'] ? $new_key.'_button' : '',
                'endtime' => $this->getResearchTimeLeft()
            ));
        }
        return array('success' => false, 'status' => 'Research task failed successfully');
    }

    public function ajaxExploreLand($return) {
        $postedTurns = abs(floor(Request::post('turns')));
        if ($postedTurns < 1 || !is_numeric(($postedTurns))) return array('status' => 'Not a valid number.');
        $perturnm2 = $this->getExplorationRate();
        if($perturnm2 < 0) return array('status' => 'No more exploring possible');
        $turns = $this->getTurns();
        if($turns < $postedTurns) return array('status' => 'Not enough turns');
        $maxLand = $this->getMaxExploreLand();
        $postedLand = ($postedTurns*$perturnm2);
        if ($maxLand < $postedLand) return array('status' => 'You can only explore '. Format::land($maxLand).'</strong> more land.');

        $ownedland = $this->getLand();
        $this->update('turns', round($turns-$postedTurns));
        $this->update('land', round($ownedland + $postedLand));
        $this->update('explored_today', round($this->get('explored_today') + $postedLand));
        $this->turn_spread('exploring', $postedTurns); //@wp
        $this->count_all_stats();
        $exploredToday = $this->get('explored_today');

        // Log it
        $current = file_get_contents('explorelog.txt');
        $current .= current_time('G:i:s | d-m-Y')."\n". "ID: ".$this->id."\n" . "Turns used: ".$postedTurns."\n" . "New land: ".($ownedland+$postedLand)."\n";
        file_put_contents('explorelog.txt', $current."Explored today: ".$exploredToday."\n\n");

        $perturnm2 = $this->getExplorationRate();
        $maxLand = $this->getMaxExploreLand();
        $maxAmount = floor($maxLand/$perturnm2);
        $maxSell = $this->getMaxSellLand();
        $return = array_merge($return, array(
            'success' => true,
            'status' => Format::land($postedLand).' explored',
            'newrate' => Format::land($perturnm2),
            'exploredtoday' => 'You have explored <strong>'.Format::land($exploredToday).' </strong> today.
                You can explore an additional <span class="maxexp" data-max="'. $maxAmount .'"><strong>'.Format::land($maxLand).'</strong>
                <i>('.$maxAmount.' turns)</i></span>',
            'maxturns' => $maxAmount,
            'maxsell' => $maxSell,
            'soldtoday' => Format::land(1).' has a value of '.Format::money(Settings::get('money_per_land')).'.
                You have '. $this->getFreeLand(true) .' of free land.
                You have sold <strong>'.Format::land($this->get('land_sold_today')).'</strong> today. You can sell an additional
                <strong class="maxsell" data-max="'. $maxSell .'">'. Format::land($maxSell) .'</strong>',
        ));
        return $return;
    }

    public function ajaxSellLand($return) {
        $postedLand = abs(floor(Request::post('land')));
        if($postedLand < 0 || !is_numeric($postedLand)) return array('status' => 'Not a valid number.');
        $freeland = $this->getFreeLand();
        if($freeland < 0) return array('status' => 'Cannot sell! Not enough free land');
        if($postedLand > $freeland) return array('status' => 'Not enough free land');
        $maxSellLand = Settings::get('max_sell_land')-$this->get('land_sold_today');
        if ($maxSellLand < $postedLand) return array('status' => 'Cannot sell any more land');

        $this->update('land', round($this->getLand() - $postedLand));
        $this->update('land_sold_today', round($this->get('land_sold_today') + $postedLand));
        $this->update('money', $this->getMoney() + round($postedLand * Settings::get('money_per_land')));
        $this->count_all_stats();

        // Log it
        $current = file_get_contents('landselllog.txt');
        $current .= current_time('G:i:s | d-m-Y')."\n" . "ID: ".$this->id."\n" . "Sold land: ".$postedLand."\n";
        file_put_contents('landselllog.txt', $current . "Land sold today: ". $this->get('land_sold_today') ."\n\n");

        $maxSell = $this->getMaxSellLand();
        $return = array_merge($return, array(
            'success' => true,
            'status' => 'You sold '.Format::land($postedLand).' for a total sum of '.Format::money($postedLand * Settings::get('money_per_land')),
            'maxsell' => $maxSell,
            'soldtoday' => Format::land(1).' has a value of '.Format::money(Settings::get('money_per_land')).'.
                You have '. $this->getFreeLand(true) .' of free land.
                You have sold <strong>'.Format::land($this->get('land_sold_today')).'</strong> today. You can sell an additional
                <strong class="maxsell" data-max="'. $maxSell .'">'. Format::land($maxSell) .'</strong>'
        ));
        return $return;
    }

    public function ajaxBuildings($return) {
        $buildings = $this->getBuildings();
        if(!is_array($_POST['demo']) || !is_array($_POST['build'])) return array('status' => 'Not a valid request.');
        $status = array('Done');

        // Demo first, opens up land
        $buildingsNum = $this->getBuildingsNum();
        $money = $this->getMoney();
        $demo = array();
        $demo_num = $demo_price = 0;
        foreach($_POST['demo'] as $key => $num) {
            if(empty($num) || !is_numeric($num) || $num < 0 || !isset($buildings[$key])) continue;
            $demo[$key] = min($num, $buildings[$key]['maxdemo']);
            $demo_num += $demo[$key];
            $demo_price += $demo[$key] * $buildings[$key]['demoprice'];
        }
        if($demo_num == $buildingsNum) return array('status' => 'Cannot demolish all your buildings');
        if($demo_price > $money) return array('status' => 'Insufficient funds');
        foreach ($demo as $key => $count) {
            $this->update($key, $this->get($key) - $count);
        }
        $this->update('buildings_built', $this->get('buildings_built') - $demo_num);
        $this->update('money', $money - $demo_price);
        if($demo_num > 0) $status[] = $demo_num.' buildings demolished';

        // Recalculate maxbuild and freeland for building
        $this->calculateFreeLand();
        $buildings = $this->getBuildings();
        $freeland = $this->getFreeLand();
        $money = $this->getMoney();
        $turns = $this->getTurns();
        $build = array();
        $build_num = $build_price = 0;
        foreach($_POST['build'] as $key => $num) {
            if(empty($num) || !is_numeric($num) || $num < 0 || !isset($buildings[$key])) continue;
            $build[$key] = min($num, $buildings[$key]['maxbuild']);
            $build_num += $build[$key];
            $build_price += $build[$key] * $buildings[$key]['buildprice'];
        }
        $turns_needed = ceil($build_num/$this->getBuildingsPerTurn());
        if($build_price > $money) $status[] = 'insufficient funds to build';
        else if($turns_needed > $turns) $status[] = 'not enough turns to build';
        else if($build_num*Settings::get('land_per_building') > $freeland) $status[] = 'Not enough free land';
        else {
            foreach ($build as $key => $count) {
                $this->update($key, $this->get($key) + $count);
            }
            $this->update('buildings_built', $this->get('buildings_built') + $build_num);
            $this->update('money', $money - $build_price);
            $this->update('turns', $turns - $turns_needed);
            $this->turn_spread('buildings', $turns_needed); //@wp
        }

        // Recalculate maxes
        $this->count_all_stats();
        $buildings = $this->getBuildings();
        $maxbuild = $maxdemo = $owned = array();
        foreach($buildings as $key => $building) {
            $maxbuild[$key] = $building['maxbuild'];
            $maxdemo[$key] = $building['maxdemo'];
            $owned[$key] = $building['num'];
        }
        return array_merge($return, array(
            'success' => true, 'status' => implode(', ', $status),
            'maxbuild' => $this->getMaxBuild(), 'buildspace' => $this->getBuildSpace(), 'turns' => $this->getTurns(),
            'networth' => $this->getNetworth(), 'money' => $this->getMoney(), 'freeland' => $this->getFreeLand(),
            'freeland_formatted' => $this->getFreeLand(true), 'power' => $this->getPower(true),
            'buildmax' => $maxbuild, 'demomax' => $maxdemo, 'owned' => $owned
        ));
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
    public function inRange() {
        if($this->isCurrentUser()) return false; // I am not in range of myself
        if($this->isDead()) return false;
        if($this->isProtected()) return false;

        $user = CurrentUser::make();
        $networth = $this->getNetworth();
        $viewerNetworth = $user->getNetworth();
        $range = Settings::get('attack_range_mult');
        return ($networth > $viewerNetworth / $range && $networth < $viewerNetworth * $range);
    }

    /**
     * Public province data (viewable for everyone)
     */
    public function getName() {
        return $this->get('display_name');
    }

    public function getLink() { // @todo: make a permalink for users
        return Request::siteUrl().'/users/profile/?id='.$this->id;
    }

    public function getAvatar() {
        $avatar = $this->get('avatar_user');
        $return = '<a href="'.$this->getLink().'" title="'.$this->getName().'">';
        if(!empty($avatar)) {
            $avatar = str_replace("http://", "https://", $avatar);
            $return .= '<div class="setAvatar menuAvatar" style="background: url(\''.$avatar.'\');"></div>';
        }
        else {
            // @todo Change this to classes to avoid inline css
            $map = array('A'=>'#2D434E','B'=>'#607782','C'=>'#425D69','D'=>'#1B3642','E'=>'#0D2632','F'=>'#343855','G'=>'#6C708E','H'=>'#4C5173',
            'I'=>'#212648','J'=>'#121636','K'=>'#315842','L'=>'#6A937C','M'=>'#49775D','N'=>'#1C4B31','O'=>'#0D3820','P'=>'#7B6C44','Q'=>'#CEBE95',
            'R'=>'#CEBE95','S'=>'#A79566','T'=>'#695728','U'=>'#4F3E12','V'=>'#7B5044','W'=>'#CEA195','X'=>'#A77366','Y'=>'#693528','Z'=>'#4F1F12');
            $firstletter = strtoupper(substr($this->getName(), 0, 1));
            $color = (isset($map[$firstletter]) ? $map[$firstletter] : '#2D434E');
            $return .= '<div class="setAvatar menuAvatar" style="background-color:'. $color .';">'. $firstletter .'</div>';
        }
        return $return .'</a>';
    }

    public function getNetworth($format=false) { // @todo: maybe we want to show if province is in range
        $n = intval($this->get('networth'));
        return ($format ? Format::networth($n) : $n);
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
        $maxExplore = round(Settings::get('max_explore_land') - $this->get('explored_today'));
        return $turnMax < $maxExplore ? $turnMax : $maxExplore;
    }

    public function getMaxSellLand() {
        $freeLand = $this->getFreeLand();
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
            $medals[$id]['next'] = !empty($this->get($id.'_next')) ? intval($this->get($id.'_next')) : 0;
            $medals[$id]['prev'] = !empty($this->get($id.'_prev')) ? intval($this->get($id.'_prev')) : 0;
            if($format == true && isset($medals[$id]['format'])) {
                $medals[$id]['next'] = call_user_func(array('Format', $medals[$id]['format']), $medals[$id]['next']);
                $medals[$id]['prev'] = call_user_func(array('Format', $medals[$id]['format']), $medals[$id]['prev']);
            }
            //Hooks::trigger('get_province_medal', array($id, $medals[$id])); // we might want to work with modifiers
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
     *  Event functions
     */
    public function getGlobalNum($format=false) {
        return intval($this->get('new_global_events'));
    }
    public function getLocalNum($format=false) {
        return intval($this->get('new_events'));
    }
    public function getMessageNum($format=false) {
        return intval($this->get('new_messages'));
    }

    /**
     * Used on dashboard
     */
    public function getIncome($format=false) {
        $finance_multi = ($this->hasStartingBonus('finance') ? Settings::get('startbonus_finance_income_multi') : 1);
        $income = Settings::get('income_money') * $finance_multi;

        $money_production = $this->getResearches('money_production');
        if($money_production['level'] == 1) $income = $money_production['level1_value'] * $finance_multi;
        elseif($money_production['level'] == 2) $income = $money_production['level2_value'] * $finance_multi;

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
        switch($bonustype) {
            case 'offensive': $this->update('turns', $this->getTurns() + 75); break;
            case 'defensive': $this->update('land', $this->getLand() + 3500); break;
            case 'finance': $this->update('money', $this->getMoney() + 400000); break;
            case 'shipping':
                $this->update('land', $this->getLand() + 2500);
                $this->update('money', $this->getMoney() + 250000);
            break;
        }
        $this->update('starting_bonus', $bonustype);
        return true;
    }

    /**
     * Province bank account
     */
    public function getBankAccount() {
        if(static::$bankAccount==false) static::$bankAccount = BankAccount::make($this->id);
        return static::$bankAccount;
    }

    /**
     * Province market orders
     */
    public function getOrders() {
        $orders = get_posts(array('posts_per_page' => -1, 'post_status' => 'publish', 'post_type' => 'market_order', 'author' => $this->id));
        $return = array();
        foreach($orders as $order) {
            $return[] = Order::make($order);
        }
        return $return;
    }

    /**
     * Province Research
     */
    public function getResearches($key=null) {
        $researches = Researches::get();
        foreach($researches as $id => $research) {
            $level = !empty($this->get('level_'.$id)) ? intval($this->get('level_'.$id)) : 0;
            $value = (isset($research['level'.($level+1).'_value']) ? $research['level'.($level+1).'_value'] : 0); // next 'value'
            $description = (isset($research['level'.($level+1)]) ? $research['level'.($level+1)] : 'Unknown research level');

            if($id == 'money_production') {
                $value = Format::money($value * ($this->hasStartingBonus('finance') ? Settings::get('startbonus_money_research_multi') : 1));
            }
            if($id == 'market_discount' && $this->hasStartingBonus('shipping')) {
                $value = $value + Settings::get('startbonus_shipping_research_multi');
            }

            $researches[$id]['level'] = $level;
            $researches[$id]['level_value'] = $value;
            $researches[$id]['level_description'] = str_replace('{value}', $value, $description);
            $researches[$id]['inProgress'] = ($this->get('research_in_progress') == $id ? true : false);
            $researches[$id]['queued'] = ($this->get('queued_research') == $id ?  true : false);
            if($this->hasStartingBonus('defensive')) {
                $researches[$id]['duration'] = $researches[$id]['duration'] * Settings::get('startbonus_defensive_research_time');
            }
            $researches[$id]['nw'] = Format::networth($researches[$id]['duration'] * Settings::get('nw_research'));
            //Hooks::trigger('get_province_research', array($id, $researches[$id])); // we might want to work with modifiers
        }
        return  ($key != null && $researches[$key] ? $researches[$key] : $researches);
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
        $freeLand = $this->getFreeLand();
        $freeSpace = $this->getBuildSpace();
        $units = $this->getUnits();
        $missiles = $this->getMissiles();

        $buildings = Buildings::get();
        foreach($buildings as $id => $building) {
            $buildings[$id]['num'] = (!!$this->get($id) ? intval($this->get($id)) : 0);
            $buildings[$id]['buildprice'] = $building['price']; // Might become cheaper with research/startbonus
            $buildings[$id]['demoprice'] = $building['price'] * Settings::get('demolish_price_multi');
            $buildings[$id]['networthPerUnit'] = round($building['price'] * $building['networth']/100); // of original price!
            $buildings[$id]['maxbuild'] = min(floor($totalMoney / $buildings[$id]['buildprice']), $freeTurns, $freeSpace);
            $occupied = 0;
            if(isset($building['houses'])) {
                foreach($units as $unit) {
                    if($unit['type'] == $building['houses'] || $unit['sectype'] == $building['houses']) $occupied += ($unit['ordered'] + $unit['num']);
                }
                foreach($missiles as $missile) {
                    if($missile['type'] == $building['houses']) $occupied += ($missile['ordered'] + $missile['num']);
                }
            }
            $buildings[$id]['occupied'] = ($occupied > 0 ? ceil($occupied / $building['housing']) : 0);
            $buildings[$id]['maxdemo'] = $buildings[$id]['num'] - $buildings[$id]['occupied'];

            //Hooks::trigger('get_province_building', array($id, $buildings[$id])); // we might want to work with modifiers
            if($this->hasStartingBonus('defensive')) {
                $buildings[$id]['life'] = round($buildings[$id]['life'] * Settings::get('startbonus_defensive_building_life_multi'));
            }
        }

        if ($this->hasResearchMinimalLevel('powerplant_efficiency',1)) {
            $buildings['powerplant']['powerprod'] = $buildings['powerplant']['powerprod'] * 1.5;
            $buildings['powerplant']['life'] = round($buildings['powerplant']['life'] * 1.5);
            $buildings['powerplant']['description'] = 'Produces ' . $buildings['powerplant']['powerprod'] .' power';
            $buildings['advancedpowerplant']['powerprod'] = $buildings['advancedpowerplant']['powerprod'] * 1.5;
            $buildings['advancedpowerplant']['life'] = round($buildings['advancedpowerplant']['life'] * 1.5);
            $buildings['advancedpowerplant']['description'] = 'Produces ' . $buildings['advancedpowerplant']['powerprod'] .' power';
        }

        if($shootdown_chance = $this->getShootdownChance()) {
            $buildings['antimissile']['shootdown_chance'] = $shootdown_chance;
            $buildings['antimissile']['description'] = 'Each Anti-Missile System protects 100m2 of your built land.
                Chance to shoot down missiles is currently '. $shootdown_chance .'%.
                Every Anti-Missile System has a 25% chance to shoot down tomahawk missiles.';
        }

        return ($key != null && $buildings[$key] ? $buildings[$key] : $buildings);
    }

    public function getBuildingsPerTurn() {
        $r = $this->getResearches('engineering_effectiveness');
        switch($r['level']) {
            case 1: return 10; break;
            case 2: return 15; break;
        }
        return 5;
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
     * Get all information of one or all of one type, or all units of this province
     */
    public function getUnits($key=null,$type=null) {

        $units = Units::get();
        foreach($units as $id => $unit) {
            $units[$id]['num'] = (!!$this->get($id.'_owned') ? intval($this->get($id.'_owned')) : 0);
            $units[$id]['ordered'] = (!!$this->get($id.'_ordered') ? intval($this->get($id.'_ordered')) : 0);
            $units[$id]['original_price'] = $unit['price'];
            //Hooks::trigger('get_province_unit', array($id, $units[$id])); // we might want to work with modifiers
            if($this->hasStartingBonus('defensive')) {
                $units[$id]['life'] = $units[$id]['life'] * Settings::get('startbonus_defensive_unit_life_multi');
            }
        }

        return ($key != null && $units[$key] ? $units[$key] : $units);
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
    public function getUnitsNum($key=null) {
        $num = 0;
        foreach(Units::get() as $k => $unit) {
            if(is_null($key) || $k == $key) {
                $num += (!!$this->get($k.'_owned') ? intval($this->get($k.'_owned')) : 0);
            }
        }
        return $num;
    }

    /**
     * Get all information of one or all Missiles of this province
     */
    public function getMissiles($key=null) {
        $missiles = Missiles::get();
        foreach($missiles as $id => $missile) {
            $missiles[$id]['num'] = (!!$this->get($id.'_owned') ? intval($this->get($id.'_owned')) : 0);
            $missiles[$id]['ordered'] = (!!$this->get($id.'_ordered') ? intval($this->get($id.'_ordered')) : 0);
            $missiles[$id]['original_price'] = $missile['price'];
            //Hooks::trigger('get_province_missile', array($id, $missiles[$id])); // we might want to work with modifiers
        }
        return ($key != null && $missiles[$key] ? $missiles[$key] : $missiles);
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
        $satellites = Satellites::get();
        $sat = $this->get('sat_owned');
        foreach($satellites as $id => $satellite) {
            $satellites[$id]['num'] = ($sat == $id ? 1 : 0);
            $satellites[$id]['original_price'] = $satellite['price'];
            $satellites[$id]['status'] = ($id=='stealths' && $this->get('stealth_sat_status')=='active' ? 'active' : '');
            //Hooks::trigger('get_province_sattelite', array($id, $satellites[$id])); // we might want to work with modifiers
            if(!$this->hasResearchMinimalLevel('satellite_construction', 3)) {
                $satellites[$id]['price'] = $satellites[$id]['price'] * Settings::get('satellite_construction_3_price_multi');
            }
        }
        return ($key != null && $satellites[$key] ? $satellites[$key] : $satellites);
    }
    // Acktually gets shortname (header.php)
    public function getSatelliteNum() {
        if(!$this->hasResearchMinimalLevel('satellite_construction', 1)) return 0;
        $sat = $this->get('sat_owned');
        if(empty($sat)) return 0;
        return (!!Satellites::get($sat) ? Satellites::get($sat)['shortname'] : 0);
    }

    /**
     * Get Clan
     */
    public function getClan() {
        return (!empty($this->get('clan_id_user')) ? Clan::make($this->get('clan_id_user')) : false);
    }
    public function isFellowClanMember() {
        if($clan = $this->getClan()) {
            return in_array($this->id, $clan->getMembers());
        }
        return false;
    }

    /**
     * Keep track of turn usage
     */
    public function turn_spread($turntype, $addedturns) {
        $turnSpread = maybe_unserialize($this->get('turn_spread'));
        if(!is_array($turnSpread)) $turnSpread = array();
        if(!isset($turnSpread[$turntype])) $turnSpread[$turntype] = 0;
        $turnSpread[$turntype] += $addedturns;
        $this->update('turn_spread', maybe_serialize($turnSpread));
    }


    public function calculateNw() {}

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

        // calculate unit NW
        $unit_networth = 0;
        foreach($this->getUnits() as $key => $unit) {
            $unit_networth += $unit['num'] * $unit['price'] * ($unit['networth'] / 100);
        }

        // calculate missile NW
        $missile_networth = 0;
        foreach($this->getMissiles() as $key => $missile) {
            $missile_networth += $missile['num'] * $missile['price'] * ($missile['networth'] / 100);
        }

        // calculate building NW (original price!)
        $building_networth = 0;
        foreach ($this->getBuildings() as $key => $building) {
            if($building['num'] == 0) continue;
            $building_networth += $building['num'] * $building['price'] * ($building['networth'] / 100);
        }

        // calculate research NW
        $research_networth = 0;
        foreach ($this->getResearches() as $key => $research) {
            if($research['level']==0) continue;
            $research_networth += $research['duration'] * Settings::get('nw_research') * $research['level'];
        }

        // calculate satellite NW (original price!)
        $sat_networth = 0;
        foreach ($this->getSatellites() as $key => $satellite) {
            if($satellite['num'] == 0) continue;
            $sat_networth += $satellite['num'] * $satellite['original_price'] * Settings::get('nw_sat');
        }

        // calculate land NW
        $land = $this->getLand();
        $land_networth = round($land * Settings::get('nw_land'));

        $totalNW = round($unit_networth+$missile_networth+$building_networth+$research_networth+$sat_networth+$land_networth);
        $this->update('networth', $totalNW);
        $this->update('unit_nw', round($unit_networth));
        $this->update('missile_nw', round($missile_networth));
        $this->update('building_nw', round($building_networth));
        $this->update('research_nw', round($research_networth));
        $this->update('sat_nw', round($sat_networth));
        $this->update('land_nw', round($land_networth));

        $this->calculateFreeLand();

        if($totalNW > $this->get('highest_networth')) $this->update('highest_networth', $totalNW);
        if($land > $this->get('highest_land')) $this->update('highest_land', $land);

        $this->calculatePower();
    }

    /*
    invite(),
    kick(),
    getTrophies(),
    kill(),
    reset(),
    attack(),
    spy()
    */
}