<?php
class CurrentUser extends User {

    protected $loggedin = false;
    private static $instance = null;
    static $cache = 'users';

    private function __construct($props=null,$fromCache=true) {

        // Initialization of current user
        if(is_numeric($props)) {
            parent::__construct($props, $fromCache);
            $this->loggedin=true;
        } elseif(is_user_logged_in()) { //@wp
            parent::__construct(wp_get_current_user()->ID, $fromCache); //@wp
            $this->loggedin=true;
        }

        if(isset($_GET['checkmulti'])) {
            $this->isMulti();
        }

        // Validate session based on user-agent, ip-address and other stuff
        if(!$this->isAdmin()) $this->validateSession();

        // Redirects or exits if needed
        $this->validateCurrentPath();

        // After path validation
        if($this->isLoggedIn()) {
            $this->update('last_online', current_time('timestamp')); //@wp

            // Fill our session with browser data
            if(!isset($_SESSION['user'])) {
                $token = bin2hex(random_bytes(32));
                $time = current_time('timestamp');
                $_SESSION['user'] = array(
                    'id' => preg_replace("/[^0-9]+/", "", $this->get('id')), // XSS protection as we might print this value
                    'ipaddr' => $_SERVER['REMOTE_ADDR'],
                    'useragent' => $_SERVER['HTTP_USER_AGENT'],
                    'login_string' => hash('sha512', $token . $time . $_SERVER['HTTP_USER_AGENT']),
                    'token' => $token,
                    'session_started' => $time
                );
            }

            // Only really die when coming online
            $province = $this->getProvince();
            $province->count_all_stats(); // @todo: On each request might be a big hit
            if(!Request::isAjax() && $province->isDead() && $province->get('times_killed') == 0) {
                $province->afterDeath();
                $province->update('status', 'nukeprotection');
                $province->update('nuke_protection_timestamp', current_time('timestamp') + Settings::get('nuke_protection_length'));
            }
        }
        static::$instance = $this;
    }
    private function __clone() {} // prevent cloning of the instance
    private function __wakeup() {} // prevent unserializing of the instance

    public static function make($props=null,$fromCache=true) {
        if (null === static::$instance) static::$instance = new CurrentUser($props,$fromCache);
        return static::$instance;
    }

    /**
     * We can start to implement safe login by validating ip-address and user agent information
     */
    public function isLoggedIn() {
        return $this->loggedin;
    }

    /**
     * Session validation so we can prevent XSS attacks and session hijacking
     * This also prevents remote calling of ajax calls using a stolen cookie
     */
    public function validateSession() {
        $error = '';
        if(isset($_SESSION['user'])) {
            if(isset($_SERVER['HTTP_USER_AGENT']) && $_SESSION['user']['useragent'] != $_SERVER['HTTP_USER_AGENT']) {
                $error .= 'E02';
            }
            /*if(isset($_SERVER['REMOTE_ADDR']) && $_SESSION['user']['ipaddr'] != $_SERVER['REMOTE_ADDR']) {
                $error .= 'E03';
            }*/
            $login_check = hash('sha512', $_SESSION['user']['token'] . $_SESSION['user']['session_started'] . $_SERVER['HTTP_USER_AGENT']);
            if(!hash_equals($login_check, $_SESSION['user']['login_string'])) {
                $error .= 'E04';
            }
        }
        if(!empty($error)) {
            $this->logout();
            die('You might be the victim of an XSS attack, session hijack or remote cookie copy. If this problem keeps happening, let us know (code: '.$error.')');
        }
        return true;
    }

    /**
     * Access validation based on:
     * login, role, permissions, xp-level etc
     */
    public function validateCurrentPath() {
        if(!$this->isLoggedIn()) {
            $pathArray = array(
                'dashboard', 'events', 'buildings', 'spy-report-overview', 'units', 'clan', 'clan-wars', 'satellites',
                'buy', 'clan-information', 'player-statistics', 'users', 'clan-member-information', 'conversations',
                'explore', 'bank', 'sell', 'missiles', 'orders', 'research', 'send-aid', 'all-clans', 'forum',''
            );
            if(in_array(Request::part(0), $pathArray)) {
                header("HTTP/1.0 401 Unauthorized");
                if(!Request::isAjax()) { header("Location: ".Request::siteUrl()."/home/"); exit; }
                else { die('Access denied'); }
            }
        } else {
            if(in_array(Request::path(), array('home','register',''))) {
                header("HTTP/1.0 401 Unauthorized");
                if(!Request::isAjax()) { header("Location: ".Request::siteUrl()."/dashboard/"); exit; }
                else { die('Access denied'); }
            }
        }
    }

