<?php
class Request {
    public static $full;
    public static $site_url;
    public static $path;
    public static $parts;
    public static $query;
    public static $ajax_paths = array(
        'header' => array('province','ajaxHeader'),
        'devfunds' => array('province','ajaxDevfunds'),
        'startingbonus' => array('province','ajaxStartingbonus'),
        'clanbonus' => array('province', 'ajaxClanBonus'),
        'removenp' => array('province','ajaxRemoveNp'),
        'deposit' => array('province','ajaxDeposit'),
        'withdraw' => array('province','ajaxWithdraw'),
        'research' => array('province','ajaxSetResearch'),
        'exploreland' => array('province','ajaxExploreLand'),
        'sellland' => array('province','ajaxSellLand'),
        'buildings' => array('province','ajaxBuildings'),
        'units' => array('province','ajaxUnits'),
        'sendaid' => array('province','ajaxSendAid'),
        'message' => array('province','ajaxMessage'),
        'claninvite' => array('province','ajaxClanInvite'),
        'clanmessage' => array('clan','ajaxSetMessage')
    );
    // Rate limiting per hour
    public static $rate_limits = array(
        'wp-login.php' => 10,           //0
        'ajax/message' => 5,            //1

        // Old ajax calls
        'missiles.php' => 10,           //2
        'sell_missiles.php' => 10,      //3
        'activate_stealthsat.php' => 10,//4
        'cancel_order.php' => 10,       //5
        'satellite.php' => 10,          //6
        'market.php' => 40,             //7
        'cancel_order.php' => 30,       //8
        'sell_units.php' => 60,         //9
        'attack.php' => 120,            //10
        'step-2.php' => 120,            //11
        'attack2.php' => 120,           //12
        'step-3.php' => 120,            //13
        'attack-result.php' => 120,     //14
        'update_profile.php' => 60,     //15

        //'dashboard' => 60, // DO NOT EVER UNCOMMENT THIS PLEASE
        'toplists' => 60,               //16
        'all-clans' => 60,              //17
        'users' => 60,                  //18
        'users/profile' => 240,         //19
        'clan' => 120,                  //20
        'attack' => 120,                //21
        'spy-reports' => 120,           //22
        'spy-report-overview' => 120,   //23
        'send-message' => 120,          //24
        'military-overview' => 20,      //25
        'player-statistics' => 20,      //26
        'events' => 10,                 //27
        'events/incoming' => 60,        //28
        'events/outgoing' => 60,        //29
        'events/global' => 60,          //30
        'ajax/devfunds' => 60,          //31
        'ajax/deposit' => 10,           //32
        'ajax/withdraw' => 10,          //33
        'ajax/research' => 5,           //34
        'ajax/exploreland' => 10,       //35
        'ajax/sellland' => 10,          //36
        'ajax/buildings' => 50,         //37
        'ajax/units' => 50,             //38
        'ajax/sendaid' => 20,           //39
    );

