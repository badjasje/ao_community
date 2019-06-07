<?php
class CurrentUser extends User {

    protected $loggedin = false;
    private static $instance = null;

    private function __construct($props=null) {
        // Wordpress functionality
        if(is_user_logged_in()) {
            parent::__construct(wp_get_current_user()->ID);
            $this->loggedin=true;
        }
        static::$instance = $this;
    }
    private function __clone() {} // prevent cloning of the instance
    private function __wakeup() {} // prevent unserializing of the instance

    public static function make($items=null) {
        if (null === static::$instance) static::$instance = new CurrentUser();
        return static::$instance;
    }

    public function isLoggedIn() {
        return $this->loggedin;
    }

    /*public function login() {}
    public function logout() {}
    public function changePassword() {}
    public function editProfile() {}
    public function changeAvatar() {}
    public function exploreLand() {}
    public function sellLand() {}
    public function getProvince() {}
    */
}
