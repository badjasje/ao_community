<?php

function api_startbonuses($province, $return) {
    $data = Startbonuses::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Startbonuses gamedata'));
}