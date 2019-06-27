<?php
/**
 * Wordpress post class
 */
class PostObject extends PhpObject {

    public static $list = array();
    public static $cache = false;

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

    function update($key,$value) {
        update_post_meta($this->id, $key, $value);
        $this->setPropertiesFromArray(array($key => $value));
        $this->setCache(array($key => $value));
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
