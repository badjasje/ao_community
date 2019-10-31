<?php

function ajax_setresearch($province, $return) {
    if(!Round::isLive()) {
        return array('status' => 'Game is paused.');
    }
    $researchInProgress = $province->getCurrentResearch();
    $researchQueued = $province->getQueuedResearch();
    if($researchInProgress !== false && $researchQueued !== false) {
        return array('status' => 'There is already a research in progress, and you already queued a research.');
    }

    $new_key = Request::post('research');
    if(!Researches::get($new_key)) {
        return array('status' => 'No such research');
    }
    $new_research = $province->getResearches($new_key);
    if($new_research['level']>=$new_research['maxlevel']) {
        return array('status' => 'Max reached');
    }
    if($new_research['queued']) {
        return array('status' => 'Already queued');
    }
    if($new_research['inProgress'] && ($new_research['level']+1)>=$new_research['maxlevel']) {
        return array('status' => 'Already in progress');
    }

    $queueResearch = ($researchInProgress !== false);

    $totalturns = $province->getTurns();
    $turn_cost = $new_research['turns'];
    if($totalturns < $turn_cost) {
        return array('status' => 'Not enough turns');
    }

    $province->update('turns', $totalturns - $turn_cost);
    $province->turn_spread( ($queueResearch ? 'research_queue' : 'research'), $turn_cost); //@wp

    $return = array('success' => true,'started' => $new_key, 'status' => '', 'endtime' => 'queued');
    if($queueResearch === true) {
        $province->update('queued_research', $new_key);
        return array_merge($return, array('status' => $new_research['name'].' research queued'));
    }
    else {
        // set up arguments for creating research post
        $researchPost = Research::create($province->get('id'), $new_key);
        return array_merge($return, array(
            'status' => $new_research['name'].' research started',
            'hidebutton' => ($new_research['level']+1) >= $new_research['maxlevel'] ? $new_key.'_button' : '',
            'endtime' => $province->getResearchTimeLeft()
        ));
    }
    return array('success' => false, 'status' => 'Research task failed successfully');
}