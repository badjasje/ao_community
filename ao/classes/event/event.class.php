<?php

class Event extends PostObject {

    static $data = array(
        'aid' => array(
            'icon' => 'flaticon-compass', 'header' => array('incoming' => 'Aid received', 'outgoing' => 'Aid sent', 'global' => 'Aid report'),
            'title' => array(
                'incoming' => 'You received <strong>{money}</strong> aid from {attacker}',
                'outgoing' => 'You sent <strong>{money}</strong> to {defender}',
                'global' => '{defender} received <strong>{money}</strong> aid from {attacker}',
            ),
        ),
        'nukeprotection' => array(
            'icon' => 'flaticon-compass', 'header' => 'Protection removed', 'avatar' => 'attacker_id',
            'title' => 'Your protection has been removed',
            'body' => 'You are now able to attack',
        ),
        'research_ready' => array(
            'icon' => 'flaticon-compass', 'header' => 'Research completed',
            'title' => '{research_name} completed',
            'body' => 'You can now start a new research',
        ),
        'air_sea' => array(
            'icon' => 'flaticon-ship', 'header' => 'Air & Sea attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'regular' => array(
            'icon' => 'flaticon-fighter-plane', 'header' => 'Regular attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'ground' => array(
            'icon' => 'flaticon-tank', 'header' => 'Ground attack battle report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}', //won/lost the battle
                'outgoing' => 'You attacked {defender} and you {killed}{won}{lost}.',
                'global' => '{attacker} attacked {defender} and {won}{lost}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'missile' => array(
            'icon' => 'flaticon-radioactive', 'header' => 'Missile attack report',
            'title' => array(
                'incoming' => '{attacker} launched a {missile_name} and {youdied}{shotdown}{missed}{hit}.', //you shot down the missile
                'outgoing' => 'You launched a missile at {defender} and {shotdown}{killed}{missed}{hit}.',
                'global' => '{attacker} launched a {missile_name} at {defender} and {shotdown}{missed}{hit}.{clan_points}', //was shotdown
            ),
            'body' => '{attack_body}'
        ),
        'satellite' => array(
            'icon' => 'flaticon-objective', 'header' => 'Satellite attack report',
            'title' => array(
                'incoming' => '{attacker} used a satellite and {youdied}{missed}{hit}.',
                'outgoing' => 'You fired a Laser Beam Satellite at {defender} and you {killed}{missed}{hit}.', //you killed {defender}
                'global' => '{attacker} fired a satellite at {defender} and {missed}{hit}.{clan_points}',
            ),
            'body' => '{attack_body}'
        ),
        'empsat' => array(
            'icon' => 'flaticon-objective', 'header' => 'EMP missile attack report',
            'title' => array(
                'incoming' => '{attacker} used an EMP satellite and {missed}{hit}.',// your base
                'outgoing' => 'You fired an EMP sat at {defender} and you {missed}{hit}.',// the enemy base
                'global' => '{attacker} fired an EMP satellite at {defender} and {missed}{hit}.',// the base
            ),
            'body' => 'Power decreased by {nw_damage_defender}% for 6 hours',//if winner:
        ),
        'sat_crash' => array(
            'icon' => 'flaticon-objective', 'header' => 'Satellite crash report', 'avatar' => 'defender_id',
            'title' => 'Your satellite crashed and burned up in the atmosphere.',
            'body' => 'You can now order a new satellite',
        ),
        'empmissile' => array(
            'icon' => 'flaticon-objective', 'header' => 'EMP missile attack report',
            'title' => array(
                'incoming' => '{attacker} launched an EMP missile and {shotdown}{missed}{hit}.',
                'outgoing' => 'You launched an EMP missile at {defender} and you {missed}{hit}.',
                'global' => '{attacker} launched an EMP missile at {defender} and {shotdown}{missed}{hit}.',
            ),
            'body' => 'Power decreased by 15% for 6 hours',//if winner:
        ),
        'sniper' => array(
            'icon' => 'flaticon-bullet', 'header' => 'Sniper attack report',
            'title' => array(
                'incoming' => 'You were attacked by {attacker} and {youdied}{won}{lost}.{defender_points}',
                'outgoing' => 'You sent snipers to {defender} and you {won}{lost}',
            ),
            'body' => '{attack_body}'
        ),
        'spy' => array(
            'icon' => 'flaticon-fighter-plane-1', 'header' => 'Spy infiltration report',
            'title' => '{attacker} sent a {spy}{spyplane}{shot}', // Someone or attacker, killed or shotdown
        ),
        'thief' => array(
            'icon' => 'flaticon-secret-agent', 'header' => 'Thief infiltration report',
            'title' => array(
                'incoming' => '{attacker} sent thieves and {stolemoney}{killedthieves}', // Someone or attacker, you killed X, stole {money}
                'outgoing' => 'You sent {thieves} to {defender} and {stolemoney}{caught}.',
            ),
            'body' => '{money} stolen',
        ),
        'user_kicked' => array(
            'icon' => 'flaticon-boots', 'header' => 'You were kicked from your clan', 'avatar' => 'attacker_id',
            'title' => '{defender}{kicked}{joined}{left}{clan_points}', // Sorry :-(
        ),
        'killed' => array(
            'icon' => 'flaticon-badge', 'header' => 'You died, Kill report, Kill report',
            'title' => array(
                'incoming' => 'You were killed by {attacker}',
                'outgoing' => 'You killed {defender}',
                'global' => '{attacker} killed {defender}',
            ),
        ),
        'war_declared' => array(
            'icon' => 'flaticon-star', 'header' => 'War declared',
            'title' => '{declaring_clan} declared war on {declared_clan}',
            'body' => '{dec_message}',
        ),
        'peace_declared' => array(
            'icon' => 'flaticon-star', 'header' => 'Peace declared',
            'title' => '{declaring_clan} declared peace on {declared_clan}',
            'body' => '{dec_message}',
        ),
        'user_change' => array(
            'icon' => 'flaticon-soldier', 'header' => 'User change', 'avatar' => 'defender_id',
            'title' => '{defender} {kicked}{joined}{left}{clan_points}',
        ),
        /* TODO:
        - clanbonus
        - market close?
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
            'post_title' => $data['title'], 'post_status' => 'publish', 'post_type' => 'event_local',  'post_title' => '',
            'post_author' => $current_user->get('id'), 'attacker_id' => $province->get('id'), 'time_attacked' => current_time('timestamp')
        ), $data);
        if(isset($data['author'])) $args['post_author'] = $data['author'];
        $eventId = wp_insert_post($args);

        // fill wp post
        foreach($data as $key => $value) {
            if(in_array($key, array('title','author'))) continue;
            if($key == 'type') $key = 'attacktype';
            update_field($key, $value, $eventId);
        }

        // let people know
        if(!is_array($notify)) $notify = array($notify);
        foreach($notify as $member_id) {
            $member = Province::make($member_id);
            if(!!$member) $member->update('new_global_events', $member->get('new_global_events') + 1);
        }

        return Event::make($eventId);
    }

    public static function getPossibleEventTypes($category='') {
        $eventTypes = array('aid','air_sea','empmissile', 'empsat', 'ground', 'killed', 'missile', 'regular', 'satellite');
        switch($category) {
            case 'incoming': $eventTypes = array_merge($eventTypes, array('thief','nukeprotection','research_ready','user_kicked','sat_crash','sniper','spy')); break;
            case 'outgoing': $eventTypes = array_merge($eventTypes, array('thief', 'user_kicked', 'sniper')); break;
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
        $avatar = '<div class="clan_avatar smallAvatar eventAvatar">?</div>';

        // Sabotaged silo's
        if($this->eventcategory == 'global' && empty($this->get('defender_id'))) return $avatar;

        // Thief stole money, don't know who
        if($this->eventcategory == 'incoming' && $this->eventtype == 'thief' && $this->get('winner_id') != $this->get('defender_id')) return $avatar;

        // Spy
        if($this->eventcategory == 'incoming' && $this->eventtype == 'spy') {
            if($this->get('show_spy_sender') == 'no' || $this->get('event_spy_type') == 'spyplane') return $avatar;
        }

        $avatar_user = false;
        if($this->eventcategory == 'outgoing') $avatar_user = (!empty($this->get('defender_id')) ? $this->get('defender_id') : false);
        else $avatar_user = (!empty($this->get('attacker_id')) ? $this->get('attacker_id') : false);

        $et = $this->getEventTypeData();
        if(isset($et['avatar'])) $avatar_user = $this->get($et['avatar']);

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

        return $this->parseEventVariables($body, $format);
    }

    public function getAttackBody($format=false) {

        $buildings = Buildings::get();
        $units = Units::get();

        $body = '';
        // Attackmode, target and morale
        if($this->eventcategory == 'outgoing') {
            $attackSettings = array();
            if(!empty($this->get('attackmode'))) $attackSettings[] = 'attackmode: <em>'. $this->get('attackmode') .'</em>';
            if(!empty($this->get('maintarget'))) $attackSettings[] = 'maintarget: <em>'. $this->get('maintarget') .'</em>';
            if(!empty($this->get('moralecost'))) $attackSettings[] = 'morale: <em>'. Format::morale($this->get('moralecost')) .'</em>';
            if(count($attackSettings)) $body .= '<p>'. implode(', ', $attackSettings) .'</p>';
        }

        // Land and money
        $money = ($format == true ? Format::money($this->get('money_lost')) : $this->get('money_lost'));
        $land = ($format == true ? Format::land($this->get('land_lost')) : $this->get('land_lost'));
        $tomahawkHit = (!empty($this->get('tomahawk_hit')) ? $this->get('tomahawk_hit') : 0);
        $body .= '<p>In this attack <strong>'. $land .'</strong> and <strong>'. $money .'</strong> was stolen';
        $body .= ($tomahawkHit>0 ? ', ' . Format::plural($tomahawkHit, 'tomahawk') .' hit the base' : '') . '</p>';

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
        $body .= '<p>'. (count($att_lost)>0 || $tomahawkDown>0?'<strong>':'').'Attacker losses: ' . Format::plural($att_num_losses, 'unit');
        $body .= ($tomahawkDown>0?', '. Format::plural($tomahawkDown, 'tomahawk').' shot down':'');
        $body .= (count($att_lost)>0 || $tomahawkDown>0?'</strong><br>':'').implode(', ', $unit_losses) . '</p>';

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
        $body .= '<p>'.(count($def_lost)>0?'<strong>':'').'Defender losses: ' . Format::plural($def_unit_num_losses, 'unit');
        $body .= ' and ' . Format::plural($def_buildings_num_lost, 'building').''.(count($def_lost)>0?'</strong><br>':'');
        $body .= implode(', ', $unit_losses) . (count($unit_losses)>0?'<br>':'') . implode(', ', $bld_losses) . '</p>';

        return $body;
    }

    public function parseEventVariables($str='', $format=false) {

        // Often used
        $attacker_id = $this->get('attacker_id');
        $attacker = (!empty($attacker_id) ? User::make($attacker_id) : false);
        $attacker_name = ($attacker ? $attacker->getLink($format) : 'unknown');
        $defender_id = $this->get('defender_id');
        $defender = (!empty($defender_id) ? User::make($defender_id) : false);
        $defender_name = ($defender ? $defender->getLink($format) : 'unknown');
        $winner_id = $this->get('winner_id');
        $money = ($format == true ? Format::money($this->get('money_lost')) : $this->get('money_lost'));
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
            '{shot}' => ($winner_id == $defender_id ? 'and you '.($this->get('event_spy_type') == 'spy' ? 'killed it' : 'shot it down') : ''),
            '{defender_points}' => ($this->get('defender_points')>0 ? $this->get('defender_points').' clan point(s) gained for successful base defense.' : ''),
            '{youdied}' => ($this->get('status_defender') == 'death' ? ($format == true ? '<strong>you died</strong>' : 'you died') : ''), // incoming
            '{killed}' => ($this->get('status_defender') == 'death' ? 'killed this player' : ''),// outgoing
            '{kicked}' => 'Kicked from '.($attacker_clan ? $attacker_clan->getLink($format) : 'unknown').' by '.$attacker_name,
            '{declaring_clan}' => ($attacker_clan ? $attacker_clan->getLink($format) : ''),
            '{declared_clan}' => ($defender_clan ? $defender_clan->getLink($format) : ''),
            '{dec_message}' => $this->get('dec_message'),
        );

        // Incoming
        if($this->eventcategory == 'incoming') {
            $replace = array_merge($replace, array(
                '{won}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id ? '<strong>won</strong> the battle' : ''),
                '{lost}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? '<strong>lost</strong> the battle' : ''),
                '{shotdown}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') == 'shotdown' ? 'you shot down the missile' : ''),
                '{missed}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') != 'shotdown' ? '<strong>missed</strong> your base' : ''),
                '{hit}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? '<strong>hit</strong> your base' : ''),
                '{killedthieves}' => ($winner_id == $defender_id ? 'you killed '. Format::plural($this->get('thiefs_lost'), 'thief', 'thieves') : ''),
                '{stolemoney}' => ($winner_id == $attacker_id ? 'stole '.$money : ''),
            ));

            // Thief & spy may confuscate attacker
            if($this->eventtype == 'thief' && $winner_id != $defender_id) $replace['{attacker}'] = 'Someone';
            if($this->eventtype == 'spy') {
                if($this->get('show_spy_sender') == 'no' || $this->get('event_spy_type') == 'spyplane') $replace['{attacker}'] = 'Someone';
            }
        }

        // Outgoing
        if($this->eventcategory == 'outgoing') {
            $replace = array_merge($replace, array(
                '{won}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? 'won the battle' : ''),
                '{lost}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id ? 'lost the battle' : ''),
                '{shotdown}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') == 'shotdown' ? ' it was shot down' : ''),
                '{missed}' => ($this->get('status_defender') != 'death' && $winner_id == $defender_id && $this->get('shotdown') != 'shotdown' ? 'you missed the enemy base' : ''),
                '{hit}' => ($this->get('status_defender') != 'death' && $winner_id == $attacker_id ? 'you hit the enemy base' : ''),
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
                    '{clan_points}' => ($winner_id == $attacker_id && $this->get('clan_points') > 0 ? Format::plural($this->get('clan_points'), 'clan point') .' gained.' : ''),
                    '{hit}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $attacker_id ? 'hit the enemy base' : ''),
                    '{missed}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $defender_id ? 'missed the enemy base' : ''),
                ));
            }

            if(in_array($defender_id, $clan_members)) { // defense by clan member
                $replace = array_merge($replace, array(
                    '{won}' => ($winner_id == $defender_id ? 'lost the battle' : ''),
                    '{lost}' => ($winner_id == $attacker_id ? 'won the battle' : ''),
                    '{clan_points}' => ($winner_id == $defender_id && $this->get('defender_points') > 0 ? Format::plural($this->get('defender_points'), 'clan point') .' gained for successful base defense.' : ''),
                    '{hit}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $attacker_id ? 'hit the base' : ''),
                    '{missed}' => ($this->get('shotdown') != 'shotdown' && $winner_id == $defender_id ? 'missed the base' : ''),
                ));
            }

            $replace = array_merge($replace, array(
                '{shotdown}' => ($this->get('shotdown') == 'shotdown' ? 'it was shot down' : ''),
                '{kicked}' => ($this->get('outcome') == 'kicked' ? 'was kicked by '.$attacker_name : ''),
                '{joined}' => ($this->get('outcome') == 'joined' ? 'joined your clan' : ''),
                '{left}' => ($this->get('outcome') == 'left' ? 'left your clan' : ''),
            ));

            if($this->eventtype == 'user_change' && in_array($this->get('outcome'), array('kicked','left'))) {
                $replace['{clan_points}'] = ', ' .  Format::plural($this->get('clan_points'), 'clan point') .' gained.';
            }

            // Sabotaged silo's
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