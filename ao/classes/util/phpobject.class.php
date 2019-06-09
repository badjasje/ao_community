<?php
class phpObject {

    // Example: Fill this object from a database result: $user = new User($result); echo $user->get('username');
	public function __construct($props=null) {
		if(is_array($props)) $this->setPropertiesFromArray($props);
	}

    // Easily create a new instance for method chaining
    // Example: echo User:make($result)->get('username')
	public static function make($items=null) {
		$class = get_called_class();
        return new $class($items);
    }

    // This should be in php default...
	public function setPropertiesFromArray($arr) {
		foreach($arr as $key => $prop) {
			$this->{$key} = $prop;
		}
	}

	public function get($key) {
		return (isset($this->{$key}) ? $this->{$key} : false);
	}

	public function set($key, $prop) {
		$this->{$key} = $prop;
	}
}