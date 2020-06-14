<?php

function api_buildings($province, $return) {
    if(!isset($_POST['build'])) $_POST['build'] = array();
    if(!isset($_POST['demo'])) $_POST['demo'] = array();
    require_once(AJAX_PATH.'/province/buildings.php');
    return array_merge($return, ajax_buildings($province, $return));
}