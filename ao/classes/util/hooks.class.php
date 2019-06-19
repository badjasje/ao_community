<?php
class Hooks extends phpObject {

	private static $instance;
	private static $events;

	static function trigger($event, $args, $id=false) {
        if(!isset(static::$events[$event])) return;

        if($id) static::$events[$event][$id]['cb']($args);
        else {
            foreach(static::$events[$event] as $_id => $obj) $obj['cb']($args);
        }
	}

	static function on($event, $cb, $id=false) {
		if(!$id) $id = 'hk_'.uniqid();
		if(!isset(static::$events[$event])) static::$events[$event] = array();
		static::$events[$event][$id] = array('cb' => $cb);
		return $id;
	}
}