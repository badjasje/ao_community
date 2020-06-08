<?php

function api_missiles($province, $return) {
    $data = Missiles::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Missiles gamedata'));
}