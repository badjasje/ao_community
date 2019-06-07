<?php
/**
 * I don't like the class from wp...
 */
class Database extends mysqli {

    private static $instance = null;
    private $queries = array();

    private function __construct($h,$u,$p,$d) {
    	parent::__construct($h,$u,$p,$d);
    	if($this->connect_errno) die('Error connecting to db');
    }
    private function __clone() {} // prevent cloning of the instance
    private function __wakeup() {} // prevent unserializing of the instance
    public function __destruct() {
		$this->close();
    }

    public static function make() {
        if (null === static::$instance) {
            static::$instance = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        }
        return static::$instance;
    }

	// Keep track of all queries
	public function query($str, $resultmode = NULL) {
        $this->queries[] = $str;
		$r = parent::query($str);
		if(!$r) die('Query failed: '.$str);
		return $r;
    }

    public function getQueries() {
        return $this->queries;
    }

    // Shortcut for escaping variables
    public function escape($value) {
        return $this->real_escape_string($value);
    }

    /**
     * Noob function for update or insert when not exists
     * @param $fields - example array('username'=>'test','password'=>'1234','modified'=>time(),'created'=>time())
     * @param $table - tablename
     * @param $where - example array('id' => 1)
     * @param $update - fields only for update, example: array('modified')
     * @param $insert - fields only for insert, example: array('created')
     * @return array result
     */
    public function changeTable($fields, $table, $where=array(), $update=array(), $insert=array()) {
        $valuelist = $wherelist = $updatelist = $insertlist = array();
        foreach ($fields as $key => $val) {
        	$val = $this->escape($val);
            if(in_array($key, $where)) $wherelist[] = "`$key` ".($val=='NULL'?'is NULL':"= '$val'");
            if(in_array($key, $update)) $updatelist[] = "`$key` ".($val=='NULL'?'is NULL':"= '$val'");
            elseif(in_array($key, $insert)) $insertlist[] = "`$key` ".($val=='NULL'?'is NULL':"= '$val'");
            else $valuelist[] = "`$key` = ".($val=='NULL'?'NULL':"'$val'");
        }
        if(count($wherelist) > 0) {
            if($result = $this->query("SELECT * FROM `$table` WHERE ".implode(" AND ", $wherelist))) {
            	if($result->num_rows > 0) {
                    if($this->query("UPDATE `$table` SET ".implode(", ", array_merge($valuelist, $updatelist)).
                        " WHERE ".implode(" AND ", $wherelist))) {
	                    return $result->fetch_assoc();
	                }
	            }
	            $result->close();
            }
        }
        $result = $this->query("INSERT INTO `$table` SET ".implode(", ", array_merge($valuelist, $insertlist)));
        return array('id' => $this->insert_id);
    }

    // Get first field from a result: "SELECT field FROM table"
    public function getField($q) {
    	$return = false;
    	if($row = $this->getRow($q)) $return = array_shift($row);
    	return $return;
    }

    // Get the first row of an result
    public function getRow($q) {
    	$return = false;
    	if($result = $this->query($q)) {
    		$row = $result->fetch_assoc();
    		if(is_array($row)) $return = $row;
    		$result->free();
    	}
    	return $return;
    }
}