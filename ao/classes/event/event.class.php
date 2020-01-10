<?php

class Event extends PostObject {

    static $data = array(
        'aid' => array(
            'icon' => 'fas fa-hand-holding-usd', 'header' => array('incoming' => 'Aid received', 'outgoing' => 'Aid sent', 'global' => 'Aid report'),
            'title' => array(
                'incoming' => 'You received {money} aid from {attacker}',
                'outgoing' => 'You sent {money} to {defender}',
                'global' => '{defender} received {money} aid from {attacker}',
            ),
        ),
        'nukeprotection' => array(
            'icon' => 'fas fa-umbrella', 'header' => 'Assault protection removed', 'avatar' => 'attacker_id',
            'title' => 'Your protection has been removed',
            'body' => 'You are now able to attack',
        ),
        'research_ready' => array(
            'icon' => 'fas fa-flask', 'header' => 'Research completed',
            'title' => '{research_name} completed',
            'body' => 'You can now start a new research',
        ),
        'air_sea' => array(
            'icon' => 'fas fa-ship', 'header' => 'Air & Sea attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.{clan_points}',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'regular' => array(
            'icon' => 'fas fa-fighter-jet', 'header' => 'Regular attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.{clan_points}',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'ground' => array(
            'icon' => 'fas fa-truck-monster', 'header' => 'Ground attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.{clan_points}',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'missile' => array(
            'icon' => 'fas fa-rocket', 'header' => 'Missile attack report',
            'title' => array(
                'incoming' => '{attacker} launched a {missile_name} and {youdied}{shotdown}{missed}{hit}.', //you shot down the missile
                'outgoing' => 'You launched a missile at {defender} and {shotdown}{killed}{missed}{hit}.{clan_points}',
                'global' => '{attacker} launched a {missile_name} at {defender} and {shotdown}{missed}{hit}{sabotaged}.{clan_points}', //was shotdown
            ),
            'body' => '{attack_body}'
        ),
        'satellite' => array(
            'icon' => 'fas fa-satellite', 'header' => 'Satellite attack report',
            'title' => array(
                'incoming' => '{attacker} used a satellite and {youdied}{sat_missed}{sat_hit}.',
                'outgoing' => 'You fired a Laser Beam Satellite at {defender} and you {killed}{sat_missed}{sat_hit}.{clan_points}', //you killed {defender}
                'global' => '{attacker} fired a satellite at {defender} and {sat_missed}{sat_hit}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'empsat' => array(
            'icon' => 'fas fa-satellite', 'header' => 'EMP missile attack report',
            'title' => array(
                'incoming' => '{attacker} used an EMP satellite and {missed}{hit}.',// your base
                'outgoing' => 'You fired an EMP sat at {defender} and you {missed}{hit}.',// the enemy base
                'global' => '{attacker} fired an EMP satellite at {defender} and {missed}{hit}.',// the base
            ),
            'body' => 'Power decreased by {nw_damage_defender}% for 6 hours',//if winner:
        ),
        'sat_crash' => array(
            'icon' => 'fas fa-satellite', 'header' => 'Satellite crash report', 'avatar' => 'defender_id',
            'title' => 'Your satellite crashed and burned up in the atmosphere.',
            'body' => 'You can now order a new satellite',
        ),
        'empmissile' => array(
            'icon' => 'fas fa-rocket', 'header' => 'EMP missile attack report',
            'title' => array(
                'incoming' => '{attacker} launched an EMP missile and {shotdown}{missed}{hit}.',
                'outgoing' => 'You launched an EMP missile at {defender} and you {missed}{hit}.',
                'global' => '{attacker} launched an EMP missile at {defender} and {shotdown}{missed}{hit}.',
            ),
            'body' => 'Power decreased by 15% for 6 hours',//if winner:
        ),
        'sniper' => array(
            'icon' => 'fas fa-bullseye', 'header' => 'Sniper attack report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}',
                'outgoing' => 'You sent snipers to {defender} and you {won}{lost}',
            ),
            'body' => '{attack_body}'
        ),
        'saboteur' => array(
            'icon' => 'fas fa-bomb', 'header' => 'Saboteur sent',
            'title' => 'You sent a saboteur to {defender} and he was {succesfull}{sabokilled}',
            'body' => '{silos} disabled'
        ),
        'spy' => array(
            'icon' => 'fas fa-binoculars', 'header' => 'Spy infiltration report',
            'title' => '{attacker} sent a {spy}{spyplane}{shot}', // Someone or attacker, killed or shotdown
        ),
        'thief' => array(
            'icon' => 'fas fa-user-ninja', 'header' => 'Thief infiltration report',
            'title' => array(
                'incoming' => '{attacker} sent thieves and {stolemoney}{killedthieves}', // Someone or attacker, you killed X, stole {money}
                'outgoing' => 'You sent {thieves} to {defender} and {stolemoney}{caught}.',
            ),
            'body' => '{money} stolen',
        ),
        'user_kicked' => array(
            'icon' => 'fas fa-shoe-prints', 'header' => 'You were kicked from your clan', 'avatar' => 'attacker_id',
            'title' => '{defender}{kicked}{joined}{left}{clan_points}', // Sorry :-(
        ),
        'killed' => array(
            'icon' => 'fas fa-skull-crossbones', 'header' => 'Kill report',
            'title' => array(
                'incoming' => 'You were killed by {attacker}',
                'outgoing' => 'You killed {defender}',
                'global' => '{attacker} killed {defender}',
            ),
        ),
        'war_declared' => array(
            'icon' => 'fas fa-bomb', 'header' => 'War declared', 'avatar' => 'attacker_clan_id',
            'title' => '{declaring_clan} declared war on {declared_clan}',
            'body' => '{dec_message}',
        ),
        'peace_declared' => array(
            'icon' => 'fas fa-peace', 'header' => 'Peace declared', 'avatar' => 'attacker_clan_id',
            'title' => '{declaring_clan} declared peace on {declared_clan}',
            'body' => '{dec_message}',
        ),
        'user_change' => array(
            'icon' => 'fas fa-shoe-prints', 'header' => 'User change', 'avatar' => 'defender_id',
            'title' => '{defender} {kicked}{joined}{left}{clan_points}',
        ),
        'bonus' => array(
            'icon' => 'fas fa-award', 'header' => 'Bonus received', 'avatar' => 'attacker_clan_id',
            'title' => 'Whoop! We got bonus',
            'body' => 'You can now receive {bonus_money} and {bonus_turns} turns',
        )
        /* TODO:
        - market close - fas fa-shopping-cart
        */
    );

    function __construct($postData=null) {
        parent::__construct($postData);

        $this->eventtype = $this->get('attacktype');
        $this->eventcategory = $this->get('category');
        $this->eventtime = $this->get('time_attacked');
    }

    public static function create($data, $notify=array()) {

        // make wp post
        $current_user = CurrentUser::make();
        $args = array_merge(array(
            'post_title' => $data['title'], 'post_status' => 'publish', 'post_type' => 'event_local',
            'post_author' => (!isset($data['author']) && !!$current_user ? $current_user->get('id') : 0),
            'attacker_id' => (!isset($data['attacker_id']) && !!$current_user ? $current_user->get('id') : 0),
            'time_attacked' => current_time('timestamp')
        ), $data);
        if(isset($data['author'])) $args['post_author'] = $data['author'];
        $eventId = wp_insert_post($args);

        // fill wp post
        foreach($args as $key => $value) {
            if(in_array($key, array('title','author'))) continue;
            if($key == 'type') $key = 'attacktype';
            update_field($key, $value, $eventId);
        }

        // let people know
        if(!is_array($notify)) $notify = array($notify);
        foreach($notify as $member_id) {
            $member = Province::make($member_id);
            if(isset($data['send']) && $data['send']=='global') $member->update('new_global_events', $member->get('new_global_events') + 1);
            else  $member->update('new_events', $member->get('new_events') + 1);
        }

        return Event::make($eventId);
    }

    public static function getPossibleEventTypes($category='') {
        $eventTypes = array('aid','air_sea','empmissile', 'empsat', 'ground', 'killed', 'missile', 'regular', 'satellite');
        switch($category) {
            case 'incoming': $eventTypes = array_merge($eventTypes, array('thief','nukeprotection','research_ready','user_kicked','sat_crash','sniper','spy', 'bonus')); break;
            case 'outgoing': $eventTypes = array_merge($eventTypes, array('thief', 'user_kicked', 'sniper','saboteur')); break;
            case 'global':   $eventTypes = array_merge($eventTypes, array('war_declared', 'peace_declared', 'user_change')); break;
        }
        return $eventTypes;
    }

    public function getEventTypeData() {
        return (isset(self::$data[$this->eventtype]) ? self::$data[$this->eventtype] : array());
    }

    public function getIcon($format=false) {
        $icon = '';
        $et = $this->getEventTypeData();
        if(isset($et['icon'])) $icon = $et['icon'];
        return ($format==true ? '<i class="'.$icon.'"></i>': $icon);
    }

    public function getHeader($format=false) {
        $reportHeader = '';
        $et = $this->getEventTypeData();
        if(isset($et['header'])) {
            if(is_array($et['header']) && isset($et['header'][$this->eventcategory])) $reportHeader = $et['header'][$this->eventcategory];
            else $reportHeader = $et['header'];
        }
        return $reportHeader;
    }

    public function getAvatar($format=false) {
        $avatar = '<div class="eventAvatar setAvatar letter">
            <img src="'.get_stylesheet_directory_uri().'/img/avatars/_.png">
        </div>';

        // Sabotaged silo's
        if($this->eventcategory == 'global' && empty($this->get('defender_id'))) return $avatar;

        // Thief stole money, don't know who
        if($this->eventcategory == 'incoming' && $this->eventtype == 'thief' && $this->get('winner_id') != $this->get('defender_id')) return $avatar;

        // Spy
        if($this->eventcategory == 'incoming' && $this->eventtype == 'spy') {
            if(
                $this->get('winner_id') == $this->get('attacker_id') &&
                ($this->get('show_spy_sender') == 'no' || $this->get('event_spy_type') == 'spyplane'
            )) return $avatar;
        }

        $avatar_user = $avatar_clan = false;
        if($this->eventcategory == 'outgoing') $avatar_user = (!empty($this->get('defender_id')) ? $this->get('defender_id') : false);
        else $avatar_user = (!empty($this->get('attacker_id')) ? $this->get('attacker_id') : false);

        $et = $this->getEventTypeData();
        if(isset($et['avatar'])) {
            if(in_array($et['avatar'], array('attacker_clan_id','defender_clan_id'))) {
                if($clan = Clan::make($this->get($et['avatar']))) return $clan->getAvatar('eventAvatar');
            } else $avatar_user = $this->get($et['avatar']);
        }

        if(!empty($avatar_user)) {
            $avatar = User::make($avatar_user)->getAvatar('eventAvatar');
        }
        return $avatar;
    }

    public function getTitle($format=false) {
        $title = '';
        $et = $this->getEventTypeData();
        if(isset($et['title'])) {
            if(is_array($et['title']) && isset($et['title'][$this->eventcategory])) $title = $et['title'][$this->eventcategory];
            else $title = $et['title'];
        }
        if(empty($title)) return '';
        return $this->parseEventVariables($title, $format);
    }

    public function getBody($format=false) {
        $body = '';
        $et = $this->getEventTypeData();
        if(isset($et['body'])) $body = $et['body'];
        if(empty($body)) return '';

        // This is heavy
        if(strpos($body,'{attack_body}') !== false) {
            $body = strtr($body, array('{attack_body}' => $this->getAttackBody($format)));
        }

        if(in_array($this->eventtype, array('empmissile','empsat')) && $this->get('winner_id') != $this->get('attacker_id')) {
            $body = ''; // Missed, no effect
        }

        return $this->parseEventVariables($body, $format);
    }

    public function getAttackBody($format=false) {

        $buildings = Buildings::get();
        $units = Units::get();

        $body = '';
        // Attackmode, target and morale
        if($this->eventcategory == 'outgoing') {
            $attackSettings = array();
            if(!empty($this->get('attackmode'))) $attackSettings[] = 'attackmode: '.($format == true ? '<em>'. $this->get('attackmode') .'</em>' : $this->get('attackmode'));
            if(!empty($this->get('maintarget'))) $attackSettings[] = 'maintarget: '.($format == true ? '<em>'. $this->get('maintarget') .'</em>' : $this->get('maintarget'));
            if(!empty($this->get('moralecost'))) $attackSettings[] = 'morale: '.($format == true ? '<em>'. Format::morale($this->get('moralecost')) .'</em>' : $this->get('moralecost'));
            if(count($attackSettings)) $body .= ($format == true ? '<p>'. implode(', ', $attackSettings) .'</p>' : implode(', ', $attackSettings).'. ');
        }

        // Land and money
        $money = ($format == true ? '<strong>'.Format::money($this->get('money_lost')).'</strong>' : $this->get('money_lost'));
        $land = ($format == true ? '<strong>'.Format::land($this->get('land_lost')).'</strong>' : $this->get('land_lost'));
        $tomahawkHit = (!empty($this->get('tomahawk_hit')) ? $this->get('tomahawk_hit') : 0);
        $body .= ($format==true?'<p>':'').'In this attack '. $land .' and '. $money .' was stolen';
        $body .= ($tomahawkHit>0 ? ', ' . Format::plural($tomahawkHit, 'tomahawk') .' hit the base' : '') . ($format==true?'</p>':'. ');

        // Attacker losses
		$tomahawkDown = (!empty($this->get('tomahawk_down')) ? $this->get('tomahawk_down') : 0);
        $unit_losses = array();
        $att_num_losses = (!empty($this->get('att_total_units_lost')) ? $this->get('att_total_units_lost') : 0);
        $att_lost = (!empty($this->get('attacker_lost')) ? maybe_unserialize($this->get('attacker_lost')) : array());
        foreach ($att_lost as $item) {
            $key = array_keys($item)[1];
            $num = array_values($item)[1];
            if($item['type']=='unit' && isset($units[$key])) $unit_losses[] = $units[$key]['normalname'].': '.$num;
        }
        $body .= ($format==true?'<p>':''). ($format==true && (count($att_lost)>0 || $tomahawkDown>0)?'<strong>':'');
        $body .= 'Attacker losses: ' . Format::plural($att_num_losses, 'unit');
        $body .= ($tomahawkDown>0?', '. Format::plural($tomahawkDown, 'tomahawk').' shot down':'');
        $body .= ($format==true && (count($att_lost)>0 || $tomahawkDown>0)? '</strong><br>':'');
        $body .= implode(', ', $unit_losses) . ($format==true?'</p>':'. ');

        // Defender losses
        $bld_losses = $unit_losses = array();
        $def_unit_num_losses = (!empty($this->get('def_total_units_lost')) ? $this->get('def_total_units_lost') : 0);
        $def_buildings_num_lost = (!empty($this->get('total_buildings_lost')) ? $this->get('total_buildings_lost') : 0);
        $def_lost = (!empty($this->get('defender_lost')) ? maybe_unserialize($this->get('defender_lost')) : array());
        foreach ($def_lost as $item) {
            $key = array_keys($item)[1];
            $num = array_values($item)[1];
            if($item['type']=='unit' && isset($units[$key])) $unit_losses[] = $units[$key]['normalname'].': '.$num;
            else if($item['type']=='bld' && isset($buildings[$key])) $bld_losses[] = $buildings[$key]['normalname'].': '.$num;
        }
        $body .= ($format==true?'<p>':''). ($format==true&&count($def_lost)>0?'<strong>':'').'Defender losses: ' . Format::plural($def_unit_num_losses, 'unit');
        $body .= ' and ' . Format::plural($def_buildings_num_lost, 'building').''. (count($def_lost)>0 ? ($format==true?'</strong><br>':': ') : '');
        $body .= implode(', ', $unit_losses) . (count($unit_losses)&&$format==true>0?'<br>':' ') . implode(', ', $bld_losses) . ($format==true?'</p>':'. ');

        return $body;
    }

    public function parseEventVariables($str='', $format=false) {

        // Often used
        $attacker_id = $this->get('attacker_id');
        $attacker = (!empty($attacker_id) ? User::make($attacker_id) : false);
        $attacker_name = ($attacker ? ($format==true ? $attacker->getLink(true) : $attacker->getName()) : 'unknown');
        $defender_id = $this->get('defender_id');
        $defender = (!empty($defender_id) ? User::make($defender_id) : false);
        $defender_name = ($defender ? ($format==true ? $defender->getLink(true) : $defender->getName()) : 'unknown');
        $winner_id = $this->get('winner_id');
        $money = ($format == true ? '<strong>'.Format::money($this->get('money_lost')).'</strong>' : $this->get('money_lost'));
        $attacker_clan = (!empty($this->get('attacker_clan_id')) ? Clan::make($this->get('attacker_clan_id')) : false);
        $defender_clan = (!empty($this->get('defender_clan_id')) ? Clan::make($this->get('defender_clan_id')) : false);
        if(!$attacker_clan && !!$attacker) $attacker_clan = $attacker->getProvince()->getClan();
        if(!$defender_clan && !!$defender) $defender_clan = $defender->getProvince()->getClan();

        // Default
        $replace = array(
            '{attacker}' => ($attacker_clan ? $attacker_clan->getTag($format) .' ' : '') . $attacker_name,
            '{defender}' => ($defender_clan ? $defender_clan->getTag($format) .' ' : '') . $defender_name,
            '{money}' => $money,
            '{spy}' => ($this->get('event_spy_type') == 'spy' ? 'spy' : ''),
            '{spyplane}' => ($this->get('event_spy_type') == 'spyplane' ? 'spyplane' : ''),
            '{shot}' => ($winner_id == $defender_id ? ' and you '.($this->get('event_spy_type') == 'spy' ? ' killed it' : ' shot it down') : ''),
            '{defender_points}' => ($this->get('defender_points')>0 ? ' '.$this->get('defender_points').' clan point(s) gained for successful base defense.' : ''),
            '{youdied}' => ($this->get('status_defender') == 'death' ? ($format == true ? '<strong>you died</strong>' : 'you died') : ''), // incoming
            '{killed}' => ($this->get('status_defender') == 'death' ? 'killed this player' : ''),// outgoing
            '{kicked}' => 'Kicked from '.($attacker_clan ? $attacker_clan->getLink($format) : 'unknown').' by '.$attacker_name,
            '{declaring_clan}' => ($attacker_clan ? ($format==true ? $attacker_clan->getLink(true) : $attacker_clan->getName())  : ''),
            '{declared_clan}' => ($defender_clan ? ($format==true ? $defender_clan->getLink(true) : $defender_clan->getName()) : ''),
            '{dec_message}' => $this->get('dec_message'),
            '{bonus_money}' => ($format == true ? Format::money($this->get('bonus_money')) : $this->get('bonus_money')),
            '{bonus_turns}' => ($format == true ? Format::turns($this->get('bonus_turns')) : $this->get('bonus_turns')),
            '{nw_damage_defender}' => $this->get('nw_damage_defender'),
            '{succesfull}' => ($winner_id != $defender_id ? 'not killed' : ''),
            '{sabokilled}' => ($winner_id == $defender_id ? 'killed in action' : ''),
            '{silos}' => (!empty($this->get('silos')) ? Format::plural($this->get('silos'), 'silo') : 'no silos'),
        );

        // Incoming
        if($this->eventcategory == 'incoming') {
            $replace = array_merge($replace, array(
                '{won}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id ? ($format == true ? '<strong>won</strong>' : 'won').' the battle' : ''),
                '{lost}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? ($format == true ? '<strong>lost</strong>' : 'lost').' the battle' : ''),
                '{shotdown}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') == 'shotdown' ? 'you shot down the missile' : ''),
                '{missed}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') != 'shotdown' ? ($format == true ? '<strong>missed</strong>' : 'missed').' your base' : ''),
                '{hit}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? ($format == true ? '<strong>hit</strong>' : 'hit').' your base' : ''),
                '{sat_hit}' => ($this->get('outcome') == 'success' ? ($format == true ? '<strong>hit</strong>' : 'hit').' your base' : ''),
                '{sat_missed}' => ($this->get('outcome') == 'failure' ? ($format == true ? '<strong>missed</strong>' : 'missed').' your base' : ''),
                '{killedthieves}' => ($winner_id == $defender_id ? 'you killed '. Format::plural($this->get('thiefs_lost'), 'thief', 'thieves') : ''),
                '{stolemoney}' => ($winner_id == $attacker_id ? 'stole '.$money : ''),
            ));

            // Thief & spy may confuscate attacker
            if($this->eventtype == 'thief' && $winner_id != $defender_id) $replace['{attacker}'] = 'Someone';
            if($this->eventtype == 'spy' && $winner_id != $defender_id) {
                if($this->get('show_spy_sender') == 'no' || $this->get('event_spy_type') == 'spyplane') $replace['{attacker}'] = 'Someone';
            }
        }

