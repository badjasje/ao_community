<?php
class Clan extends PostObject {

    public static $cache = 'clans';
    public static $wp_post_type = 'clan';

    function __construct($postData=null) {
        parent::__construct($postData);
        if(isset($this->id)) {
            $this->setPropertiesFromArray(array_merge(
                array('link' => get_permalink($this->id), 'name' => $this->get('post_title') )
            ));
        }
    }

    public function isFull() {
        return count($this->getMembers()) >= Settings::get('clan_member_num');
    }

    public function isMember($user_id=false) {
        $user_id = (!$user_id ? CurrentUser::make()->get('id') : $user_id);
        return in_array($user_id, $this->getMembers());
    }
    public function isCLT($user_id=false) { // is either CL or CT
        $user_id = (!$user_id ? CurrentUser::make()->get('id') : $user_id);
        return in_array($user_id, $this->getCLTs());
    }
    public function isCL($user_id=false) {
        $user_id = (!$user_id ? CurrentUser::make()->get('id') : $user_id);
        return $user_id == $this->getLeader();
    }
    public function isCT($user_id=false) {
        $user_id = (!$user_id ? CurrentUser::make()->get('id') : $user_id);
        return in_array($user_id, $this->getTrustees());
    }

    public function canKick($viewer_id, $member_id) {
        if($viewer_id == $member_id) return false; // You can't kick yourself
        $user = User::make($viewer_id);
        if(!$viewer = $user->getProvince()) return false;
        $user = User::make($member_id);
        if(!$member = $user->getProvince()) return false;

        $viewer_clan_id = $viewer->getClanId();
        $member_clan_id = $member->getClanId();
        if($viewer_clan_id && $member_clan_id) {
            if($viewer_clan_id != $this->id) return false;
            if($member_clan_id != $this->id) return false;
            if($this->isCLT($viewer_id) && !$this->isCLT($member_id)) return true; // CT can kick nonCTL
            if($this->isCL($viewer_id) && $this->isCT($member_id)) return true; // CL can kick CT
        }
        return false;
    }
    public function canDeclare($user_id=false) {
        $user = (!$user_id ? CurrentUser::make()->get('id') : User::make($user_id));
        if(!$province = $user->getProvince()) return false;
        if($clan = $province->getClan()) {
            return ($clan->get('id') != $this->id && $clan->isCLT($user->get('id')));
        }
        return false;
    }

    public function inRange($my_clan_id=false) {
        if(!$my_clan_id) {
            $user = CurrentUser::make();
            if(!$province = $user->getProvince()) return false;
            if(!$my_clan_id = $province->getClanId()) return false; // I have no clan
        }
        if($this->id == $my_clan_id) return false;
        $my_clan = Clan::make($my_clan_id);
        $min_nw = $my_clan->getNetworth()/Settings::get('attack_range_mult');
        $max_nw = $my_clan->getNetworth()*Settings::get('attack_range_mult');
        return ($this->getNetworth() > $min_nw  && $this->getNetworth() < $max_nw);
    }

    public function getTag($format=false,$brackets=true) {
        $clantag = $this->get('clan_tag');
        if(!empty($clantag) && $brackets) $clantag = '['.str_replace(array('[', ']'), '', $clantag).']';
        return ($format ? '<strong>' . $clantag . '</strong>' : $clantag);
    }

    public function getName($format=false) {
        return $this->get('name');
    }

    public function getLink($format=false) {
        if(!$format) return $this->get('link');
        return '<a href="'.$this->getLink(false).'">'.$this->getName(true).'</a>';
    }

    public function getAvatar($classes='', $link=true) {
        $avatar = $this->get('clan_thumb');
        $firstletter = strtoupper(substr($this->getName(), 0, 1));
        if(!preg_match('/[A-Z]/', $firstletter)) $firstletter = '_';
        $classes = array_merge( (!is_array($classes) ? array($classes) : array()), array('setAvatar clan_avatar'));
        $classes[] = !empty($avatar) ? 'uploaded' : 'letter';
        $return = (!!$link ? '<a href="'.$this->getLink().'" title="'.$this->getName().'">' : '');
        $return .= '<div class="'. implode(' ', $classes) .'">';
        if(!empty($avatar) && in_array(substr($avatar, -3), array('jpg','png','gif'))) {
            $return .= '<img src="'. str_replace("http://", "https://", $avatar) .'">';
        }
        else {
            $return .= '<img src="'. get_stylesheet_directory_uri().'/img/avatars/'. $firstletter .'.png' .'">';
        }
        return $return . (!!$link ? '</div>' : '') . '</a>';
    }

