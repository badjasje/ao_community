<?php
/**
 * Handles queueing researches
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

/* Get necessary vars */
$userId = get_current_user_id();
$userData = get_user_meta($userId);
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
    echo json_encode($array);
    exit;
exit;
