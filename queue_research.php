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
	$_SESSION['status'] = 'Cannot queue another research. '.$researches[$research_queued]['name'].' already queued.';
	wp_redirect(get_permalink(4837));
	exit;
}

/* Get research input by user */
$research = $_POST['research'];
$totalturns = $userData['turns'][0];

if ($totalturns < 30) {
    $_SESSION['status'] = 'Not enough turns';
    wp_redirect(get_permalink(4837));
    exit;
}



/* set up arguments for creating research post */

update_user_meta($userId, 'queued_research', $research);
update_user_meta($userId, 'turns', $totalturns-30);

$_SESSION['status'] = $researches[$research]['name'].' research queued';
wp_redirect(get_permalink(4837));
exit;
