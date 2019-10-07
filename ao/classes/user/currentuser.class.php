<?php
class CurrentUser extends User {

    protected $loggedin = false;
    private static $instance = null;
    static $cache = 'users';

    private function __construct($props=null,$fromCache=true) {

        $time = current_time('timestamp'); //@wp

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
            if($fromCache == true && $this->get('logouteverywhere') == true) {
                $this->logout();
                header("Location: ".Request::siteUrl()."/home/");
                exit;
            }

            $this->update('last_online', $time);

            // Fill our session with browser data
            if(!isset($_SESSION['user'])) {
                $token = bin2hex(random_bytes(32));
                $_SESSION['user'] = array(
                    'id' => preg_replace("/[^0-9]+/", "", $this->get('id')), // XSS protection as we might print this value
                    'ipaddr' => $_SERVER['REMOTE_ADDR'],
                    'useragent' => $_SERVER['HTTP_USER_AGENT'],
                    'login_string' => hash('sha512', $token . $time . $_SERVER['HTTP_USER_AGENT']),
                    'token' => $token,
                    'session_started' => $time,
                    'is_multi' => $this->isMulti()
                );
            }
            else {
                if(rand(1,10) == 1) {
                    $_SESSION['user']['is_multi'] = $this->isMulti();
                }
            }

            if(isset($_SESSION['user']['is_multi']) && $_SESSION['user']['is_multi']==true) {
                $this->logoutEverywhere();
                echo 'Please login with your own account. <a href="'. Request::siteUrl() .'">Back</a>';
                die();
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
            $this->logoutEverywhere();
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
                'explore', 'bank', 'sell', 'missiles', 'orders', 'research', 'send-aid', 'all-clans', 'forum',
                'send-message', ''
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
        $user->update('logouteverywhere', false);

        $ip_array = maybe_unserialize(get_post_meta(139664, 'login_array_general', true));
        if($user->isMulti($ip_array)) {
            $user->logoutEverywhere();
            echo 'Please login with your own account. <a href="'. Request::siteUrl() .'">Back</a>';
            die();
            return false;
        }
        $output = Request::getGeo();
        if(Request::isVPN($output)) {
            $user->logoutEverywhere();
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
        $my_userid = $this->get('id');
        if(isset($_GET['checkmulti'])) {
            if(isset($_GET['userid'])) $my_userid = $_GET['userid'];
        }
        if(in_array($my_userid, array(1,2,6,2768))) { // Admins may have multi's?
            if(isset($_GET['checkmulti'])) { die('Admin: not a multi'); }
            return false;
        }
        if(Round::isDev() || Round::isTest()) {
            if(isset($_GET['checkmulti'])) { die('Dev or Test, multi is ok'); }
            return false;
        }

        if(!$ip_array) {
            $ip_array = maybe_unserialize(get_post_meta(139664, 'login_array_general', true));
        }

        $my_user = User::make($my_userid); //might no be "me" (currentuser)
        if($my_user->isBanned()) {
            if(isset($_GET['checkmulti'])) { die('Banned: I don\'t care'); }
            return false;
        }
        if($my_user->get('multi_whitelist') == 1) {
            if(isset($_GET['checkmulti'])) { die('Whitelisted: not a multi'); }
            return false;
        }
        $my_ip = Request::getIpAddress();
        if(isset($_GET['checkmulti'])) {
            if(isset($_GET['ip'])) $my_ip = $_GET['ip'];
        }
        if(!isset($ip_array[$my_ip])) $ip_array[$my_ip] = array();

        // What was MY first login?
        $my_firstlogin = time();
        foreach($ip_array as $ip => $data) {
            if(in_array($my_userid, array_keys($data))) {
                if(strtotime($data[$my_userid][0]) < $my_firstlogin) $my_firstlogin = strtotime($data[$my_userid][0]);
            }
        }
        if(isset($_GET['checkmulti'])) {
            echo $my_user->getUsername() .' ('.$my_userid.')'. ($my_user->isBanned()?' BANNED!':'').' '.$my_ip.'<br>';
            echo 'Firstlogin: '. date('Y-m-d H:i:s',$my_firstlogin);
            wtf($ip_array[$my_ip]);
        }

        // Are there other accounts on this IP (exclude banned)?
        $other_accounts = array();
        foreach($ip_array[$my_ip] as $uid => $data) {
            $tmpUser = User::make($uid);
            if(isset($_GET['checkmulti']) && !empty($uid) && $uid != $my_userid) echo $tmpUser->getUsername() .' ('.$uid.')'. ($tmpUser->isBanned()?' BANNED!':'').'<br>';
            if(!empty($uid) && $uid != $my_userid && !$tmpUser->isBanned()) { // Multi detected, this ip also used by another user
                $other_accounts[$uid] = maybe_unserialize($tmpUser->get('logindata'));
            }
        }

        // Get first login of other (active) accounts that have shared this IP
        if(count($other_accounts)) {
            foreach($other_accounts as $uid => $logindata) {
                $firstlogin = time();
                foreach($logindata as $ip => $data) {
                    if(strtotime($data[0]) < $firstlogin) $firstlogin = strtotime($data[0]);
                }
                // If my first login was later than any other account on this ip, block the login attempt
                if($my_firstlogin > $firstlogin) {
                    if(isset($_GET['checkmulti'])) { echo '<strong>Multi!</strong> ('. $uid .') '. date('Y-m-d H:i:s', $firstlogin).' <br>'; die(); }
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
        // session_destroy(); // Do not destory session because of rate-limiting
        // possible redirect? if(!Request::isAjax())
    }

    public function logoutEverywhere() {
        $this->update('logouteverywhere', true);
        $this->logout();
    }

    /*public function changePassword() {}
    public function editProfile() {}
    public function changeAvatar() {}
    */
}