        // Outgoing
        if($this->eventcategory == 'outgoing') {
            $replace = array_merge($replace, array(
                '{won}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? 'won the battle' : ''),
                '{lost}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id ? 'lost the battle' : ''),
                '{clan_points}' => ($winner_id == $attacker_id && $this->get('clan_points') > 0 ? ' '. Format::plural($this->get('clan_points'), 'clan point') .' gained.' : ''),
                '{shotdown}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') == 'shotdown' ? ' it was shot down' : ''),
                '{missed}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') != 'shotdown' ? 'you missed the enemy base' : ''),
                '{hit}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? 'you hit the enemy base' : ''),
                '{sat_hit}' => ($this->get('outcome') == 'success' ? ($format == true ? '<strong>hit</strong>' : 'hit').' the base' : ''),
                '{sat_missed}' => ($this->get('outcome') == 'failure' ? ($format == true ? '<strong>missed</strong>' : 'missed').' the base' : ''),
                '{thieves}' => ($this->get('thiefs_lost') > 0 ? Format::plural($this->get('thiefs_lost'), 'thief', 'thieves') : 'thieves'),
                '{stolemoney}' => ($winner_id != $defender_id ? 'stole '.$money : ''),
                '{caught}' => ($winner_id == $defender_id ? 'but you were caught' : ''),
            ));
        }

        // Global
        if($this->eventcategory == 'global') {
            $clan_members = array();
            $current_user = CurrentUser::make();
            if($clan = $current_user->getProvince()->getClan()) {
                $clan_members = $clan->getMembers(); // id's
            }
            if(in_array($attacker_id, $clan_members)) { // attack by clanmember
                $replace = array_merge($replace, array(
                    '{won}' => ($winner_id == $attacker_id ? 'won the battle' : ''),
                    '{lost}' => ($winner_id == $defender_id ? 'lost the battle' : ''),
                    '{clan_points}' => ($winner_id == $attacker_id && $this->get('clan_points') > 0 ? ' '. Format::plural($this->get('clan_points'), 'clan point') .' gained.' : ''),
                    '{hit}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $attacker_id ? 'hit the enemy base' : ''),
                    '{missed}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $defender_id ? 'missed the enemy base' : ''),
                ));
            }

            if(in_array($defender_id, $clan_members)) { // defense by clan member
                $replace = array_merge($replace, array(
                    '{won}' => ($winner_id == $defender_id ? 'lost the battle' : ''),
                    '{lost}' => ($winner_id == $attacker_id ? 'won the battle' : ''),
                    '{clan_points}' => ($winner_id == $defender_id && $this->get('defender_points') > 0 ? ' '. Format::plural($this->get('defender_points'), 'clan point') .' gained for successful base defense.' : ''),
                    '{hit}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $attacker_id ? 'hit the base' : ''),
                    '{missed}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $defender_id ? 'missed the base' : ''),
                ));
            }

            $replace = array_merge($replace, array(
                '{shotdown}' => ($this->get('shotdown') == 'shotdown' ? 'it was shot down' : ''),
                '{kicked}' => ($this->get('outcome') == 'kicked' ? 'was kicked by '.$attacker_name : ''),
                '{joined}' => ($this->get('outcome') == 'joined' ? 'joined your clan' : ''),
                '{left}' => ($this->get('outcome') == 'left' ? 'left your clan' : ''),
                '{sat_hit}' => ($this->get('outcome') == 'success' ? ($format == true ? '<strong>hit</strong>' : 'hit').' the base' : ''),
                '{sat_missed}' => ($this->get('outcome') == 'failure' ? ($format == true ? '<strong>missed</strong>' : 'missed').' the base' : ''),
            ));

            if($this->eventtype == 'user_change' && in_array($this->get('outcome'), array('kicked','left'))) {
                $replace['{clan_points}'] = ', ' .  Format::plural($this->get('clan_points'), 'clan point') .' lost.';
            }

            // Sabotaged silo's
            $replace['{sabotaged}'] = (empty($defender_id) ? ' it was sabotaged' : '');
            if(empty($defender_id)) $replace['{defender}'] = 'Someone';
        }

        // Some actions cost too much memory to do on every event
        if($this->eventtype == 'research_ready') {
            $rs = Researches::get($this->get('outcome'));
            $replace['{research_name}'] = ($rs ? $rs['name'] : '');
        }
        if($this->eventtype =='missile') {
            $ms = Missiles::get($this->get('missile_type'));
            $replace['{missile_name}'] = ($ms ? $ms['normalname'] : '');
        }

        return strtr($str, $replace);
    }




    public function getCol1($format=false) {
        return ($format == true ? '<span title="'.date('d M Y H:i:s', $this->eventtime).'">'.Format::time_diff($this->eventtime).' ago</span>' : $this->eventtime);
    }

    public function getCol2($format=false) {
        $defLost = $this->get('nw_damage_defender');
        return ($defLost !== false ? ($format == true ? 'Defender NW lost: ' . Format::networth($defLost) : intval($defLost)) : '');
    }

    public function getCol3($format=false) {
        $attLost = $this->get('nw_damage_attacker');
        return ($attLost !== false ? ($format == true ? 'Attacker NW lost: ' . Format::networth($attLost) : intval($attLost)) : '');
    }

    public function getCol4($format=false) {
        $land_lost = $this->get('land_lost');
        return ($land_lost !== false ? ($format == true ? 'Land stolen: ' . Format::land($land_lost) : intval($land_lost)) : '');
    }
}