<?php
class Request {
    public static $full;
    public static $site_url;
    public static $path;
    public static $parts;
    public static $query;
    public static $ajax_paths = array(
        'header' => array('province','ajaxHeader'),
        'devfunds' => array('province','ajaxDevfunds')
    );

    static function init() {
        if (static::$full === null) {
            $url = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'?'https':'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            static::$full = strtolower($url);
            $parsed = parse_url(static::$full);
            if(!isset($parsed['path'])) $parsed['path'] = '/';
            if(!empty($parsed['query'])) {
                parse_str($parsed['query'], $arr);
                $parsed['query'] = $arr;
            }
            static::$site_url = $parsed['scheme'].'://'.$parsed['host'];
            static::$path = trim($parsed['path'],'/'); // "/user/login/" becomes "user/login"
            static::$parts = explode('/', static::$path);
            static::$query = (isset($parsed['query']) ? $parsed['query'] : array()); // please do not use this but use Request::get()
        }
    }

    // Returns site url, usage: Request::siteUrl()
    // alias for wp's get_site_url
    static function siteUrl() {
        return static::$site_url;
    }

    // Returns path only
    // Example: if(Request::path() == 'user/login')
    static function path() {
        return static::$path;
    }

    // Helper function to get a specific part of the current path
    // Example: if(Request::part(0) == 'user' && Request::part(1) == 'login')
    static function part($nr=null) {
        if(is_null($nr)) return static::$parts;
        else return (isset(static::$parts[$nr]) ? static::$parts[$nr] : false);
    }

    // @todo: convert all ajax calls to requests via /ajax
    static function isAjax() {
        return (static::part(0) == 'ajax');
    }

    // Return headers so ajax calls aren't cached
    static function noCache() {
        foreach(array(
            'Expires' => 'Wed, 23 Mar 1982 05:00:00 GMT',
            'Cache-Control' => 'no-cache, must-revalidate, max-age=0'
        ) as $key => $value) {
            header($key.': '.$value);
        }
    }

    // Wrapper for most ajax calls
    static function ajax() {
        static::noCache();

        $return = array('success' => false, 'status' => '');
        if(!in_array($_SERVER['REQUEST_METHOD'], array('GET','POST'))) $return['status'] = 'Method not allowed';
        if(!defined('ABSPATH')) $return['status'] = 'Base not found';
        $user = CurrentUser::make();
        if($user->isBanned()) $return['status'] = 'Your account is banned from Assault.Online.';
        if(!$user->isLoggedIn()) $return['status'] = 'You must log in to perform this action';
        $province = $user->getProvince();

        // Make sure we are in a valid path
        if(!in_array(Request::part(1), array_keys(static::$ajax_paths))) $return['status'] = 'Unknown path.';
        else {
            $funcs = static::$ajax_paths[Request::part(1)];
            if($funcs[0] == 'province') $return = call_user_func(array($province, $funcs[1]), $return);
        }
        return json_encode($return);
    }

    // Basic sanitation (& trim)
    // Instead of using $_GET['username'] use Request::get('username') which is safer
    // Example: Request::get('login_email', 'email') for email sanitation
    static function get($key, $filter = 'default') {
        $f = FILTER_SANITIZE_STRING;
        switch($filter) {
            case 'int': $f = FILTER_SANITIZE_NUMBER_INT; break;
            case 'email': $f = FILTER_SANITIZE_EMAIL; break;
            case 'url': $f = FILTER_SANITIZE_URL; break;
            case 'html': $f = FILTER_SANITIZE_FULL_SPECIAL_CHARS; break; // For saving html in database or displaying html-code as code
        }
        return (isset(static::$query[$key]) ?  filter_var(trim(static::$query[$key]), $f) : false);
    }

    // Basic sanitation (& trim)
    // Instead of using $_POST['username'] use Request::post('username') which is safer
    // Example: Request::post('userid', 'int') for integer sanitation
    static function post($key, $filter = 'default') {
        $f = FILTER_SANITIZE_STRING;
        switch($filter) {
            case 'int': $f = FILTER_SANITIZE_NUMBER_INT; break;
            case 'email': $f = FILTER_SANITIZE_EMAIL; break;
            case 'url': $f = FILTER_SANITIZE_URL; break;
            case 'html': $f = FILTER_SANITIZE_FULL_SPECIAL_CHARS; break; // For saving html in database or displaying html-code as code
        }
        return (isset($_POST[$key]) ? filter_var(trim($_POST[$key]), $f) : false);
    }

}