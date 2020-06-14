<?php

function api_researches($province, $return) {
    $data = Researches::get();
    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Researches gamedata'));
}