    /**
     *  WP-hook, happends after registration was successful.
     */
    public function register() {
        // @todo: We can set standards here, just like after_death
    }

    /**
     * WP-hook, this SHOULD be executed before the WordPress authentication process.
     * Only a die() or redirect works to disable actual logging in
     */
    public static function login($username) {
        if (!username_exists($username)) return;
        /* @todo: enable this at some point
        $user = get_user_by('login', $username);
        if(is_multi($user->ID)) {
            echo 'Please login with your own account. <a href="'. get_site_url() .'">Back</a>';
            die();
            return;
        }*/
        if(Request::isVPN()) {
            echo 'Your current Internet Service Provider has been blocked. You are not allowed to use Virtual Private Networks playing Assault.Online.';
            die();
            return;
        }
    }

    /**
     * WP-hook, when a user logs in by the wp_signon() function.
     * It is the very last action taken in the function, immediately following the wp_set_auth_cookie() call
     */
    public static function loggedin($login) {
        $wp_user = get_user_by('login', $login);
        $user = new CurrentUser($wp_user->ID, false); //Not from cache, make a new user

        $ip_array = maybe_unserialize(get_post_meta(139664, 'login_array_general', true));
        if($user->isMulti($ip_array)) {
            $user->logout();
            echo 'Please login with your own account. <a href="'. Request::siteUrl() .'">Back</a>';
            die();
            return false;
        }
        $output = Request::getGeo();
        if(Request::isVPN($output)) {
            $user->logout();
            echo 'Your current Internet Service Provider has been blocked. You are not allowed to use Virtual Private Networks playing Assault.Online.';
            die();
            return false;
        }

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = Request::getIpAddress();
        if(!isset($ip_array[$ip_address])) $ip_array[$ip_address] = array();
        $hostaddress = gethostbyaddr($ip_address);
        $ip_array[$ip_address][$user->get('id')] = array(date('Y-m-d H:i:s'), $useragent, $hostaddress, $output);
        update_post_meta(139664, 'login_array_general', $ip_array);

        $logindata = maybe_unserialize($user->get('logindata'));
        if(!is_array($logindata)) $logindata = array();
        $logindata[$ip_address] = array(date('Y-m-d H:i:s'), $useragent, $hostaddress, $output);
        $user->update('logindata', $logindata);
        return true;
    }

    /**
     * @todo: these huge data-array's should be stored elsewhere
     */
    public function isMulti($ip_array=false) {
        $user_ID = $this->get('id');
        if(isset($_GET['checkmulti'])) {
            if(isset($_GET['userid'])) $user_ID = $_GET['userid'];
        }
        if(in_array($user_ID, array(1,2,6,2768,2957))) { // Admins may have multi's?
            if(isset($_GET['checkmulti'])) { die('Admin: not a multi'); }
            return false;
        }

        if(!$ip_array) {
            $ip_array = maybe_unserialize(get_post_meta(139664, 'login_array_general', true));
        }

        $user = User::make($user_ID); //might no be me
        $ip_address = Request::getIpAddress();
        if(isset($_GET['checkmulti'])) {
            if(isset($_GET['ip'])) $ip_address = $_GET['ip'];
        }
        if(!isset($ip_array[$ip_address])) $ip_array[$ip_address] = array();

        // What was MY first login?
        $firstlogin = time();
        foreach($ip_array as $ip => $data) {
            if(in_array($user_ID, array_keys($data))) {
                if(strtotime($data[$user_ID][0]) < $firstlogin) $firstlogin = strtotime($data[$user_ID][0]);
            }
        }
        if(isset($_GET['checkmulti'])) {
            wtf(date('Y-m-d H:i:s',$firstlogin), $user_ID, $ip_address, $user->isBanned(), $ip_array[$ip_address]);
        }

        foreach($ip_array[$ip_address] as $uid => $data) {
            $tmpUser = User::make($uid);
            if(isset($_GET['checkmulti'])) wtf($uid != $user_ID, $tmpUser->isBanned(), $data[0]);
            if(!empty($uid) && $uid != $user_ID && !$tmpUser->isBanned()) { // Multi detected, this ip was previously used for another user
                // If my first login was later than any other account on this ip, block the login attempt
                if($firstlogin > strtotime($data[0])) {
                    if(isset($_GET['checkmulti'])) { wtf('Multi!', strtotime($data[0])); die(); }
                    return true;
                }
            }
        }
        if(isset($_GET['checkmulti'])) { die('Not a multi'); }
        return false;
    }

    public function logout() {
        wp_logout();
        unset($_SESSION['user']);
        session_destroy();
        // possible redirect? if(!Request::isAjax())
    }

    /*public function changePassword() {}
    public function editProfile() {}
    public function changeAvatar() {}
    */
}