    public function getImage($format=false) {
        $clanImg = $this->get('clan_image');
        return (!empty($clanImg) ? ($format ? '<img src="'.$clanImg.'">' : $clanImg) : '');
    }

    public function getMessage($format=false) {
        $p = $this->get('clan_message');
        return ($format ? html_entity_decode($p, ENT_QUOTES) : $p); // @wp
    }

    public function getPublicMessage($format=false) {
        $content = get_the_content($this->id);
        if(!empty($content)) $content = str_replace("\r", "<br />", wp_strip_all_tags($content));
        return $content;
    }

    public function getNetworth($format=false, $update=false) {
        if($update) {
            $total = 0;
            foreach($this->getMembers() as $member_id) {
                $province = Province::make($member_id);
                $province->calculateNetworth();
                $total += $province->getNetworth(false);
            }
            $this->update('clan_networth', round($total));
        }
        $n = intval($this->get('clan_networth'));
        return ($format ? Format::networth($n) : $n);
    }

    public function getAvgNetworth($format=false) {
        $n = intval($this->get('clan_networth'));
        $avg = $n / count($this->getMembers());
        return ($format ? Format::networth($avg) : $avg);
    }

    public function getPoints($format=false) {
        $n = intval($this->get('clan_points'));
        return ($format ? Format::points($n) : $n);
    }

    public function get24hPoints($format=false) {
        $n = intval($this->get('24h_pts'));
        return ($format ? Format::points($n) : $n);
    }

    public function getLeader() { // returns ID!
        return intval($this->get('clan_leader'));
    }
    public function getTrustees() { // returns IDs!
        $return = array();
        for($i=1;$i<=Settings::get('clan_trustee_num');$i++) {
            if(!empty($this->get('ct_'.$i))) $return[] = intval($this->get('ct_'.$i));
        }
        return $return;
    }
    public function getCLTs() { // returns leader and trustee IDs!
        return array_merge(array($this->getLeader()), $this->getTrustees());
    }
    public function getMembers() { // returns IDs!
        $members = $this->get('clan_members');
        if(!empty($members)) $members = unserialize($members);
        return (is_array($members) && count($members) ? $members : array());
    }

    public function getPreviousMembers() {
        $previous_members = maybe_unserialize($this->get('previous_members'));
        if(!is_array($previous_members) || Round::isPaused()) $previous_members = array();
        return $previous_members;
    }

    public function getOpenInvites() {
        $open_invites = maybe_unserialize(maybe_unserialize($this->get('open_invites')));
        if(!is_array($open_invites)) $open_invites = array();
        return $open_invites;
    }

    public function getCooldownList() {
        $cooldownlist = maybe_unserialize($this->get('cooldown_list'));
        if(!is_array($cooldownlist) && !empty($cooldownlist)) $cooldownlist = maybe_unserialize($cooldownlist); // Temp fix double serialization
        if(!is_array($cooldownlist)) $cooldownlist = array();
        $timestamp = current_time('timestamp');
        foreach ($cooldownlist as $key => $unset_time) {
            if ($unset_time < $timestamp) unset($cooldownlist[$key]);
        }
        $this->update('cooldown_list', $cooldownlist);
        return $cooldownlist;
    }

    public function getWarArray() {
        $war_array = maybe_unserialize(maybe_unserialize($this->get('war_array'))); //old data may be malformed
        if(!is_array($war_array)) $war_array = array();
        return $war_array;
    }

    public function canEditMessage($user=null) {
        if(is_null($user)) $user = CurrentUser::make();
        if($user->get('id') == $this->getLeader()) return true;
        if(in_array($user->get('id'), $this->getTrustees())) return true;
        return false;
    }

    public function getAwards() {
        // @todo: use new Award()
        return get_posts(array('post_type' => 'award','numberposts' => -1, 'meta_key' => 'winning_clan', 'meta_value' => $this->id));
    }

    public function canPeace($viewer_clan_id=false) {
        $timestamp = current_time('timestamp');
        $incomingWar = $this->getIncomingWars($viewer_clan_id);
        if(!!$incomingWar && $timestamp-get_the_title($incomingWar->ID) > Settings::get('peace_after_time')) return true;
        return false;
    }

