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

    public function getAvatar() {

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
}
