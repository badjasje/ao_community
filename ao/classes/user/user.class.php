<?php
class User extends DbObject {
    //static $table = 'users';
    static $cache = 'users';
    public $fields = array(
        'id','email','nicename','registered','display_name',
        'nickname','name_change_counter','first_name','last_name','avatar_user','status',
        'description','phone_number','first_visit','last_online','user_lock',
        'telegram_key','high_power_notified','low_power_notified','low_buildings_notified','last_summary',
    );
    private $province = false;

    public function __construct($props=null) {
        if(is_numeric($props)) {
            if(static::$cache && isset(static::$list[static::$cache][$props])) {
                return parent::__construct(static::$list[static::$cache][$props]);
            }
            // Set user and province from data from Wordpress (for now)
            $props = $this->getUserDataFromWordpress($props);
        }
    	if(is_array($props)) {
            foreach($props as $k => $v) {
                if(count($this->fields) && !in_array($k, $this->fields)) { unset($props[$k]); continue; }
            }
        }
        parent::__construct($props);
    }

    public function update($key, $value) {
        if(in_array($key,$this->fields)) update_user_meta($this->id, $key, $value);
        else {
            $userdata = array('ID'=>$this->id);
            $userdata['user_'.$key] = $value;
            wp_update_user($userdata);
        }
        parent::update($key, $value);
    }

    private function getUserDataFromWordpress($id) {
        $user = get_userdata($id);
        $meta = array_map( function( $a ){ return $a[0]; }, get_user_meta($id));
        $props = array_merge(array(
            'id' => $user->ID, 'email' => $user->data->user_email, 'nicename' => $user->data->user_nicename,
            'registered' => $user->data->user_registered, 'display_name' => $user->data->display_name
        ), $meta);

        $this->province = Province::make($props);
        return $props;
    }

    public function isBanned() {
        return ($this->status == 'banned');
    }
    public function getProvince() {
        return $this->province;
    }

    /*public function getUsernamelink() {
        return '<a href="'.get_site_url().'/users/profile/?id='.$this->get('id').'" data-id="'.$this->get('id').'" class="user-link username-link">
            '.$this->get('username').'
        </a>';
    }
    public function getXP() {}
    public function getAvatar() {}
    public function getDisplayName() {}
    public function getUsername() {}
    public function getEmail() {}*/
}