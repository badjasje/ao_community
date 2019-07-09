<?php
class Clan extends PostObject {

    public static $cache = 'clans';

    function __construct($postData=null) {
        parent::__construct($postData);
        $this->setPropertiesFromArray(array_merge(
            array('link' => get_permalink($this->id), 'name' => $this->get('post_title') )
        ));
    }

    public function ajaxSetMessage($result) {
        if(!$this->canEditMessage()) return array('status' => 'You cannot do that');
        $content = sanitize_text_field(htmlentities(wp_kses_post(Request::post('new_message','raw'))));
        $this->update('clan_message', $content);
        return array('success' => true, 'status' => 'Clan message updated', 'clanmessage' => $this->getMessage(true));
    }

    public function getLink() {
        return $this->get('link');
    }

    public function getName() {
        return $this->get('name');
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

    public function canEditMessage($user=null) {
        if(is_null($user)) $user = CurrentUser::make();
        if($user->get('id') == $this->getLeader()) return true;
        if(in_array($user->get('id'), $this->getTrustees())) return true;
        return false;
    }
}
