<?php
class Hooks extends phpObject {

	private static $instance;
	private static $events;

	static function trigger($event, $id=false, &...$args) {
        if(!isset(static::$events[$event])) return;

        if($id) static::$events[$event][$id]['cb'](...$args);
        else {
            foreach(static::$events[$event] as $_id => $obj) {
				$obj['cb'](...$args);
			}
        }
	}

	static function on($event, $id=false, $cb) {
		if(!$id) $id = uniqid();
		if(!isset(static::$events[$event])) static::$events[$event] = array();
		static::$events[$event][$id] = array('cb' => $cb);
		ksort(static::$events[$event]);
		return $id;
	}
}