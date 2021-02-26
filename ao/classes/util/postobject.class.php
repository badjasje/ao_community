<?php
/**
 * Wordpress post class
 */
class PostObject extends PhpObject {

    public static $list = array();
    public static $cache = false;
    public static $wp_post_type = false;

    /**
     * getSome($key,$value)  // get some, returns array, using get_posts(args)
     * getAll() // returns array
     */

    function __construct($postData=null) {
        if(!!static::$cache && !isset(static::$list[static::$cache])) static::$list[static::$cache] = array();
        $props = array();
        if(is_numeric($postData)) {
            if(static::$cache && isset(static::$list[static::$cache][$postData])) {
                return parent::__construct(static::$list[static::$cache][$postData]);
            }
            $postData = get_post($postData);
            //if(is_null($postData))
        }
        if(is_object($postData) && isset($postData->ID)) {
            $meta = array_map( function( $a ){ return $a[0]; }, get_post_meta($postData->ID));
            $props = array_merge(json_decode(json_encode($postData),true), $meta, array('id' => $postData->ID));
        }
        if(is_array($props)) {
            $this->setPropertiesFromArray($props);
            if(isset($this->id)) $this->setCache($props);
        }
        parent::__construct($props);
    }

    public static function getAll() {
        if(!static::$wp_post_type) return;

        $collection = array();
		foreach(get_posts(array('post_type' => static::$wp_post_type, 'posts_per_page' => -1)) as $row) {
			if(isset($row->ID)) $collection[$row->ID] = static::make($row);
		}
		return $collection;
    }

    public function trash() { // Used on player reset, death or when a order/deposit/research ends
        wp_trash_post($this->get('id'));
    }

    function update($key, $value) {
        update_post_meta($this->id, $key, $value);
        $this->setPropertiesFromArray(array($key => $value));
        $this->setCache(array($key => $value));
        return true;
    }

    function addToMeta($key='clan_points', $value=0) {
        // Make a query that does:
        // UPDATE 23zx_postmeta SET meta_value = meta_value + $points WHERE meta_key = "clan_points" AND post_id = $this->id
        // Because two attacks can happen at the same time, and 100+10 and 100+20 will eventually become 120, while it should be 130
        global $wpdb;

        // Check if meta_value is not NULL but 0??
        $data = $wpdb->get_row('SELECT `meta_value` FROM '.$wpdb->prefix.'postmeta WHERE `meta_key` = "'.$key.'" AND `post_id` = '.$this->id, ARRAY_A);
        if(empty($data)) $wpdb->query('INSERT INTO '.$wpdb->prefix.'postmeta SET `meta_value` = 0, `meta_key` = "'.$key.'", `post_id` = '.$this->id);

        // Update
        if($value > 0) {
            $wpdb->query('UPDATE '.$wpdb->prefix.'postmeta SET `meta_value` = `meta_value` + '.$value.' WHERE `meta_key` = "'.$key.'" AND `post_id` = '.$this->id);
        }

        // Get new value, might not be accurate due to multiple processes at the same time
        $data = $wpdb->get_row('SELECT `meta_value` FROM '.$wpdb->prefix.'postmeta WHERE `meta_key` = "'.$key.'" AND `post_id` = '.$this->id, ARRAY_A);
        $new_value = $data['meta_value'];
        $this->setPropertiesFromArray(array($key => $new_value));
        $this->setCache(array($key => $new_value));
        return true;
    }

    public function set($key, $prop) {
        $this->setCache(array($key => $prop));
        parent::set($key, $prop);
    }

    public function setCache($props) {
        if(!static::$cache) return false;
        if(!isset(static::$list[static::$cache][$this->id])) static::$list[static::$cache][$this->id] = array();
        static::$list[static::$cache][$this->id] = array_merge(static::$list[static::$cache][$this->id], $props);
    }
}
