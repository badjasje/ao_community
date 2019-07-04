<?php
class CurrentUser extends User {

    protected $loggedin = false;
    private static $instance = null;
    static $cache = 'users';

    private function __construct($props=null) {
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

            // Only really die when coming online
            $province = $this->getProvince();
            if(!Request::isAjax() && $province->isDead() && $province->get('times_killed') == 0) {
                $province->after_death();
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

    /*public function login() {}
    public function logout() {}
    public function changePassword() {}
    public function editProfile() {}
    public function changeAvatar() {}
    */
}
