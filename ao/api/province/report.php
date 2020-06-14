<?php

function api_report($province, $return) {

    $target_id = Request::get('target','int');
    if(!empty($target_id)) {
        $target_user = User::make($target_id);
        if(!!$target_user) $target = $target_user->getProvince();
    }
    if(empty($target_id) || (!!$target && !$target->getName())) {
        return array_merge($return, array('success' => false, 'status' => 'Not a user'));
    }

    if(!$reports = $province->getReports($target_id)) {
        return array_merge($return, array('success' => false, 'status' => 'No spy reports for this player'));
    }

    $data = array();
    foreach ($reports as $type => $report) {
        $data[$type] = $report->getData(false);
    }

    return array_merge($return, array('success' => true, 'data' => $data, 'status' => 'Report from province #'.$target->get('id')));
}