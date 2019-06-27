<?php
class DataObject /*extends PhpObject*/ {

    static $data = false;

    static public function get($key=null) {
        if($key == null) return static::$data;
        if(!!static::$data && isset(static::$data[$key])) return static::$data[$key];
        return false;
    }
}
