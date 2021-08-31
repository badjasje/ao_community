<?php

function ajax_reset($province, $return) {

    $reset_status = $province->get('reset_status');
    if(Round::isDev() || Round::isTest()) $reset_status = false; //You may reset more than once
    if(!empty($reset_status)) {
        return array('status' => 'You have already reset this round');
    }

    if($province->isProtected()) {
        return array('status' => 'You cannot reset while in Assault Protection');
    }

    if($clan = $province->getClan()) {
        $incomingWars = get_posts(array('numberposts' => -1, 'post_type' => 'wars', 'meta_key' => 'declared_on', 'meta_value' => $clan->get('id')));
        if(count($incomingWars) > 0) {
            return array('status' => 'You cannot reset your account while having incoming clan wars');
        }

        $outgoingWars = get_posts(array('numberposts' => -1, 'post_type' => 'wars', 'meta_key' => 'declared_by', 'meta_value' => $clan->get('id')));
        if(count($outgoingWars) > 0) {
            return array('status' => 'You cannot reset your account while having outgoing clan wars');
        }
    }

    $province->reset();
	$province->updateXP('reset');
    return array('success' => true);
}