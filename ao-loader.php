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
$assaultOnlineDir = __DIR__.'/ao';
define('CLASS_PATH', $assaultOnlineDir."/classes");
define('AJAX_PATH', $assaultOnlineDir."/ajax");

// Often used functions
require_once($assaultOnlineDir .'/functions.inc.php');

// Autoload classes: When using "new Class()" (or static) it requires automatically
require_once(CLASS_PATH.'/util/autoloader.class.php');
Autoloader::init();

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
