<?php
// This file should be included in all pages and ajax files

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
require_once( __DIR__. '/wp-config.php' );

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

// Handle ajax requests privatly
if(Request::isAjax()) {
    echo Request::ajax();
    die();
}