    public function getResumetime($viewer_clan_id=false) {

        $warType = $this->getWarType($viewer_clan_id);
        if($warType == 'none') return 0;

        // Find peaced war
        if(!$war = $this->getIncomingWars($viewer_clan_id, 'trash')) {
            return 0;
        }

        // Find peace time
        $peacetime = 0;
        $resume_time = Settings::get('resume_after_hours');
        $eventposts = get_posts(array('numberposts' => 1, 'title' => 'PEACE', 'post_status' => 'publish', 'post_type' => 'event_local',
            'meta_query' => array('relation' => 'AND',
                array('key' => 'attacker_clan_id', 'value' => $viewer_clan_id),
                array('key' => 'defender_clan_id', 'value' => $this->get('id')),
            ),
        ));
        if(count($eventposts)) {
            $peacetime = intval(get_post_meta($eventposts[0]->ID, 'time_attacked', true)) + (60*60* $resume_time);
        }

        return $peacetime;
    }

    //  After peace there is a 72 hour cooldown before you can declare war on that clan again,
    //  unless that clan has a war declared on you, then there will be a 12h cooldown before you can resume war.
    public function canResume($viewer_clan_id=false) {
        $peacetime = $this->getResumetime($viewer_clan_id);
        if($peacetime == 0) return false;
        if(current_time('timestamp') < $peacetime) return false;
        return true;
    }

    public function peaceWar($dec_msg='') {
        $timestamp = current_time('timestamp');
        $user = CurrentUser::make();
        $province = $user->getProvince();
        $viewer_clan = $province->getClan();
        if(empty($viewer_clan->get('id'))) return false; // Just in case

        // Find & trash war-post
        $incomingWar = $this->getIncomingWars($viewer_clan->get('id'));
        if(!$incomingWar) return false;
        wp_trash_post($incomingWar->ID);

        // Add clan to OUR cooldown list
        $cooldownlist = $viewer_clan->getCooldownList();
        $cooldownlist[$this->get('id')] = $timestamp + Settings::get('cooldown_time');
        $viewer_clan->update('cooldown_list', $cooldownlist);

        // Add peace event
        Event::create(array(
            'title' => 'PEACE', 'type' => 'peace_declared', 'author' => 1, 'dec_message' => $dec_msg,
            'attacker_clan_id' => $viewer_clan->get('id'), 'defender_clan_id' => $this->get('id'),
            'attacker_id' => $province->get('id'), 'defender_id' => $this->getLeader(), 'send' => 'global'
        ), array_merge($this->getMembers(), $viewer_clan->getMembers()));
        return true;
    }

    public function resumeWar($dec_msg='') {
        $timestamp = current_time('timestamp');
        $user = CurrentUser::make();
        $province = $user->getProvince();
        $viewer_clan = $province->getClan();
        if(empty($viewer_clan->get('id'))) return false; // Just in case

        // Find peaced war
        if(!$war = $this->getIncomingWars($viewer_clan->get('id'), 'trash')) {
            return false;
        }

        // Update the war-post into the database
        $my_post = array('ID' => $war->ID, 'post_status' => 'publish', 'post_title' => $timestamp);
        wp_update_post($my_post);

        // unset in cooldownlist
        $cooldownlist = $viewer_clan->getCooldownList();
        unset($cooldownlist[$this->get('id')]);
        $viewer_clan->update('cooldown_list', $cooldownlist);

        // Add resume-event
        Event::create(array(
            'title' => 'WAR RESUMED', 'type' => 'war_declared', 'author' => 1, 'dec_message' => $dec_msg, 'outcome' => 'resume',
            'attacker_clan_id' => $viewer_clan->get('id'), 'defender_clan_id' => $this->get('id'),
            'attacker_id' => $province->get('id'), 'defender_id' => $this->getLeader(), 'send' => 'global'
        ), array_merge($this->getMembers(), $viewer_clan->getMembers()));
        return true;
    }

