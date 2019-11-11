<?php
require_once("wp-load.php");

function count_tot_units($user_id) {
    $units = Units::get();
    $units_owned = 0;
    $units_ordered = 0;
    foreach ($units as $key => $order) {
        $units_owned += get_user_meta($user_id, $key.'_owned')[0];
        $units_ordered += get_user_meta($user_id, $key.'_ordered')[0];
    }
    return array('owned' => $units_owned, 'ordered' => $units_ordered);
}

function count_tot_buildings($user_id) {
    $buildings = Buildings::get();
    $buildings_owned = 0;
    foreach ($buildings as $key => $order) {
        $buildings_owned += get_user_meta($user_id, $key)[0];
    }
    return array('owned' => $buildings_owned);
}


function count_airspace($user_id)
{
    $units = Units::get();
    $totalair = 0;

    foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        if ($unittype == 'air') {
            $units_owned = get_user_meta($user_id, $key.'_owned')[0];
            $units_ordered = get_user_meta($user_id, $key.'_ordered')[0];
            $totalair+=($units_owned+$units_ordered);
        }
    }
        return $totalair;
}
function count_allunits($user_id)
{
    $units = Units::get();
    $totalunits = 0;

    foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        if ($unittype == 'air' || $unittype == 'veh' || $unittype == 'inf' || $unittype == 'sea') {
            $units_owned = get_user_meta($user_id, $key.'_owned')[0];
            $totalunits+=($units_owned+$units_ordered);
        }
    }
        return $totalunits;
}

function count_vehspace($user_id)
{
    $units = Units::get();
    $totalveh = 0;

    foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        if ($unittype == 'veh') {
            $units_owned = get_user_meta($user_id, $key.'_owned')[0];
            $units_ordered = get_user_meta($user_id, $key.'_ordered')[0];
            $totalveh+=($units_owned+$units_ordered);
        }
    }
        return $totalveh;
}

function count_infspace($user_id)
{
    $units = Units::get();
    $totalinf = 0;

    foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        if ($unittype == 'inf') {
            $units_owned = get_user_meta($user_id, $key.'_owned')[0];
            $units_ordered = get_user_meta($user_id, $key.'_ordered')[0];
            $totalinf+=($units_owned+$units_ordered);
        }
    }
        return $totalinf;
}
function count_seaspace($user_id)
{
    $units = Units::get();
    $totalsea = 0;

    foreach ($units as $key => $order) {
        $unittype = $units[$key]['type'];
        if ($unittype == 'sea') {
            $units_owned = get_user_meta($user_id, $key.'_owned')[0];
            $units_ordered = get_user_meta($user_id, $key.'_ordered')[0];
            $totalsea+=($units_owned+$units_ordered);
        }
    }
        return $totalsea;
}
function count_missilespace($user_id)
{
    $missiles = Missiles::get();
    $totalmissiles = 0;

    foreach ($missiles as $key => $missile) {
        if ($key != 'tomahawk') {
            $missiles_owned = intval(get_user_meta($user_id, $key.'_owned', true));
            $missiles_ordered = intval(get_user_meta($user_id, $key.'_ordered', true));
            $totalmissiles+=($missiles_owned+$missiles_ordered);
        }
    }
        return $totalmissiles;
}
