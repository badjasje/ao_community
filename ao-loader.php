<?php
// This file should be included in all pages and ajax files
/*
// Easy debug
function wtf() {
	array_map(function($x) {
        if(is_object($x)||is_array($x)) echo '<pre>'.print_r($x,1).'</pre>'.PHP_EOL;
        else { var_dump($x); echo '<br>'.PHP_EOL; }
    }, func_get_args());
}

// placeholder for if we ever want to translate the interface
function t($str) {
    return $str;
}

// Get configs needed for classes
$assaultOnlineDir = __DIR__.'/ao';
define('CLASS_PATH', $assaultOnlineDir."/classes");
require_once( __DIR__. '/wp-config.php' );

// Autoload classes: When using "new Class()" (or static) it requires automatically
require_once(CLASS_PATH.'/util/autoloader.class.php');
Autoloader::init();

// Instead of the $userId and $userData globals everywhere
$user = CurrentUser::make();
if($user->isBanned()) { echo '<br/><br/><center>Your account is banned from Assault.Online.</center>'; exit; }
*/