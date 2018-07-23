<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();

include 'research_array.php';
$array = array();
/* Get necessary vars */
$userId = get_current_user_id();
$userData = get_user_meta($userId);
$userLock = $userData['user_lock'][0];

if ($userLock == 1) {
    $array['status'] = 'Wait a couple of seconds, refresh the page and try again!';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
update_user_meta($userId, 'user_lock', 1);
$extraLevel = 0;
$current_research = $userData['research_in_progress'][0];
$research_queued = $userData['queued_research'][0];


if ($current_research != 0 && $research_queued != 0) {
    $array['status'] = 'There is already a research in progress, and you already queued a research.';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
if ($current_research == '0' && $research_queued == '0') { // Check if regular research is in order.
/* Get research input by user */
$research = $_POST['research'];


if($current_research == $research){
	$extraLevel = $extraLevel+1;
}
if($research_queued == $research){
	$extraLevel = $extraLevel+1;
}
$totalturns = $userData['turns'][0];

$current = $userData['level_' . $research][0];



if ($totalturns < 25) {
	$array['status'] = 'Not enough turns';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}
update_user_meta($userId, 'turns', $totalturns-25);

$timestamp = current_time('timestamp');

$startingbonus = $userData['starting_bonus'][0];
$research_reduce = 1;
if ($startingbonus == 'defensive') {
    $research_reduce = 0.9;
}

/* Get duration of research */
$time = $researches[$research]['duration'];

/* set up arguments for creating research post */
$endTime = $timestamp+(($time*60*60*$research_reduce));
$args = array(
'post_title'    => $endTime,  /* Receive research timestamp */
'post_status'   => 'publish',
'post_content'  => $research,
'post_type'     => 'research',
'post_author'   => $userId
);


$new_research_id = wp_insert_post($args);

update_user_meta($userId, 'research_in_progress', $research);
update_user_meta($userId, 'user_lock', 0);

$userData = get_user_meta($userId);
$current = $userData['level_' . $research][0];
$hide = '';
if($researches[$research]['maxlevel'] == ($current+1)){
	$hide = $research.'_button';
}

	$array['status'] = $researches[$research]['name'].' research started';
    $array['next'] = false;
    $array['started'] = $research;
    $array['hidebutton'] = $hide;
    $array['endtime'] = $time*60*60*$research_reduce;
    $array['turns'] = $userData['turns'][0];
    echo json_encode($array);
    exit;
}

elseif ($research_queued == '0' && $current_research != '0'){

$research_queued = $userData['queued_research'][0];
if($research_queued != '0'){
	$array['status'] = 'Cannot queue another research. '.$researches[$research_queued]['name'].' already queued.';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

/* Get research input by user */
$research = $_POST['research'];
$totalturns = $userData['turns'][0];

if ($totalturns < 30) {
    $array['status'] = 'Not enough turns';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}



/* set up arguments for creating research post */

update_user_meta($userId, 'queued_research', $research);
update_user_meta($userId, 'turns', $totalturns-30);


 	$array['status'] = $researches[$research]['name'].' research queued';
    $array['next'] = false;
    $array['turns'] = $totalturns-30;
    $array['started'] = $research;
    $array['endtime'] = 'queued';
    echo json_encode($array);
    exit;

}