    public function declareWar($dec_msg='') {
        $timestamp = current_time('timestamp');
        $user = CurrentUser::make();
        $province = $user->getProvince();
        $viewer_clan = $province->getClan();
        if(empty($viewer_clan->get('id'))) return false; // Just in case

        // Create war-post
        $war_ID = md5(uniqid(rand(), true));
        $args = array('post_title' => $timestamp, 'post_content' => '', 'post_status' => 'publish', 'post_type' => 'wars', 'post_author' => $province->get('id'));
        $new_war_id = wp_insert_post($args);
        update_post_meta($new_war_id, 'declared_by', $viewer_clan->get('id'));
        update_post_meta($new_war_id, 'declared_on', $this->get('id'));
        update_post_meta($new_war_id, 'war_array_id', $war_ID);

        // Add war-event
        Event::create(array(
            'title' => 'NEW WAR', 'type' => 'war_declared', 'author' => 1, 'dec_message' => $dec_msg,
            'attacker_clan_id' => $viewer_clan->get('id'), 'defender_clan_id' => $this->get('id'),
            'attacker_id' => $province->get('id'), 'defender_id' => $this->getLeader(), 'send' => 'global'
        ), array_merge($this->getMembers(), $viewer_clan->getMembers()));

        // Notify all clan members they are in a war now
        foreach ($this->getMembers() as $member) {
            fcm_send_notification($member, 'wardeclared', $province->get('id'));
        }

        // Add/update clan->war_array for statistics, both clans
        $viewer_war_array = $viewer_clan->getWarArray();
        $defend_war_array = $this->getWarArray();
        if (isset($viewer_war_array[$war_ID])) {
            $viewer_war_array[$war_ID]['id_outgoing'] = $new_war_id;
            $viewer_war_array[$war_ID]['mutual_date'] = $timestamp;
            $defend_war_array[$war_ID]['id_incoming'] = $new_war_id;
            $defend_war_array[$war_ID]['mutual_date'] = $timestamp;
        } else {
            $war = array(
                'date' => $timestamp, 'mutual_date' => 0, 'end_date' => 0,
                'declarer_id' => $viewer_clan->get('id'), 'receiver_id' => $this->get('id'), 'id_outgoing' => $new_war_id, 'id_incoming' => 0,
                'attacks_received' => 0, 'successfull_def' => 0, 'attacks_made' => 0, 'successfull_att' => 0,
                'missiles_received' => 0, 'missiles_hit_def'  => 0, 'missiles_sent' => 0, 'missiles_hit_att' => 0, 'nw_dmg_done' => 0, 'nw_dmg_rec' => 0, 'highest_nw_dmg' => 0,
                'bds_killed' => 0, 'bds_lost' => 0, 'units_killed' => 0, 'units_lost' => 0, 'land_gained' => 0, 'land_lost' => 0,
                'money_gained' => 0, 'clan_points' => 0, 'money_lost' => 0, 'kills' => 0, 'deaths' => 0
            );
            $viewer_war_array[$war_ID] = $war;
            $war['id_outgoing'] = 0;
            $war['id_incoming'] = $new_war_id;
            $defend_war_array[$war_ID] = $war;
        }
        $viewer_clan->update('war_array', maybe_serialize($viewer_war_array));
        $this->update('war_array', maybe_serialize($defend_war_array));
        return true;
    }

    public function getIncomingWars($viewer_clan_id=false, $status='publish') {
        $incoming_wars = get_posts(
            array('numberposts'	=> -1, 'post_type' => 'wars', 'post_status' => $status, 'meta_query' => array('relation' => 'AND',
                array('key' => 'declared_on', 'value' => $this->get('id'), 'compare' => '='),
            ))
        );
        if(!empty($viewer_clan_id)) {
            foreach($incoming_wars as $war) {
                $declaredClanID = get_post_meta($war->ID, 'declared_by', true);
                if($declaredClanID == $viewer_clan_id) return $war;
            }
            return false;
        }
        return $incoming_wars;
    }

    public function getOutgoingWars($viewer_clan_id=false, $status='publish') {
        $outgoing_wars = get_posts(
            array('numberposts'	=> -1, 'post_type' => 'wars', 'post_status' =>  $status, 'meta_query' => array('relation' => 'AND',
                array('key' => 'declared_by', 'value' => $this->get('id'), 'compare' => '='),
            ))
        );
        if(!empty($viewer_clan_id)) {
            foreach($outgoing_wars as $war) {
                $attClanID = get_post_meta($war->ID, 'declared_on', true);
                if($attClanID == $viewer_clan_id) return $war;
            }
            return false;
        }
        return $outgoing_wars;
    }

    public function getWarType($viewer_clan_id) {
        if(empty($viewer_clan_id)) return 'none';
        $outgoing_war = (!!$this->getOutgoingWars($viewer_clan_id));
        $incoming_war = (!!$this->getIncomingWars($viewer_clan_id));
        if ($outgoing_war && $incoming_war) return 'mutual';
        elseif ($outgoing_war) return 'outgoing';
        elseif ($incoming_war) return 'incoming';
        else return 'none';
    }

    public function getWarTypeMultiplier($warType) {
        $warTypeMulti = Settings::get('war_type_multi');
        return (isset($warTypeMulti[$warType]) ? $warTypeMulti[$warType] : 0);
    }

    public function getClanMemberSizeDiff($viewer_clan_id) {
        $viewer_clan_count = 1;
        if($viewer_clan = Clan::make($viewer_clan_id)) {
            if($this->getWarType($viewer_clan_id) == 'mutual') return 0;
            $viewer_clan_count = count($viewer_clan->getMembers());
        }
        return count($this->getMembers()) - $viewer_clan_count ;
    }

