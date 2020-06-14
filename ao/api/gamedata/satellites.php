<?php

function api_satellites($province, $return) {
    $data = Satellites::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Satellites gamedata'));
}