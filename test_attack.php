<?php
require_once("./wp-load.php");
nocache_headers();

if(!Round::isDev() && !Round::isTest() && !Round::isSandbox()) die('Not on live!');

global $userId;
if(isset($_GET['attacker'])) $userId = $_GET['attacker'];
else $userId = get_current_user_id(); // Attacker
global $userData;
$userData = get_user_meta($userId);
global $debug;
$debug = true;
$_POST = array(
    'attackarray' => array('paratrooper' => 1),
    'attacktype' => 'regular',
    'target_id' => 2768,
    'attackmode' => (isset($_GET['attackmode']) ? $_GET['attackmode'] : 'normal'), //aggressive
    'maintarget' => (isset($_GET['maintarget']) ? $_GET['maintarget'] : 'none')
);
if(isset($_GET['attackarray'])) {
    $_POST['attackarray'] = array();
    if(!is_array($_GET['attackarray'])) $_POST['attackarray'][$_GET['attackarray']] = 1;
    else $_POST['attackarray'] = $_GET['attackarray'];
}
if(isset($_GET['defender'])) {
    $_POST['target_id'] = $_GET['defender'];
    $_GET = array('id' => $_GET['defender']);
} else {
    $_GET = array('id' => 2); // Defender
    $_POST['target_id'] = 2;
}

function debug_update_user($user_id, $key, $value) {
    echo '<strong>Update '.$user_id.'</strong> '.$key.': '.$value.'<br>'.PHP_EOL;
}
function debug_var($key, $value) {
    echo '<strong>'.$key.'</strong>: '. $value.'<br>'.PHP_EOL;
}

require(ABSPATH.'/wp-content/themes/wp-bootstrap-starter/pages/attack/attack-result.php');
