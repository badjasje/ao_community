<?php
/**
 * High level database class
 * Should be extended per table
 */
class DbObject extends PhpObject {

    public static $table = false;
    public static $list = array();
    public static $cache = false;
    private $db;

    /**
     * Example 1: new User(1); // gets "SELECT * FROM users WHERE id = 1" and fills it
     * Example 2: new User($results); // fills everything from result
     */
    public function __construct($props=null) {
		if(!!static::$cache && !isset(static::$list[static::$cache])) static::$list[static::$cache] = array();
		if(is_numeric($props)) $props = $this->getOne($props);
		if(is_array($props)) {
            $this->setPropertiesFromArray($props);
            if(!!static::$cache && isset($this->id) && !isset(static::$list[static::$cache][$this->id])) $this->setCache($props);
        }
    }

    /**
     * Example: User::add(array('username'=>'test','password'=>'12345'))
     * @return user object
     */
    public static function add($props=null) {
        if(!static::$table) return;
        $obj = Database::make()->changeTable($props, static::$table);
		if(isset($obj['id'])) return new static($obj['id']);
		return false;
    }

    /**
     * Example: User::getAll()
     * @return: array
     */
    public static function getAll() {
		if(!static::$table) return;

		$collection = array();
		foreach(Database::make()->query('SELECT * FROM `'. static::$table .'`') as $row) {
			if(isset($row['id'])) $collection[$row['id']] = static::make($row);
		}
		return $collection;
    }

    /**
     * Example 1: User::getSome('status','online')
     * Example 2: User::getSome(array('status'=>'online','type'=>'admin'))
     * @return array
     */
    public static function getSome($key,$value=null) {
		if(!static::$table) return;
        $db = Database::make();

        $props = $key;
        if(func_num_args() == 2) $props = array($key => $value);
        $args = array();
        foreach($props as $key => $value) $args[] = '`'.$key.'`="'. $db->escape($value) .'"';
        $collection = array();
        foreach($db->query('SELECT * FROM `'. static::$table .'` WHERE '.implode(' AND ', $args)) as $row) {
            if(isset($row['id'])) $collection[$row['id']] = static::make($row);
        }
        return $collection;
	}

    /**
     * Example 1: User::getOne(1)
     * Example 2: User::getOne('id', 1)
     * Example 3: User::getOne('username','test')
     * Example 4: User::getOne(array('status'=>'online','type'=>'admin'))
     * @return user object
     */
    public static function getOne($key,$value=null) {
        if(!static::$table) return;
        $db = Database::make();

        $props = $key;
		if(func_num_args() == 1 && is_numeric($key)) {
			if(static::$cache && isset(static::$list[static::$table][$key])) return static::__construct(static::$list[static::$table][$key]);
			$props = array('id' => $key);
		}
		if(func_num_args() == 2) $props = array($key => $value);

        $args = array();
		foreach($props as $key => $value) $args[] = '`'.$key.'`="'. $db->escape($value) .'"';
		if($row = $db->getRow('SELECT * FROM `'. static::$table .'` WHERE '.implode(' AND ', $args))) {
			return static::__construct($row);
		}
        return false;
    }

    // Example: $user->update(array('username'=>'new'))
    public function update($key,$value) {
        $data = array('id' => $this->get('id'));
        $data[$key] = $value;
        if(!!static::$table) Database::make()->changeTable($data, static::$table, array('id'));
        $this->setPropertiesFromArray($data);
        $this->setCache(array($key => $value));
        return true;
    }

    // Example: $user->delete()
    public function delete() {
        if(!static::$table) return;
		if($this->get('id')) {
			return Database::make()->query('DELETE FROM `'. static::$table .'` WHERE `id`="'.$this->get('id').'"');
		}
		return false;
    }

    public function get($key) {
		if(!!static::$cache && isset($this->id) && isset(static::$list[static::$cache][$this->id])) {
            return (isset(static::$list[static::$cache][$this->id][$key]) ? static::$list[static::$cache][$this->id][$key] : false);
        }
        return parent::get($key);
	}

    public function set($key, $prop) {
        $this->setCache(array($key => $prop));
        parent::set($key, $prop);
    }

    //
    public function setCache($props) {
        if(!static::$cache) return false;
        if(!isset(static::$list[static::$cache][$this->id])) static::$list[static::$cache][$this->id] = array();
        static::$list[static::$cache][$this->id] = array_merge(static::$list[static::$cache][$this->id], $props);
    }

}
