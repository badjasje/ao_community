<?php
class CurrentUser extends User {

    protected $loggedin = false;
    private static $instance = null;
    static $cache = 'users';

    private function __construct($props=null) {

        // Validate session based on user-agent, ip-address and other stuff
        $this->validateSession();

        // Initialization of current user
        if(is_user_logged_in()) { //@wp
            parent::__construct(wp_get_current_user()->ID); //@wp
            $this->loggedin=true;
            $this->update('last_online', current_time('timestamp')); //@wp
        }

        // Redirects or exits if needed
        $this->validateCurrentPath();

        // After path validation
        if($this->isLoggedIn()) {
            // @todo: $this->count_all_stats(); On each request might be a big hit
            // @todo: move function to here
            count_all_stats($this->get('id'));

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

    public static function make($items=null) {
        if (null === static::$instance) static::$instance = new CurrentUser();
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
            if(isset($_SERVER['REMOTE_ADDR']) && $_SESSION['user']['ipaddr'] != $_SERVER['REMOTE_ADDR']) {
                $error .= 'E03';
            }
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

    //public function login() {}
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
