<?php

function api_units($province, $return) {
    $data = Units::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Units gamedata'));
}