    public function getClanPointsTotalDiff($viewer_clan_id) {
        if($viewer_clan = Clan::make($viewer_clan_id)) {
            if($this->getPoints() < 500 && $viewer_clan->getPoints() < 500) return 0;
            if($this->getWarType($viewer_clan_id) == 'mutual') return 0;
            $vp = max($viewer_clan->getPoints(),1);
            $mp = max($this->getPoints(),1); // devision by zero and all
            return $vp / $mp; // do not round because of huge differences
        }
        return 0;
    }

    // Building damage modifier based on clan member size difference
    // diff 5 = 2% damage reduction
    public function getClanSizeDamageMultiplier($viewer_clan_id) {
        $diff = $this->getClanMemberSizeDiff($viewer_clan_id);
        //if($diff < 1) return 100;
        return 100-(($diff*2)/5);
    }

    // Clanpoint modifier based on clanmembersize difference
    // diff 5 = 30% pts reduction
    public function getClanSizePointsMultiplier($viewer_clan_id) {
        $diff = $this->getClanMemberSizeDiff($viewer_clan_id);
        //if($diff < 1) return 100;
        return 100-(($diff*30)/5);
    }

    // Clan points scaled to the difference between the points of two clans.
    // An attacking clan with higher points than defending clan will receive less points
    public function getClanTotalPointsMultiplier($viewer_clan_id) {
        $diff = $this->getClanPointsTotalDiff($viewer_clan_id);
        if($diff == 0) return 100;
        //(a) + (b*x) + (c*x^2) + (d*x^3)
        //where x is "Clan A / Clan B", a=0.776, b=0.543, c=(-0.485), d=0.166
        $multi = 0.776 + (0.543*$diff) + (-0.485*pow($diff,2)) + (0.166*pow($diff,3));
        // old: $multi = (($diff * 0.65) + 0.35);
        $multi = min($multi, 1.25);// max diff is 0.25 up
        $multi = max($multi, 0.9); // max diff is 0.1 down
        return round($multi * 100, 2);
    }

    public function getWarModifiers($viewer_clan_id, $warType=false) { // send wartype to see what WOULD BE the modifiers
        require_once('attack_functions.php');
        $mods = array();
        $totals = array('points' => 100, 'damage' => 100, 'gains' => 100);

        $user = CurrentUser::make();
        $province = $user->getProvince();
        if($province->hasStartingBonus('offensive')) {
            $mods[] = '<strong>Offensive startbonus:</strong>';
            $mods[] = '<i class="fa fa-crosshairs"></i> 200% land and money';
            $totals['gains'] = $totals['gains'] * 2;
        }

// Thief education
// Thieves steal two times the amount of money
// Thieves steal three times the amount of money
// Thieves steal four times the amount of money

// Missile accuracy
// 45% chance to hit target
// 90% chance to hit target

        if($warType == 'incoming') {
            $n = $this->getWarTypeMultiplier($warType);
            $totals['points'] = $totals['points'] * $n;
            $mods[] = '<strong>Incoming war:</strong>';
            $mods[] = '<i class="fa fa-crosshairs"></i> '.($n*100).'% pts';
        }
        if($warType != 'mutual') {
            $dmgDiff = $this->getClanSizeDamageMultiplier($viewer_clan_id);
            $totals['damage'] = $totals['damage'] * ($dmgDiff/100);
            $mods[] = '<strong>Clan member difference: '.$this->getClanMemberSizeDiff($viewer_clan_id).'</strong>';
            $mods[] = '<i class="fas fa-industry"></i> '. $dmgDiff .'% damage on buildings';
        }
        if($warType != 'mutual' && $warType != 'none') {
            $sizeDiff = $this->getClanSizePointsMultiplier($viewer_clan_id);
            $ptsDiff = $this->getClanTotalPointsMultiplier($viewer_clan_id);
            $totals['points'] = round(($totals['points'] * ($sizeDiff/100)) * ($ptsDiff/100),2);
            $mods[] = '<i class="fa fa-crosshairs"></i> '. $sizeDiff .'% pts';
            $mods[] = '<strong>Clan points total difference: '.$this->getPoints() .' vs '. Clan::make($viewer_clan_id)->getPoints().'</strong>';
            $mods[] = '<i class="fa fa-crosshairs"></i> '. $ptsDiff .'% pts';
        }
        if($warType == 'none') {
            //$mods[] = '<strong>Out of war attacks</strong>';
            //$mods[] = '<i class="fas fa-industry"></i> todo';
        }
        if($warType == 'mutual') {
            $mods[] = '<strong>Mutual</strong>';
            $mods[] = 'No modifiers!';
        }
        return array($mods, $totals);
    }
}