    public static $page_links = array(
        'marketBuy' => 3179, //market/buy
        'clanMemberInformation' => 50302, //clan-member-information
        'clanWars' => 3842, //clan-wars
        'clanSendAid'=> 49609, //send-aid
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
            static::$query = (isset($parsed['query']) ? $parsed['query'] : array()); // please do not use this but use Request::get()\
        }
    }

    static function pathRateLimit() {
         // Rate limiting per hour, based on path
         if(in_array(static::$path, array_keys(static::$rate_limits))) {
            if(!isset($_SESSION['path_num'])) $_SESSION['path_num'] = array(date('H') => array());
            if(array_keys($_SESSION['path_num'])[0] != date('H')) $_SESSION['path_num'] = array(date('H') => array());
            if(!isset($_SESSION['path_num'][date('H')][static::$path])) $_SESSION['path_num'][date('H')][static::$path] = 0;
            $_SESSION['path_num'][date('H')][static::$path]++;
            $num = $_SESSION['path_num'][date('H')][static::$path];
            $limit = static::$rate_limits[static::$path];
            if($num > $limit) {
                $code = 'E'.array_search(static::$path, array_keys(static::$rate_limits)).':'.$num .'v'.$limit;
                $error = 'Limit reached, you can do this again next hour ('.$code.')';
                // CurrentUser::make()->logoutEverywhere();
                if(Request::isAjax() || substr(static::$path, -4) == '.php') {
                    $_SESSION['request_error_num'] = (!isset($_SESSION['request_error_num']) ? 1 : $_SESSION['request_error_num']+1);
                    echo json_encode(array('success' => false, 'status' => $error));
                } else {
                    $_SESSION['showError'] = $error;
                    header("Location: ".Request::siteUrl().'/dashboard');
                }
                die();
            }
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

    static function getIpAddress() {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } return $_SERVER['REMOTE_ADDR'];
        return '';
    }

    static function isVPN($geo=false) {
        if(!$geo) $geo = self::getGeo();
        $output = json_decode($geo);
        $currentIsp = $output->data->geo->isp;
        $blocklist = array(
            'Highwinds Network Group, Inc.', 'Highwinds Network Group', 'ZSCALER, INC.',
            'Micfo, LLC.', 'M247 Ltd', 'StackPath LLC', 'M247 Ltd.'
        );
        if(in_array($currentIsp, $blocklist)) return true;
        return false;
    }

    static function getGeo() {
        $ip_address = self::getIpAddress();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tools.keycdn.com/geo.json?host=$ip_address");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    // Some static pages have unique links
    static function link($key) {
        if(!isset(static::$page_links[$key])) return static::$site_url;
        return is_numeric(static::$page_links[$key]) ? get_the_permalink(static::$page_links[$key]) : static::$page_links[$key];
    }

    static function getNonce() {
        if(!isset($_SESSION['nonce'])) { $_SESSION['nonce'] = uniqid(); }
        return $_SESSION['nonce'];
    }
    static function validateNonce() {
        $nonce = static::getNonce();
        $_SESSION['nonce'] = uniqid();
        if(empty(static::post('nonce'))) return false;
        return (static::post('nonce') == $nonce);
    }

    // Wrapper for most ajax calls
    static function ajax() {
        static::noCache();

        $error = '';
        $return = array('success' => false, 'status' => '', 'nonce' => '');

        if(!in_array($_SERVER['REQUEST_METHOD'], array('POST'))) $error = 'Request failed successfully (E03:P)';
        if(!defined('ABSPATH')) $error = 'Request failed successfully (E04:ABS)';
        $user = CurrentUser::make();
        if($user->isBanned()) $error = 'Your account is banned from Assault.Online (E05:BN)';
        if(!$user->isLoggedIn()) $error = 'You must log in to perform this action (E06:LI)';
        if(isset($_SESSION['request_error_num']) && $_SESSION['request_error_num'] > Settings::get('max_request_errors')) {
            $error = 'Please login again and try again (E02:REN)';
            $_SESSION['request_error_num'] = 0;
            $user->logoutEverywhere();
        }
        if(static::part(1)!='header' && !static::validateNonce()) $error = 'Please refresh the page and try again (E01:VN)';

        if(empty($error)) {
            $province = $user->getProvince();
            if(!is_object($province)) $error = 'Request failed successfully (E07:GP)';
            else {
                if($province->get('user_lock') === 1) $error = 'Please reload the page and try again (E08:UL)';
            }
        }
        if(empty($error)) {
            $province->update('user_lock', 1);
            if(!in_array(static::part(1), array_keys(static::$ajax_paths))) $error = 'Request failed successfully (E09:AP)';// Make sure we are in a valid path
            else {
                $funcs = static::$ajax_paths[static::part(1)]; // Call function related to this path
                if($funcs[0] == 'province') $return = call_user_func(array($province, $funcs[1]), $return);
                else if($funcs[0] == 'clan') {
                    if($clan = $province->getClan()) {
                        $return = call_user_func(array($clan, $funcs[1]), $return);
                    } else $error = 'Request failed successfully (E10:GC)';
                }
            }
            $province->update('user_lock', 0);
        }

        if(!empty($error)) $return['status'] = $error;
        $return = array_merge(array('success' => false), $return);
        if($return['success'] == false) {
            $_SESSION['request_error_num'] = (!isset($_SESSION['request_error_num']) ? 1 : $_SESSION['request_error_num']+1);
        }

        $return = array_merge($return, array('nonce' => static::getNonce()));
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
            case 'html': $f = FILTER_SANITIZE_FULL_SPECIAL_CHARS; break; // For displaying html-code as code
            case 'raw': $f = FILTER_UNSAFE_RAW; break;
        }
        return (isset($_POST[$key]) ? filter_var(trim($_POST[$key]), $f) : false);
    }

}