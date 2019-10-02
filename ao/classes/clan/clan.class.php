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

    public function ajaxSetMessage($result) {
        if(!$this->canEditMessage()) return array('status' => 'You cannot do that');
        $content = sanitize_text_field(htmlentities(wp_kses_post(Request::post('new_message','raw'))));
        $this->update('clan_message', $content);
        return array('success' => true, 'status' => 'Clan message updated', 'clanmessage' => $this->getMessage(true));
    }

    function handleInvite($userId, $inviteKey, $target='Decline') {
        $timestamp = current_time('timestamp');
        $open_invites = $this->getOpenInvites();
        if(!count($open_invites)) return array('status' => 'No invites found');
        $province = Province::make($userId);
        if(empty($province->get('id'))) return array('status' => 'No user found');

        if($target == 'Accept') {
            if(Round::isLive() && Round::timeLeft() < 172800) {
                return array('status' => 'Cannot join a clan the last 48 hours of a round');
            }
            if($this->isFull()) {
                return array('status' => 'Maximum number of clan members reached');
            }
            $clanMembers = $this->getMembers(); // user ids
            $clanLeader = $this->getLeader(); // user id
            foreach ($open_invites as $key => $invite) {
                if($invite['invite'] == $inviteKey && $invite['clan'] == $this->get('id')) {
                    if($invite['user'] != $userId) return array('status' => 'This is not the invite you\'re looking for');

                    $province->update('clan_id_user', $this->get('id'));
                    $province->update('clan_join_stamp', $timestamp+86400);
                    $clanMembers[] = $userId;
                    unset($open_invites[$key]);
                    $this->update('clan_members', $clanMembers);
                    $this->update('open_invites', $open_invites);
                    update_post_meta($invite['invite_id'], 'invite_status', 'accept');

                    $args = [
                        'post_title' => 'Clan member joined a clan: '.$userId, 'post_status' => 'publish',
                        'post_type' => 'event_local', 'post_author' => $clanLeader
                    ];
                    $newEventId = wp_insert_post( $args );
                    update_field('attacktype', 'user_change', $newEventId);
                    update_field('outcome', 'joined', $newEventId);
                    update_field('attacker_id', $clanLeader, $newEventId);
                    update_field('defender_id', $userId, $newEventId);
                    update_field('attacker_clan_id', $this->get('id'), $newEventId);
                    update_field('time_attacked', $timestamp, $newEventId);
                    foreach ($clanMembers as $member_id) {
                        $member = Province::make($member_id);
                        $member->update('new_global_events', $member->get('new_global_events') + 1);
                    }
                    return array('success' => true, 'status' => "You are now a member of ".$this->getName());
                }
            }
        }
        else { // decline
            foreach ($open_invites as $key => $invite) {
                if ($invite['invite'] == $inviteKey && $invite['clan'] == $this->get('id')) {
                    if($invite['user'] != $userId) return array('status' => 'This is not the invite you\'re looking for');
                    unset($open_invites[$key]);
                    update_post_meta($invite['invite_id'], 'invite_status', 'accept');
                    $this->update('open_invites', $open_invites);
                    return array('success' => true, 'status' => "You declined the invite of ".$this->getName());
                }
            }
        }
        return array('status' => 'Undefined error');
    }

    public function isFull() {
        return count($this->getMembers()) >= Settings::get('clan_member_num');
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
