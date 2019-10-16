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
        'id','email','nicename','registered','display_name','username','logindata','multi_whitelist',
        'nickname','name_change_counter','first_name','last_name','avatar_user','status',
        'description','phone_number','first_visit','last_online','user_lock',
        'telegram_key','high_power_notified','low_power_notified','low_buildings_notified','last_summary',
        'new_global_events','new_events','new_messages','clan_id_user',
    );
    public $province = false;

    public function __construct($props=null,$fromCache=true) {
        if(is_numeric($props)) {
            if(static::$cache && $fromCache==true && isset(static::$list[static::$cache][$props])) {
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
        if(isset($p_props) && is_array($p_props)) $this->set('province', Province::make($p_props));
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

    public function getUserDataFromWordpress($id) { // private, but also used in Province
        $user = get_userdata($id); //@wp
        if(!$user) return array('id' => $id);
        $user_meta = get_user_meta($id);
        $meta = (is_array($user_meta) ? array_map( function( $a ){ return $a[0]; }, $user_meta) : array()); //@wp
        $props = array_merge(array(
            'id' => $user->ID, 'username' => $user->data->user_login, 'email' => $user->data->user_email, 'nicename' => $user->data->user_nicename,
            'registered' => $user->data->user_registered, 'display_name' => $user->data->display_name
        ), $meta);
        return $props;
    }

    public function isBanned() {
        return ($this->get('status') == 'banned');
    }
    public function isAdmin() {
        return (isset($this->id) && in_array($this->id, Settings::get('admin_ids'))); // can this even BE more ugly?
    }
    public function isOnline() {
        $timestamp = current_time('timestamp');
        $last_online = $this->get('last_online');
        return (!empty($last_online) ? ($timestamp - $last_online < Settings::get('online_status_time')) : false);
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

    public function getUsername() {
        return $this->get('username');
    }

    public function getName($format=false) {
        if(!$format) return $this->get('display_name');
        if($this->isBanned()) return '<strike>'.$this->get('display_name').'</strike> <strong>banned</strong>';

        return $this->getName(false).' (#'.$this->get('id').')' . ($this->isOnline()?' <span class="online">*</span>':'');
    }

    public function getLink($format=false) { // @todo: make a permalink for users
        if(!$format) return Request::siteUrl().'/users/profile/?id='.$this->id;
        return '<a class="memberField" href="'.$this->getLink(false).'">'.$this->getName(true).'</a>';
    }

    public function getAvatar($classes='', $link=true) {
        $avatar = $this->get('avatar_user');
        if(strtolower($this->getName()) == 'minion') {
            $avatar = get_stylesheet_directory_uri().'/img/avatars/Minion.png';
        }
        $classes = array_merge( (!is_array($classes) ? array($classes) : array()), array('setAvatar'));
        $classes[] = !empty($avatar) ? 'uploaded' : 'letter';
        $return = (!!$link ? '<a href="'.$this->getLink().'" title="'.$this->getName().'">' : '');
        $return .= '<div class="'. implode(' ', $classes) .'">';
        if(!empty($avatar) && in_array(substr($avatar, -3), array('jpg','png','gif'))) {
            $return .= '<img src="'. str_replace("http://", "https://", $avatar) .'">';
        }
        else {
            $firstletter = strtoupper(substr($this->getName(), 0, 1));
            if(!preg_match('/[A-Z]/', $firstletter)) $firstletter = '_';
            $return .= '<img src="'. get_stylesheet_directory_uri().'/img/avatars/'. $firstletter .'.png' .'">';
        }
        return $return . '</div>' . (!!$link ? '</a>' : '');
    }

    public function getLoginData($format=false) {
        $data = maybe_unserialize($this->get('logindata'));
        return ($format==true ? '<pre>'. print_r($data,1) .'</pre>' : $data);
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

    public function getEvents($category='global') {
        $events = array();

        // Make query according to event-category
        $paged = get_query_var('paged', 1);
        $eventTypes = Event::getPossibleEventTypes($category);
        $args = array(
            'posts_per_page' => 20, 'orderby' => 'date', 'order' => 'DESC', 'paged' => $paged, 'post_type' => 'event_local',
            'meta_query' => array(
                'relation' => 'AND',
                array('key' => 'attacktype', 'value' => $eventTypes, 'compare' => 'IN'),
            )
        );
        if(in_array($category,array('incoming','outgoing'))) {
            $args['post_status'] = 'publish'; // why can global events be trashed?
        }
        switch($category) {
            case 'incoming':
                $args['meta_query'][] = array('key' => 'defender_id', 'value' => $this->id, 'compare' => '=');
            break;
            case 'outgoing':
                $args['meta_query'][] = array('key' => 'attacker_id', 'value' => $this->id, 'compare' => '=');
            break;
            case 'global':
                $clan = (!empty($this->get('clan_id_user')) ? Clan::make($this->get('clan_id_user')) : false);
                if(empty($clan->id)) return array();
                $members = $clan->getMembers();
                $args['meta_query'][] = array('relation' => 'OR',
                    array('key' => 'attacker_id', 'value' => $members[0], 'compare' => 'IN'),
                    array('key' => 'defender_id', 'value' => $members[0], 'compare' => 'IN')
                );
                $args['meta_query'][] = array('relation' => 'OR',
                    array('key' => 'attacker_clan_id', 'value' => $clan->id, 'compare' => 'IN'),
                    array('key' => 'defender_clan_id', 'value' => $clan->id, 'compare' => 'IN'),
                );
            break;
        }

        // Run query
        $wp_query = new WP_Query($args);
        if($wp_query->have_posts()) {
            foreach($wp_query->get_posts() as $post) {
                $post->category = $category;
                $events[] = new Event($post);
            }
        }

        return $events;
    }

}