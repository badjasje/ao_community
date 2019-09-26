<?php
// This file should be included in all pages and ajax files

// WP does not start sessions..
session_name('AOsession');
if (ini_set('session.use_only_cookies', 1) === FALSE) { // No $_GET sessions!
    die('Could not initiate a safe session');
}
if(!empty($_SERVER['SERVER_NAME'])) session_set_cookie_params(0, '/', '.'.$_SERVER['SERVER_NAME'], true, true);
session_start();

// Easy debug
function wtf() {
	array_map(function($x) {
        if(is_object($x)||is_array($x)) echo '<pre>'.print_r($x,1).'</pre>'.PHP_EOL;
        else { var_dump($x); echo '<br>'.PHP_EOL; }
    }, func_get_args());
}

// Get configs needed for classes
$assaultOnlineDir = __DIR__.'/ao';
define('CLASS_PATH', $assaultOnlineDir."/classes");

// Autoload classes: When using "new Class()" (or static) it requires automatically
require_once(CLASS_PATH.'/util/autoloader.class.php');
Autoloader::init();

// Placeholder for if we ever want to translate the interface
// This will use a translation class at some point
function t($str) {
    return $str;
}

// Set the current path, path in parts and get parameters for santitation
Request::init();

// Get round status, round type, round dates, etc
Round::init();

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
