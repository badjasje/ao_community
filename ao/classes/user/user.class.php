<?php
/**
 * A user is an website-entity with a username, email and password
 * A province is game-entity with land, turns, morale, etc
 * Right now a user can only have ONE province
 */
class User extends DbObject {
    //static $table = 'users';
    static $cache = 'users';
    public $fields = array(
        'id','email','nicename','registered','display_name',
        'nickname','name_change_counter','first_name','last_name','avatar_user','status',
        'description','phone_number','first_visit','last_online','user_lock',
        'telegram_key','high_power_notified','low_power_notified','low_buildings_notified','last_summary',
    );
    public $province = false;

    public function __construct($props=null) {
        if(is_numeric($props)) {
            if(static::$cache && isset(static::$list[static::$cache][$props])) {
                return parent::__construct(static::$list[static::$cache][$props]);
            }
            $props = $this->getUserDataFromWordpress($props);
            $p_props = $props;
        }
    	if(is_array($props)) {
            foreach($props as $k => $v) {
                if(count($this->fields) && !in_array($k, $this->fields)) { unset($props[$k]); continue; }
            }
        }
        parent::__construct($props);
        if(isset($p_props)) $this->set('province', Province::make($p_props));
    }

    public function update($key, $value) {
        if(in_array($key,$this->fields)) update_user_meta($this->id, $key, $value); //@wp
        else {
            $_userdata = array('ID'=>$this->id);
            $_userdata['user_'.$key] = $value;
            wp_update_user($_userdata); //@wp
        }
        return parent::update($key, $value);
    }

    private function getUserDataFromWordpress($id) {
        $user = get_userdata($id); //@wp
        $meta = array_map( function( $a ){ return $a[0]; }, get_user_meta($id)); //@wp
        $props = array_merge(array(
            'id' => $user->ID, 'email' => $user->data->user_email, 'nicename' => $user->data->user_nicename,
            'registered' => $user->data->user_registered, 'display_name' => $user->data->display_name
        ), $meta);
        return $props;
    }

    public function isBanned() {
        return ($this->get('status') == 'banned');
    }
    public function isAdmin() {
        return (in_array($this->id, Settings::get('admin_ids'))); // can this even BE more ugly?
    }
    public function getProvince() {
        return $this->get('province');
    }

    public function getMessages() {
        $inboxargs = array(
            'posts_per_page' => 5, 'post_type' => 'user_message', 'meta_key' => 'last_update_stamp', 'orderby' => 'meta_value', 'order' => 'DESC',
            'meta_query' => array(
                'relation'		=> 'OR',
                array('key' => 'receiver_id', 'value' => $this->id, 'compare' => '='),
                array('key' => 'sender_id', 'value'	=> $this->id, 'compare' => '='),
            )
        );
        $messages = get_posts($inboxargs);
        $return = array();
        if(count($messages)) {
            foreach ($messages as $message) {
                $title = get_the_title($message->ID);
                $return[$message->ID]  = array(
                    'link' => get_the_permalink($message->ID),
                    'title' => (strlen($title) > 55 ? substr($title, 0, 55). '...' : $title)
                );
            }
        }
        return $return;
    }

    /*public function getUsernamelink() {
        return '<a href="'.Request::siteUrl().'/users/profile/?id='.$this->get('id').'" data-id="'.$this->get('id').'" class="user-link username-link">
            '.$this->get('username').'
        </a>';
    }
    public function getXP() {}
    public function getDisplayName() {}
    public function getUsername() {}
    public function getEmail() {}*/
}