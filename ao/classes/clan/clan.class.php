<?php
class Clan extends PostObject {

    public static $cache = 'clans';

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

    public function getTag($format=false) {
        $clantag = $this->get('clan_tag');
        if(!empty($clantag)) $clantag = '['.str_replace(array('[', ']'), '', $clantag).']';
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

    public function getMessage($format=false) {
        $p = $this->get('clan_message');
        return ($format ? html_entity_decode($p, ENT_QUOTES) : $p); // @wp
    }

    public function getNetworth($format=false) {
        $n = intval($this->get('clan_networth'));
        return ($format ? Format::networth($n) : $n);
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
            if(!empty($this->get('ct_'.$i))) $return[] = $this->get('ct_'.$i);
        }
        return $return;
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

    public function canEditMessage($user=null) {
        if(is_null($user)) $user = CurrentUser::make();
        if($user->get('id') == $this->getLeader()) return true;
        if(in_array($user->get('id'), $this->getTrustees())) return true;
        return false;
    }

    public function getWarType($defend_clan_id) {
        if(empty($defend_clan_id)) return 'none';

        $outgoing_wars = get_posts(
            array('numberposts'	=> -1, 'post_type' => 'wars', 'meta_query' => array('relation' => 'AND',
                array('key' => 'declared_on', 'value' => $defend_clan_id, 'compare' => '='),
                array('key' => 'declared_by', 'value' => $this->get('id'), 'compare' => '='),
            ))
        );
        $outgoing_war = (count($outgoing_wars) > 0);

        $incoming_wars = get_posts(
            array('numberposts'	=> -1, 'post_type' => 'wars', 'meta_query' => array('relation' => 'AND',
                array('key' => 'declared_on', 'value' => $this->get('id'), 'compare' => '='),
                array('key' => 'declared_by', 'value' => $defend_clan_id, 'compare' => '='),
            ))
        );
        $incoming_war = (count($incoming_wars) > 0);

        if ($outgoing_war && $incoming_war) return 'mutual';
        elseif ($outgoing_war) return 'outgoing';
        elseif ($incoming_war) return 'incoming';
        else return 'none';
    }
}
