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

/* Get necessary vars */
$userId = get_current_user_id();
$userData = get_user_meta($userId);
$userLock = $userData['user_lock'][0];
if ($userLock == 1) {
    wp_redirect(get_permalink(3360).'?id='.$target_id);
}
update_user_meta($userId, 'user_lock', 1);

$current_research = $userData['research_in_progress'][0];
if ($current_research != 0) {
    wp_redirect(get_permalink(4837));
    exit;
}
/* Get research input by user */
$research = $_POST['research'];
$totalturns = $userData['turns'][0];

if ($totalturns < 25) {
    $_SESSION['status'] = 'Not enough turns';
    wp_redirect(get_permalink(4837));
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
$args = array(
'post_title'    => $timestamp+($time*60*60*$research_reduce),  /* Receive research timestamp */
'post_status'   => 'publish',
'post_content'  => $research,
'post_type'     => 'research',
'post_author'   => $userId
);


$new_research_id = wp_insert_post($args);

update_user_meta($userId, 'research_in_progress', $research);

update_user_meta($userId, 'user_lock', 0);
$_SESSION['status'] = $researches[$research]['name'].' research started';
wp_redirect(get_permalink(4837));
exit;