<?php
// This file should be included in all pages and ajax files

// WP does not start sessions..
session_name('AOsession');
if (ini_set('session.use_only_cookies', 1) === FALSE) { // No $_GET sessions!
    die('Could not initiate a safe session');
}
if(!empty($_SERVER['SERVER_NAME'])) session_set_cookie_params(0, '/', '.'.$_SERVER['SERVER_NAME'], true, true);
session_start();

// Get configs needed for classes
define('SERVER_ROOT', __DIR__);
define('AO_PATH', SERVER_ROOT.'/ao');
define('CLASS_PATH', AO_PATH."/classes");
define('AJAX_PATH', AO_PATH."/ajax");
define('API_PATH', AO_PATH."/api");

// Often used functions
require_once(AO_PATH .'/functions.inc.php');

// Autoload classes: When using "new Class()" (or static) it requires automatically
require_once(CLASS_PATH.'/util/autoloader.class.php');
Autoloader::init();

// Set the current path, path in parts and get parameters for santitation
Request::init();

// Get round status, round type, round dates, etc
Round::init();

// Get some hooks fired
Startbonuses::init();
Researches::init();
Trophies::init();

// Instead of the $userId and $userData globals everywhere
// We can use "$user = CurrentUser::make()" everywhere without extra loads
$user = CurrentUser::make();
if($user->isBanned() && !Request::isAjax()) { echo '<br/><br/><center>Your account is banned from Assault.Online.</center>'; exit; }

// We check per path if you don't look it up too much
Request::pathRateLimit();

// Handle ajax requests privatly
if(Request::isAjax()) {
    echo Request::ajax();
    die();
}

// Handle API-requests privately
if(Request::isApi()) {
    header('Content-Type: application/json');
    echo Request::api();
    die();
}
