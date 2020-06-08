<?php

function api_buildings($province, $return) {
    $data = Buildings::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Buildings gamedata'));
}